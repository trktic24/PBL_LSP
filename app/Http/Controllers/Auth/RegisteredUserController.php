<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use App\Models\Asesi;
use App\Models\Asesor;
use App\Models\DataPekerjaanAsesi;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules;
use Illuminate\Validation\Rules\File;
use Illuminate\View\View;
use Carbon\Carbon;

class RegisteredUserController extends Controller
{
    public function create(): View
    {
        return view('auth.register');
    }

    public function store(Request $request): RedirectResponse
    {
        dd($request->all());

        $roleName = $request->input('role'); // 'asesi' atau 'asesor'
        $role = Role::where('nama_role', $roleName)->firstOrFail();

        // ✅ VALIDASI DASAR
        $rules = [
            'role' => ['required', 'string', 'in:asesi,asesor'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ];

        if ($roleName === 'asesi') {
            $asesiRules = [
                'nama_lengkap' => ['required', 'string', 'max:255'],
                'nik' => ['required', 'string', 'size:16', 'unique:asesi,nik'],
                'tempat_lahir' => ['required', 'string', 'max:100'],
                'tanggal_lahir' => ['required', 'string', 'date_format:d-m-Y'],
                'jenis_kelamin' => ['required', 'string', 'in:Laki-laki,Perempuan'],
                'kebangsaan' => ['required', 'string', 'max:100'],
                'kualifikasi' => ['required', 'string', 'max:255'], // -> pendidikan
                'pekerjaan' => ['required', 'string', 'max:255'],
                'alamat_rumah' => ['required', 'string'],
                'kode_pos' => ['required', 'string', 'max:10'],
                'kabupaten' => ['required', 'string', 'max:255'], // -> kabupaten_kota
                'provinsi' => ['required', 'string', 'max:255'],
                'no_hp' => ['required', 'string', 'max:16'], // -> nomor_hp
                'nama_institusi' => ['required', 'string', 'max:255'], // -> nama_institusi_perusahaan
                'alamat_institusi' => ['required', 'string'], // -> alamat_kantor
                'jabatan' => ['required', 'string', 'max:255'],
                'kode_pos_institusi' => ['nullable', 'string', 'max:15'],
                'no_telepon_institusi' => ['nullable', 'string', 'max:16'],
            ];
            $rules = array_merge($rules, $asesiRules);
        }

        if ($roleName === 'asesor') {
            $asesorRules = [
                'nama_lengkap' => ['required', 'string', 'max:255'],
                'no_registrasi_asesor' => ['required', 'string', 'max:50', 'unique:asesor,nomor_regis'],
                'nik' => ['required', 'string', 'size:16', 'unique:asesor,nik'],
                'tempat_lahir' => ['required', 'string', 'max:100'],
                'tanggal_lahir' => ['required', 'string', 'date_format:d-m-Y'],
                'jenis_kelamin' => ['required', 'string', 'in:Laki-laki,Perempuan'],
                'pekerjaan' => ['required', 'string', 'max:255'],
                'asesor_kebangsaan' => ['required', 'string', 'max:100'],
                'alamat_rumah' => ['required', 'string'],
                'kode_pos' => ['required', 'string', 'max:10'],
                'kabupaten' => ['required', 'string', 'max:255'],
                'provinsi' => ['required', 'string', 'max:255'],
                'no_hp' => ['required', 'string', 'max:14'],
                'npwp' => ['required', 'string', 'max:25'],
                'skema' => ['required', 'string', 'exists:skema,kode_skema'],
                'nama_bank' => ['required', 'string', 'max:100'],
                'nomor_rekening' => ['required', 'string', 'max:20'],

                // FILES
                'ktp_file' => ['required', File::types(['pdf', 'jpg', 'png'])->max(5 * 1024)],
                'foto_file' => ['required', File::types(['jpg', 'png'])->max(5 * 1024)],
                'npwp_file' => ['required', File::types(['pdf', 'jpg', 'png'])->max(5 * 1024)],
                'rekening_file' => ['required', File::types(['pdf', 'jpg', 'png'])->max(5 * 1024)],
                'cv_file' => ['required', File::types(['pdf'])->max(5 * 1024)],
                'ijazah_file' => ['required', File::types(['pdf', 'jpg', 'png'])->max(5 * 1024)],
                'sertifikat_asesor_file' => ['required', File::types(['pdf'])->max(5 * 1024)],
                'sertifikat_kompetensi_file' => ['required', File::types(['pdf'])->max(5 * 1024)],
                'ttd_file' => ['required', File::types(['png'])->max(5 * 1024)],
            ];
            $rules = array_merge($rules, $asesorRules);
        }

        $validated = $request->validate($rules);

        DB::beginTransaction();

        try {
            // === USERS ===
            $user = User::create([
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role_id' => $role->id,
            ]);

            $tanggalLahir = Carbon::createFromFormat('d-m-Y', $validated['tanggal_lahir'])->format('Y-m-d');
            $jk = $validated['jenis_kelamin'] === 'Laki-laki' ? 1 : 0;

            // === ASESI ===
            if ($roleName === 'asesi') {
                $asesi = Asesi::create([
                    'id_user' => $user->id_user,
                    'nama_lengkap' => $validated['nama_lengkap'],
                    'nik' => $validated['nik'],
                    'tempat_lahir' => $validated['tempat_lahir'],
                    'tanggal_lahir' => $tanggalLahir,
                    'jenis_kelamin' => $jk,
                    'kebangsaan' => $validated['kebangsaan'],
                    'pendidikan' => $validated['kualifikasi'],
                    'pekerjaan' => $validated['pekerjaan'],
                    'alamat_rumah' => $validated['alamat_rumah'],
                    'kode_pos' => $validated['kode_pos'],
                    'kabupaten_kota' => $validated['kabupaten'],
                    'provinsi' => $validated['provinsi'],
                    'nomor_hp' => $validated['no_hp'],
                ]);

                DataPekerjaanAsesi::create([
                    'id_asesi' => $asesi->id_asesi,
                    'nama_institusi_perusahaan' => $validated['nama_institusi'],
                    'jabatan' => $validated['jabatan'],
                    'alamat_kantor' => $validated['alamat_institusi'],
                    'kode_pos_institusi' => $validated['kode_pos_institusi'] ?? null,
                    'no_telepon_institusi' => $validated['no_telepon_institusi'] ?? null,
                ]);
            }

            // === ASESOR ===
            if ($roleName === 'asesor') {
                $fileMapping = [
                    'ktp_file' => 'ktp',
                    'foto_file' => 'pas_foto',
                    'npwp_file' => 'NPWP_foto',
                    'rekening_file' => 'rekening_foto',
                    'cv_file' => 'CV',
                    'ijazah_file' => 'ijazah',
                    'sertifikat_asesor_file' => 'sertifikat_asesor',
                    'sertifikat_kompetensi_file' => 'sertifikasi_kompetensi',
                    'ttd_file' => 'tanda_tangan',
                ];

                $storagePath = "public/asesor_docs/{$user->id_user}";
                $filePaths = [];

                foreach ($fileMapping as $form => $column) {
                    if ($request->hasFile($form)) {
                        $path = $request->file($form)->store($storagePath);
                        $filePaths[$column] = $path;
                    }
                }
                // Sebelum Asesor::create()
                $skema = \App\Models\Skema::where('kode_skema', $validated['skema'])->first(); // Ganti 'kode_skema' sesuai langkah 1
                Asesor::create([
                    'id_user' => $user->id_user,
                    'id_skema' => $skema ? $skema->id_skema : null,
                    'nama_lengkap' => $validated['nama_lengkap'],
                    'nomor_regis' => $validated['no_registrasi_asesor'],
                    'nik' => $validated['nik'],
                    'tempat_lahir' => $validated['tempat_lahir'],
                    'tanggal_lahir' => $tanggalLahir,
                    'jenis_kelamin' => $jk,
                    'pekerjaan' => $validated['pekerjaan'],
                    'kebangsaan' => $validated['asesor_kebangsaan'],
                    'alamat_rumah' => $validated['alamat_rumah'],
                    'kode_pos' => $validated['kode_pos'],
                    'kabupaten_kota' => $validated['kabupaten'],
                    'provinsi' => $validated['provinsi'],
                    'nomor_hp' => $validated['no_hp'],
                    'NPWP' => $validated['npwp'],
                    'nama_bank' => $validated['nama_bank'],
                    'norek' => $validated['nomor_rekening'],
                    ...$filePaths,
                ]);
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            if ($roleName === 'asesor' && isset($storagePath)) {
                Storage::deleteDirectory($storagePath);
            }
            return back()->with('error', 'Terjadi kesalahan: '.$e->getMessage())->withInput();
        }

        event(new Registered($user));
        Auth::login($user);
        return redirect(RouteServiceProvider::HOME);
    }
}

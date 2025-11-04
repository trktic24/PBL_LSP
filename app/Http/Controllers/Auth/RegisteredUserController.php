<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Asesi;
use App\Models\Asesor;
use App\Models\DataPekerjaanAsesi;
use App\Models\Role;
use App\Models\Skema;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Carbon\Carbon;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Illuminate\Validation\Rules\File;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Tampilkan view registrasi.
     */
    public function create(Request $request): View
    {
        // Bersihkan session Google jika user kembali ke step 1
        if (!$request->has('step') || $request->step == 1) {
            session()->forget('google_register_data');
        }
        return view('auth.register');
    }

    /**
     * Tangani request registrasi.
     */
    public function store(Request $request): RedirectResponse
    {
        // Gabungkan data session Google ke request jika ada
        if (session()->has('google_register_data')) {
            $request->merge([
                'is_google_register' => true,
                'google_id' => session('google_register_data.google_id'),
                'email' => session('google_register_data.email'),
                'nama_lengkap' => session('google_register_data.name'),
                'role' => session('google_register_data.role'),
            ]);
        }

        $isGoogle = $request->boolean('is_google_register');
        $roleName = $request->input('role'); // 'asesi' atau 'asesor'

        // Ambil model Role. Ini akan dipakai untuk 'role_id'
        $role = Role::where('nama_role', $roleName)->firstOrFail();

        // ✅ VALIDASI DASAR
        $rules = [
            'role' => ['required', 'string', 'in:asesi,asesor'],
            // Rule 'unique' akan diabaikan untuk user Google jika email sudah ada di request (dari session)
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
            'password' => $isGoogle ? ['nullable'] : ['required', 'confirmed', Rules\Password::defaults()],
            'is_google_register' => ['nullable', 'boolean'],
            'google_id' => ['nullable', 'string'],
        ];

        // ✅ VALIDASI ASESI (GANTI DARI NULLABLE -> REQUIRED)
        if ($roleName === 'asesi') {
            $asesiRules = [
                'nama_lengkap' => ['required', 'string', 'max:255'],
                'nik' => ['nullable', 'string', 'size:16', 'unique:asesi,nik'],
                'tempat_lahir' => ['nullable', 'string', 'max:100'],
                'tanggal_lahir' => ['nullable', 'string', 'date_format:d-m-Y'],
                'jenis_kelamin' => ['nullable', 'string', 'in:Laki-laki,Perempuan'],
                'kebangsaan' => ['nullable', 'string', 'max:100'],
                'kualifikasi' => ['nullable', 'string', 'max:255'], // -> pendidikan
                'pekerjaan' => ['nullable', 'string', 'max:255'],
                'alamat_rumah' => ['nullable', 'string'],
                'kode_pos' => ['nullable', 'string', 'max:10'],
                'kabupaten' => ['nullable', 'string', 'max:255'], // -> kabupaten_kota
                'provinsi' => ['nullable', 'string', 'max:255'],
                'no_hp' => ['nullable', 'string', 'max:16'], // -> nomor_hp
                'nama_institusi' => ['nullable', 'string', 'max:255'], // -> nama_institusi_perusahaan
                'alamat_institusi' => ['nullable', 'string'], // -> alamat_kantor
                'jabatan' => ['nullable', 'string', 'max:255'],
                'kode_pos_institusi' => ['nullable', 'string', 'max:15'],
                'no_telepon_institusi' => ['nullable', 'string', 'max:16'], // Ini opsional
            ];
            $rules = array_merge($rules, $asesiRules);
        }

        // ✅ VALIDASI ASESOR (GANTI DARI NULLABLE -> REQUIRED)
        if ($roleName === 'asesor') {
            $asesorRules = [
                'nama_lengkap' => ['required', 'string', 'max:255'],
                'no_registrasi_asesor' => ['nullable', 'string', 'max:50', 'unique:asesor,nomor_regis'],
                'nik' => ['nullable', 'string', 'size:16', 'unique:asesor,nik'],
                'tempat_lahir' => ['nullable', 'string', 'max:100'],
                'tanggal_lahir' => ['nullable', 'string', 'date_format:d-m-Y'],
                'jenis_kelamin' => ['nullable', 'string', 'in:Laki-laki,Perempuan'],
                'pekerjaan' => ['nullable', 'string', 'max:255'],
                'asesor_kebangsaan' => ['nullable', 'string', 'max:100'],
                'alamat_rumah' => ['nullable', 'string'],
                'kode_pos' => ['nullable', 'string', 'max:10'],
                'kabupaten' => ['nullable', 'string', 'max:255'],
                'provinsi' => ['nullable', 'string', 'max:255'],
                'no_hp' => ['nullable', 'string', 'max:14'],
                'npwp' => ['nullable', 'string', 'max:25'],
                'skema' => ['nullable', 'string'],
                'nama_bank' => ['nullable', 'string', 'max:100'],
                'nomor_rekening' => ['nullable', 'string', 'max:20'],

                // FILES (bisa 'nullable' atau 'nullable' tergantung logika form, 'nullable' oke)
                'ktp_file' => ['nullable', File::types(['pdf', 'jpg', 'png'])->max(5 * 1024)],
                'foto_file' => ['nullable', File::types(['pdf', 'jpg', 'png'])->max(5 * 1024)],
                'npwp_file' => ['nullable', File::types(['pdf', 'jpg', 'png'])->max(5 * 1024)],
                'rekening_file' => ['nullable', File::types(['pdf', 'jpg', 'png'])->max(5 * 1024)],
                'cv_file' => ['nullable', File::types(['pdf', 'jpg', 'png'])->max(5 * 1024)],
                'ijazah_file' => ['nullable', File::types(['pdf', 'jpg', 'png'])->max(5 * 1024)],
                'sertifikat_asesor_file' => ['nullable', File::types(['pdf', 'jpg', 'png'])->max(5 * 1024)],
                'sertifikat_kompetensi_file' => ['nullable', File::types(['pdf', 'jpg', 'png'])->max(5 * 1024)],
                'ttd_file' => ['nullable', File::types(['png'])->max(5 * 1024)],
            ];
            $rules = array_merge($rules, $asesorRules);
        }

        $validated = $request->validate($rules);

        DB::beginTransaction();

        try {
            // === USERS ===
            $user = User::create([
                'email' => $validated['email'],
                'password' => $isGoogle
                                ? Hash::make(Str::random(24)) // Password random
                                : Hash::make($validated['password']), // Password dari form
                'google_id' => $validated['google_id'] ?? null,

                // V V V INI ADALAH FIX UTAMA V V V
                'role_id' => $role->id, // Simpan 'role_id' (angka), BUKAN 'role' (string)
                // ^ ^ ^ INI ADALAH FIX UTAMA ^ ^ ^

                'email_verified_at' => $isGoogle ? now() : null, // Verifikasi jika Google
            ]);

            // Format tanggal & JK
            $tanggalLahir = Carbon::createFromFormat('d-m-Y', $validated['tanggal_lahir'])->format('Y-m-d');
            $jk = $validated['jenis_kelamin'] === 'Laki-laki' ? 1 : 0;

            // === ASESI ===
            if ($roleName === 'asesi') {
                $asesi = Asesi::create([
                    'id_user' => $user->id_user, // Asumsi 'id_user' adalah Primary Key
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
                    'nama_institusi_pekerjaan' => $validated['nama_institusi'],
                    'jabatan' => $validated['jabatan'],
                    'alamat_institusi' => $validated['alamat_institusi'],
                    'kode_pos_institusi' => $validated['kode_pos_institusi'],
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

                // $skema = Skema::where('id_skema', $validated['skema'])->first();

                Asesor::create([
                    'id_user' => $user->id_user,
                    'id_skema' => null,
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
                    ...$filePaths, // Gabungkan semua path file
                ]);
            }

            DB::commit();

            // Bersihkan session Google setelah sukses
            session()->forget(['google_register_data']);

        } catch (\Exception $e) {
            DB::rollBack();
            // Hapus folder file jika gagal simpan asesor
            if ($roleName === 'asesor' && isset($storagePath)) {
                Storage::deleteDirectory($storagePath);
            }
            // Kembalikan ke form dengan error
            return back()->with('error', 'Terjadi kesalahan: '.$e->getMessage())->withInput();
        }

        event(new Registered($user));
        Auth::login($user);

        // Redirect ke dashboard yang sesuai
        if ($roleName === 'asesi') {
            return redirect()->route('asesi.dashboard');
        } elseif ($roleName === 'asesor') {
            return redirect()->route('asesor.dashboard');
        }

        // Fallback
        return redirect()->route('dashboard');
    }
}

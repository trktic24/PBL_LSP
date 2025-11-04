<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Asesor;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AsesorController extends Controller
{
    // ==========================================================
    // MENAMPILKAN HALAMAN (GET)
    // ==========================================================

    public function createStep1(Request $request)
    {
        // Ambil data lama dari session jika ada (untuk tombol 'back')
        $asesor = $request->session()->get('asesor');
        return view('master.asesor.add_asesor1', compact('asesor'));
    }

    public function createStep2(Request $request)
    {
        $asesor = $request->session()->get('asesor');
        return view('master.asesor.add_asesor2', compact('asesor'));
    }

    public function createStep3(Request $request)
    {
        $asesor = $request->session()->get('asesor');
        return view('master.asesor.add_asesor3', compact('asesor'));
    }

    // ==========================================================
    // MEMPROSES & MENYIMPAN DATA (POST)
    // ==========================================================

    /**
     * Simpan data dari Step 1 ke Session
     */
    public function postStep1(Request $request)
    {
        $validatedData = $request->validate([
            'email' => 'required|email|unique:users,email',
            'username' => 'required|string|unique:users,username',
            'password' => 'required|string|min:4|confirmed',
            'id_skema' => 'required|exists:master_skema,id_skema',
        ]);

        // Inisialisasi session jika belum ada
        $asesor = $request->session()->get('asesor', new Asesor());
        $asesor->fill($validatedData);
        $request->session()->put('asesor', $asesor);

        return redirect()->route('add_asesor2');
    }

    /**
     * Simpan data dari Step 2 ke Session
     */
    public function postStep2(Request $request)
    {
        $validatedData = $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'nik' => 'required|string|size:16|unique:asesor,nik',
            'tempat_lahir' => 'required|string',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|boolean',
            'kebangsaan' => 'required|string',
            'alamat_rumah' => 'required|string',
            'kode_pos' => 'required|string:5',
            'kabupaten_kota' => 'required|string',
            'provinsi' => 'required|string',
            'nomor_hp' => 'required|string:14',
            'pekerjaan' => 'required|string',
            'nama_bank' => 'required|string',
            'norek' => 'required|string:20',
            'NPWP' => 'required|string:25',
        ]);

        $asesor = $request->session()->get('asesor');
        $asesor->fill($validatedData);
        $request->session()->put('asesor', $asesor);

        return redirect()->route('add_asesor3');
    }

    /**
     * Simpan data dari Step 3 dan masukkan ke Database
     */
    public function store(Request $request)
    {
        $asesorData = $request->session()->get('asesor');

        // Validasi file
        $validatedFiles = $request->validate([
            'ktp' => 'required|file|mimes:pdf,jpg,png|max:2048',
            'pas_foto' => 'required|file|mimes:jpg,png|max:2048',
            'NPWP_foto' => 'required|file|mimes:pdf,jpg,png|max:2048',
            'rekening_foto' => 'required|file|mimes:pdf,jpg,png|max:2048',
            'CV' => 'required|file|mimes:pdf|max:2048',
            'ijazah' => 'required|file|mimes:pdf|max:2048',
            'sertifikat_asesor' => 'required|file|mimes:pdf|max:2048',
            'sertifikasi_kompetensi' => 'required|file|mimes:pdf|max:2048',
            'tanda_tangan' => 'required|file|mimes:png|max:2048',
        ]);

        // Gunakan Transaksi Database untuk keamanan
        DB::beginTransaction();
        try {
            // 1. Buat User baru
            $user = User::create([
                'username' => $asesorData->username,
                'email' => $asesorData->email,
                'password' => Hash::make($asesorData->password),
                'role_id' => 2, // Asumsi '2' adalah ID untuk role Asesor
            ]);

            // 2. Upload semua file
            foreach ($validatedFiles as $key => $file) {
                $path = $file->store('public/asesor_files/' . $user->id_user);
                $asesorData[$key] = $path; // Simpan path-nya
            }

            // 3. Isi sisa data Asesor
            $asesorData['id_user'] = $user->id_user;
            $asesorData['nomor_regis'] = 'REG-' . date('Ym') . $user->id_user; // Contoh No. Regis
            $asesorData['is_verified'] = false; // Admin harus verifikasi

            // 4. Simpan Asesor ke database
            Asesor::create($asesorData->toArray());

            // 5. Jika sukses, hapus session dan commit
            DB::commit();
            $request->session()->forget('asesor');

            return redirect()->route('master_asesor')->with('success', 'Asesor baru berhasil ditambahkan!');

        } catch (\Exception $e) {
            // 6. Jika gagal, batalkan semua dan kembali
            DB::rollBack();
            Log::error('Gagal menyimpan asesor: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan. ' . $e->getMessage());
        }
    }
}
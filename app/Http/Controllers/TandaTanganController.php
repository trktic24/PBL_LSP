<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; 
use Illuminate\View\View; // Import View untuk method yang menampilkan halaman
use App\Models\TandaTanganPemohon; 
use App\Models\Asesi; 

class TandaTanganController extends Controller
{
    /**
     * Menampilkan form Tanda Tangan Pemohon dengan data Asesi yang otomatis terisi.
     */
    public function showTandaTanganForm(): View
    {
        // 1. Dapatkan ID User yang sedang login
        // GANTI INI JIKA ANDA MENGGUNAKAN LOGIC USER ID BERBEDA DARI AUTH::ID()
        $idUser = Auth::id() ?? 1; 
        
        // 2. Cari data Asesi yang memiliki id_user ini
        if ($idUser) {
            $asesiData = Asesi::where('id_user', $idUser)->first(); 
        }

        // 3. Jika Asesi tidak ditemukan (misal user baru belum mengisi data asesi)
        if (!$asesiData) {
             // Membuat Model Asesi baru dengan nilai default agar View tidak error (Fatal Error)
             $asesiData = new Asesi([
                'nama_lengkap' => 'Nama Peserta (User Belum Terdaftar sebagai Asesi)',
                'pekerjaan' => 'Jabatan (Default)',
                'kebangsaan' => 'Politeknik Negeri Semarang (Default)', // Asumsi Perusahaan
                'alamat_rumah' => 'Jl. Prof. Sudarto, Tembalang (Default)' // Asumsi Alamat
             ]);
        }

        // 4. Kirim data Asesi ke View (resources/views/tanda_tangan_pemohon.blade.php)
        return view('tanda_tangan_pemohon', [
            'asesi' => $asesiData
        ]);
    }
    
    /**
     * Menyimpan atau mengupdate data tanda tangan asesi (Method yang sudah Anda miliki).
     */
    public function simpanTandaTangan(Request $request)
    {
        // 1. Validasi Data
        $request->validate([
            'data_tanda_tangan' => 'required|string', 
        ], [
            'data_tanda_tangan.required' => 'Data tanda tangan wajib diisi. Mohon klik "Simpan" terlebih dahulu.'
        ]);

        // 2. Tentukan ID Asesi
        $idUser = Auth::id(); 

        // Cari data Asesi yang memiliki id_user ini
        $asesi = Asesi::where('id_user', $idUser)->first();

        if (!$asesi) {
            return back()->with('error', 'Gagal: Data Asesi tidak ditemukan untuk User ini. Silakan periksa data master Anda.');
        }

        $idAsesi = $asesi->id_asesi; // Ambil Primary Key dari tabel Asesi
        
        // 3. Simpan atau Update Tanda Tangan
        try {
            TandaTanganPemohon::updateOrCreate(
                ['id_asesi' => $idAsesi], 
                ['data_tanda_tangan' => $request->input('data_tanda_tangan')]
            );

        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menyimpan data ke database. Cek relasi Foreign Key di tabel asesi.');
        }
            
        // 4. Redirect Sukses
        return redirect('/tunggu_upload_dokumen')->with('success', 'Tanda tangan berhasil disimpan. Lanjut ke proses upload dokumen.');
    }
}
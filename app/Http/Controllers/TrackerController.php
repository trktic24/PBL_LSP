<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\DataSertifikasiAsesi;
use App\Models\Jadwal;
use Carbon\Carbon;

class TrackerController extends Controller
{
    /**
     * Menampilkan halaman tracker untuk user yang sedang login.
     * [PERUBAHAN] Menerima parameter opsional $jadwal_id
     */
    public function index($jadwal_id = null) // [PERUBAHAN] Tambahkan parameter opsional
    {
        $user = Auth::user();
        $asesi = $user->asesi; 
        $sertifikasi = null; 

        if ($asesi) {
            // Siapkan query dasar
            $query = $asesi->dataSertifikasi()->with('jadwal.skema');
            
            // [PERUBAHAN] Tambahkan logika if/else
            if ($jadwal_id) {
                // Jika ada id_jadwal, cari berdasarkan ID itu
                $sertifikasi = $query->where('id_jadwal', $jadwal_id)->first();
            } else {
                // Jika tidak ada, ambil pendaftaran pertama (perilaku lama)
                $sertifikasi = $query->first(); 
            }
            // [AKHIR PERUBAHAN]
        }
        
        // 4. Kirim data (bisa jadi $sertifikasi itu null) ke view
        return view('tracker', [
            'sertifikasi' => $sertifikasi
        ]);
    }

    /**
     * ========================================================
     * FUNGSI API BARU: Untuk mendaftarkan user ke jadwal
     * ========================================================
     */
    public function daftarJadwal(Request $request)
    {
        $user = Auth::user();
        
        // 1. Validasi input
        $request->validate([
            'id_jadwal' => 'required|integer|exists:jadwal,id_jadwal',
        ]);

        $id_jadwal = $request->id_jadwal;
        $asesi = $user->asesi;

        // 2. Pastikan asesi ada
        if (!$asesi) {
            return response()->json([
                'success' => false,
                'message' => 'Data asesi Anda tidak ditemukan. Harap lengkapi profil Anda.'
            ], 404);
        }

        // 3. Cek apakah user sudah mendaftar
        // [PERUBAHAN] Ganti exists() menjadi first() agar kita bisa dapatkan datanya
        $existing = DataSertifikasiAsesi::where('id_asesi', $asesi->id_asesi)
                                ->where('id_jadwal', $id_jadwal)
                                ->first(); 
        
        if ($existing) {
            return response()->json([
                'success' => false,
                // [PERUBAHAN] Arahkan ke tracker yang SUDAH ADA datanya
                'redirect_url' => route('tracker', ['jadwal_id' => $existing->id_jadwal]), 
                'message' => 'Anda sudah memiliki pendaftaran aktif. Mengarahkan ke data Anda...'
            ], 409); // 409 Conflict
        }

        try {
            // 4. Buat pendaftaran baru
            $newSertifikasi = DataSertifikasiAsesi::create([
                'id_asesi' => $asesi->id_asesi,
                'id_jadwal' => $id_jadwal,
                'tujuan_asesmen' => 'Sertifikasi', // Default
                'tanggal_daftar' => Carbon::now(),
            ]);

            // 5. Kirim respons sukses
            return response()->json([
                'success' => true,
                // [PERUBAHAN] Arahkan ke tracker dengan ID JADWAL YANG BARU DIBUAT
                'redirect_url' => route('tracker', ['jadwal_id' => $newSertifikasi->id_jadwal]), 
                'message' => 'Berhasil mendaftar! Mengarahkan Anda ke Tracker...'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan server saat mendaftar: ' . $e->getMessage()
            ], 500);
        }
    }
}
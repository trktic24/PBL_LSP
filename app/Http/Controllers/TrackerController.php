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
     */
    public function index()
    {
        $user = Auth::user();
        
        // 1. Dapatkan asesi dari user
        $asesi = $user->asesi; 
        
        // 2. Siapkan variabel $sertifikasi
        $sertifikasi = null; 

        if ($asesi) {
            // 3. Jika asesi ada, ambil pendaftarannya (HasOne)
            // Kita Eager Load relasi jadwal dan skema-nya
            $sertifikasi = $asesi->dataSertifikasi() // Menggunakan relasi HasOne
                                ->with('jadwal.skema') 
                                ->first(); 
        }
        
        // 4. Kirim data (bisa jadi $sertifikasi itu null) ke view
        // Pastikan view Anda ada di 'resources/views/tracker.blade.php'
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
        
        // 1. Validasi input (pastikan id_jadwal dikirim)
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

        // 3. Cek apakah user sudah mendaftar (Hanya 1 pendaftaran, sesuai HasOne)
        $existing = DataSertifikasiAsesi::where('id_asesi', $asesi->id_asesi)->exists();
        if ($existing) {
            return response()->json([
                'success' => false,
                'redirect_url' => route('tracker'), // <-- [PERBAIKAN] Diubah dari tracker.index
                'message' => 'Anda sudah memiliki pendaftaran aktif. Selesaikan dulu sebelum mendaftar lagi.'
            ], 409); // 409 Conflict
        }

        try {
            // 4. Buat pendaftaran baru
            // (Anda bisa menambahkan nilai default untuk kolom lain di sini jika perlu)
            DataSertifikasiAsesi::create([
                'id_asesi' => $asesi->id_asesi,
                'id_jadwal' => $id_jadwal,
                'tujuan_asesmen' => 'Sertifikasi', // Default
                'tanggal_daftar' => Carbon::now(),
            ]);

            // 5. Kirim respons sukses
            return response()->json([
                'success' => true,
                'redirect_url' => route('tracker'), // <-- [PERBAIKAN] Diubah dari tracker.index
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
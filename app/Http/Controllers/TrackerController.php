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
     * Mendukung parameter opsional $jadwal_id untuk redirect spesifik.
     */
    public function index($jadwal_id = null)
    {
        $user = Auth::user();
        $asesi = $user->asesi; 
        $sertifikasi = null; 

        if ($asesi) {
            // 1. Siapkan query dasar
            // [PENTING] Tambahkan 'jadwal.asesor' di sini agar data asesor terambil
            $query = $asesi->dataSertifikasi()->with([
                'jadwal.skema',
                'jadwal.asesor' // <-- INI KUNCINYA BUAT SIDEBAR ASESOR
            ]);
            
            // 2. Logika Pengambilan Data
            if ($jadwal_id) {
                // Jika ada id_jadwal (dari redirect), cari yang spesifik itu
                $sertifikasi = $query->where('id_jadwal', $jadwal_id)->first();
            } else {
                // Jika buka tracker biasa, ambil data TERBARU (latest)
                // Biar user langsung liat progress terakhir mereka
                $sertifikasi = $query->latest()->first(); 
            }
        }
        
        // 3. Kirim ke View
        return view('tracker', [
            'sertifikasi' => $sertifikasi
        ]);
    }

    /**
     * ========================================================
     * FUNGSI API: Untuk mendaftarkan user ke jadwal
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

        // 3. Cek apakah user sudah mendaftar di jadwal ini?
        $existing = DataSertifikasiAsesi::where('id_asesi', $asesi->id_asesi)
                                        ->where('id_jadwal', $id_jadwal)
                                        ->first(); 
        
        if ($existing) {
            return response()->json([
                'success' => false,
                // Arahkan ke tracker jadwal tersebut
                'redirect_url' => route('tracker', ['jadwal_id' => $existing->id_jadwal]), 
                'message' => 'Anda sudah memiliki pendaftaran aktif. Mengarahkan ke data Anda...'
            ], 409);
        }

        try {
            // 4. Buat pendaftaran baru
            // Status default biasanya diatur di database ('sedang_mendaftar')
            $newSertifikasi = DataSertifikasiAsesi::create([
                'id_asesi' => $asesi->id_asesi,
                'id_jadwal' => $id_jadwal,
                'tujuan_asesmen' => 'Sertifikasi', // Default, nanti diubah user di formulir
                'tanggal_daftar' => Carbon::now(),
            ]);

            // 5. Kirim respons sukses
            return response()->json([
                'success' => true,
                // Arahkan ke tracker dengan ID JADWAL YANG BARU DIBUAT
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
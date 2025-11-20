<?php

namespace App\Http\Controllers\KerahasiaanAPI;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Asesor; 
use App\Models\Asesi; 
use Illuminate\Support\Facades\Log;
// Hapus 'use File' dan 'use Validator' kalo nggak dipake di sini

class PersetujuanKerahasiaanAPIController extends Controller
{
    /**
     * METHOD API 1: Menyediakan data FR.AK.01 dalam format JSON.
     */
    public function getFrAk01Data($id_asesi)
    {
        Log::info("API: Mencari data FR.AK.01 untuk Asesi ID $id_asesi...");
        
        try {
            // Ambil data Asesi + relasi-relasinya
            $asesi = Asesi::with([
                'dataPekerjaan',
                'dataSertifikasi', 
                'user'
            ])->findOrFail($id_asesi);

            // Ambil data Asesor (masih dummy, bisa lu ganti)
            $asesor = Asesor::first() ?? (object)['nama_lengkap' => 'Asesor (Data Dummy)'];
            
            // Siapin data dummy TUK & Bukti
            $data_asesmen = [
                'tuk' => 'Sewaktu', 
                'bukti_dikumpulkan' => [
                    'Verifikasi Portofolio', 'Hasil Test Tulis', 'Hasil Wawancara',
                ],
            ];

            // Kirim semua data sebagai JSON
            return response()->json([
                'asesor' => $asesor,
                'asesi' => $asesi,
                'data_asesmen' => $data_asesmen,
            ]);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['error' => 'Data Asesi tidak ditemukan'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Gagal mengambil data dari server'], 500);
        }
    }
    
    /**
     * ==========================================================
     * !!! METHOD API BARU: SIMPAN PERSETUJUAN (Update Timestamp) !!!
     * ==========================================================
     * Ini dipanggil saat Asesi klik "Selanjutnya/Setuju".
     */
    public function simpanPersetujuan($id_asesi)
    {
        Log::info("API: Menyimpan persetujuan (touch timestamp) untuk Asesi ID $id_asesi...");

        try {
            $asesi = Asesi::findOrFail($id_asesi);
            
            // 1. INI KUNCINYA: "Sentuh" model-nya
            // Ini cuma akan ng-update kolom 'updated_at' ke waktu sekarang
            $asesi->touch(); 

            // 2. Kirim balasan sukses
            return response()->json([
                'success' => true, 
                'message' => 'Persetujuan berhasil disimpan.'
            ]);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['error' => 'Data Asesi tidak ditemukan'], 404);
        } catch (\Exception $e) {
            Log::error('API: Gagal simpan persetujuan FR.AK.01 - ' . $e->getMessage());
            return response()->json(['error' => 'Gagal menyimpan persetujuan di server'], 500);
        }
    }
}
<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Jadwal;
use Illuminate\Http\Request;
// Kita juga butuh Model untuk relasi
use App\Models\Skema;
use App\Models\MasterTuk;
use App\Models\JenisTuk;

class JadwalController extends Controller
{
    public function index()
    {
        try {
            // ======================================================
            // VERSI AMAN: Kita hanya menggunakan Model dasar 'Jadwal'
            // tanpa memuat relasi (with) atau pengurutan (latest()).
            // Ini adalah query paling sederhana dan aman.
            // ======================================================
            $dataJadwal = Jadwal::get(); 

            // Jika query berhasil, kirim respon JSON
            return response()->json([
                'status' => 'success',
                'message' => 'Data jadwal berhasil diambil (Tanpa Relasi)',
                'data' => $dataJadwal
            ], 200);

        } catch (\Exception $e) {
            // Tangani error dan kirimkan pesannya ke browser (untuk debugging)
            // Error 500 ini akan mencatat pesan error PHP yang sebenarnya.
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal mengambil data. Error PHP: ' . $e->getMessage()
            ], 500);
        }
    }
}
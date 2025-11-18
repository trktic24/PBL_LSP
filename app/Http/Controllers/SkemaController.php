<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Skema; // <-- Pastikan ini ada

class SkemaController extends Controller
{
    /**
     * Menampilkan halaman detail untuk satu skema.
     * Method ini akan dipanggil oleh Route.
     */
    public function show($id)
    {
        // 1. Cari skema berdasarkan ID.
        // [PERUBAHAN UTAMA] Gunakan 'with()' untuk mengambil
        // data relasi 'unitKompetensi' dan 'detailSertifikasi'
        $skema = Skema::with('unitKompetensi', 'detailSertifikasi')->find($id);

        // 2. Jika skema dengan ID itu tidak ada, tampilkan halaman 404
        if (!$skema) {
            abort(404);
        }

        // 3. Kirim data skema (YANG KINI BERISI SEMUA RELASINYA) ke View
        return view('halaman_ambil_skema', ['skema' => $skema]);
    }
}
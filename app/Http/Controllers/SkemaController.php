<?php

namespace App\Http\Controllers;

use App\Models\Skema; // [PENTING] Import Model Skema
use Illuminate\Http\Request;

class SkemaController extends Controller
{
    /**
     * Menampilkan halaman detail untuk SATU skema.
     *
     * @param  int  $id_skema
     * @return \Illuminate\View\View
     */
    public function show($id_skema)
    {
        // 1. Ambil skema berdasarkan Primary Key 'id_skema'
        //    Kita juga langsung ambil relasinya (eager loading)
        try {
            $skema = Skema::with(['detailSertifikasi', 'unitKompetensi'])
                            ->findOrFail($id_skema);
                            
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            // Jika skema dengan ID itu tidak ada, lempar ke 404
            abort(404, 'Skema tidak ditemukan.');
        }


        // 2. Kirim variabel $skema yang sudah ditemukan
        //    ke view 'halaman_ambil_skema.blade.php'
        return view('halaman_ambil_skema', [
            'skema' => $skema
        ]);
    }

    // Anda bisa tambahkan fungsi 'index' untuk halaman list jika mau,
    // tapi untuk sekarang kita gunakan closure di web.php
}
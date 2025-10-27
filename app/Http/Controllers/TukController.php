<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// Asumsi Anda memiliki Model bernama 'Tuk' untuk berinteraksi dengan database
use App\Models\Tuk; 

class TukController extends Controller
{
    /**
     * Menampilkan daftar semua TUK.
     * Tugas: M -> C -> V (mengambil banyak data TUK)
     */
    public function showInfo()
    {
        // 1. Controller meminta SEMUA data TUK dari Model.
        //    (Ini adalah interaksi M-C)
        $tuks = Tuk::all(); 

        // 2. Controller memuat View 'info-tuk' dan mengirimkan data.
        //    (Ini adalah interaksi C-V)
        return view('page_tuk.info-tuk', [
            'tuks' => $tuks // Variabel $tuks akan tersedia di info-tuk.blade.php
        ]);
    }

    /**
     * Menampilkan detail spesifik dari satu TUK berdasarkan slug/ID.
     * Tugas: M -> C -> V (mengambil satu data TUK)
     *
     * @param string $slug Slug unik TUK dari URL.
     */
    public function showDetail($slug)
    {
        // 1. Controller meminta SATU data TUK dari Model berdasarkan slug.
        //    'firstOrFail()' akan melempar 404 jika data tidak ditemukan.
        $tuk = Tuk::where('slug', $slug)->firstOrFail(); 

        // 2. Controller memuat View 'detail-tuk' dan mengirimkan data tunggal.
        return view('page_tuk.detail-tuk', [
            'tuk' => $tuk // Variabel $tuk akan tersedia di detail-tuk.blade.php
        ]);
    }
}
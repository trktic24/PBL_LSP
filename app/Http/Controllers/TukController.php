<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TukController extends Controller
{
    /**
     * Menampilkan daftar Tempat Uji Kompetensi (TUK).
     * Ini adalah method yang akan dihubungkan ke rute /info-tuk
     * dan harusnya mengirimkan semua data TUK ke view.
     */
    public function index()
    {
        // --- SIMULASI DATA DAFTAR TUK ---
        // Normalnya: $tuks = Tuk::all(); 
        $tuks = [
            ['id' => 1, 'nama' => 'Politeknik Negeri Semarang', 'alamat' => 'Jl. Prof. Soedarto'],
            ['id' => 2, 'nama' => 'LSP Polines - Gedung ST', 'alamat' => 'Jl. Prof. Soedarto, Semarang'],
            ['id' => 3, 'nama' => 'Politeknik Negeri Semarang - Gedung Jokalet Satu', 'alamat' => 'Tembalang, Semarang'],
        ];
        // ---------------------------------
        
        return view('landing_page.page_tuk.info-tuk', [
            'tuks' => $tuks // Mengirim data daftar TUK
        ]);
    }


    /**
     * Menampilkan detail spesifik dari satu TUK berdasarkan ID.
     * Ini adalah method yang akan dihubungkan ke rute /detail-tuk/{id}
     *
     * @param  int  $id ID TUK yang dipilih dari daftar
     */
    public function showDetail($id)
    {
        // 1. Logika Pengambilan Data Berdasarkan ID
        // Normalnya: $data_tuk = Tuk::findOrFail($id);
        
        // --- SIMULASI PENGAMBILAN DATA TUK BERDASARKAN ID ---
        // Karena belum terhubung ke database, kita simulasikan data.
        $data_tuk = [
            'id' => $id,
            'nama' => "Detail TUK ID #{$id}", 
            'alamat' => "Alamat lengkap TUK dengan ID {$id}.",
            'kontak' => '082185585493',
            'detail_nama' => 'Politeknik Negeri Semarang' // Nama yang lebih detail
        ];
        // ----------------------------------------------------
        
        // 2. Meneruskan Data ke View
        return view('landing_page.page_tuk.detail-tuk', [
            'data_tuk' => $data_tuk,
        ]);
    }
}
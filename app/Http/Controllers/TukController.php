<?php

namespace App\Http\Controllers;

use App\Models\MasterTuk; // Ganti dari Tuk ke MasterTuk
use Illuminate\Http\Request;

class TukController extends Controller
{
    /**
     * Menampilkan daftar Tempat Uji Kompetensi (TUK).
     * 
     */
    public function index()
    {
        // Mengambil semua data TUK dari database menggunakan MasterTuk
        $tuks = MasterTuk::all(); 

        // Mengirimkan data TUK ke view
        return view('landing_page.page_tuk.info-tuk', [
            'tuks' => $tuks 
        ]);
    }


    /**
     * Menampilkan detail spesifik dari satu TUK berdasarkan ID.
     * 
     * @param int $id ID TUK (primary key)
     */
    public function showDetail($id)
    {
        // Mengambil data TUK berdasarkan ID, atau memunculkan error 404 jika tidak ditemukan
        $data_tuk = MasterTuk::findOrFail($id); 
        
        return view('landing_page.page_tuk.detail-tuk', [
            'data_tuk' => $data_tuk,
        ]);
    }
}
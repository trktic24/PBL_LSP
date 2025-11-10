<?php

namespace App\Http\Controllers;

// Ganti "use App\Models\Tuk;"
use App\Models\MasterTuk; // <-- PERBAIKAN: Menggunakan Model yang benar

use Illuminate\Http\Request;

class TukController extends Controller
{
    /**
     * Menampilkan daftar Tempat Uji Kompetensi (TUK).
     * Mengambil data dari tabel master_tuk menggunakan Model MasterTuk.
     * Untuk rute /info-tuk
     */
    public function index()
    {
        // PERBAIKAN: Menggunakan MasterTuk::all()
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
        // PERBAIKAN: Menggunakan MasterTuk::findOrFail()
        $data_tuk = MasterTuk::findOrFail($id);

        // Catatan Penting:
        // Kunci yang tersedia adalah kolom-kolom dari tabel: 'id_tuk', 'nama_lokasi', 'alamat_tuk', 'kontak_tuk', 'link_gmap', dll.

        return view('landing_page.page_tuk.detail-tuk', [
            'data_tuk' => $data_tuk,
        ]);
    }
}

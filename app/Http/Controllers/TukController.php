<?php

namespace App\Http\Controllers;

use App\Models\Tuk;
use Illuminate\Http\Request;

class TukController extends Controller
{
    /**
     * Menampilkan daftar Tempat Uji Kompetensi (TUK).
     * Mengambil data dari tabel master_tuk menggunakan Model Tuk.
     * Untuk rute /info-tuk
     */
    public function index()
    {
        // Mengambil semua data TUK dari database
        // Data akan tersedia dalam bentuk Collection/Array of objects (Model Tuk)
        $tuks = Tuk::all();

        // Mengirimkan data TUK ke view
        return view('landing_page.page_tuk.info-tuk', [
            'tuks' => $tuks 
        ]);
    }


    /**
     * Menampilkan detail spesifik dari satu TUK berdasarkan ID.
     * Mengambil data dari tabel master_tuk berdasarkan primary key 'id_tuk'.
     *
     * @param int $id ID TUK (primary key)
     */
    public function showDetail($id)
    {
        // Mengambil data TUK berdasarkan ID, atau memunculkan error 404 jika tidak ditemukan
        // Pastikan nama primary key di Model Tuk adalah 'id_tuk'
        $data_tuk = Tuk::findOrFail($id);
        
        // Catatan Penting: 
        // Struktur data yang dikirim ke view sekarang adalah objek Model Tuk ($data_tuk),
        // bukan lagi array asosiatif dengan kunci seperti 'nama_lengkap' atau 'alamat_detail'.
        // Kunci yang tersedia adalah kolom-kolom dari tabel: 'id_tuk', 'nama', 'alamat_tuk', 'kontak_tuk', 'link_gmap', dll.
        
        return view('landing_page.page_tuk.detail-tuk', [
            'data_tuk' => $data_tuk,
        ]);
    }
}
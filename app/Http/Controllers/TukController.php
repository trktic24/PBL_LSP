<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tuk; // Panggil Model Tuk
use App\Models\JenisTuk; // Panggil Model JenisTuk

class TukController extends Controller
{
    /**
     * Menampilkan halaman 'master_tuk' (Semua TUK).
     */
    public function index()
    {
        // Ambil semua data TUK beserta relasi ke jenis_tuk
        $tuks = Tuk::with('jenisTuk')->get();
        
        return view('tuk.master_tuk', [
            'tuks' => $tuks
        ]);
    }

    /**
     * Menampilkan halaman 'tuk_sewaktu' (hanya ID 1).
     */
    public function sewaktu()
    {
        $tuks = Tuk::where('id_jenis_tuk', 1)->get();
        return view('tuk.tuk_sewaktu', [
            'tuks' => $tuks
        ]);
    }

    /**
     * Menampilkan halaman 'tuk_tempatkerja' (hanya ID 2).
     */
    public function tempatKerja()
    {
        $tuks = Tuk::where('id_jenis_tuk', 2)->get();
        return view('tuk.tuk_tempatkerja', [
            'tuks' => $tuks
        ]);
    }

    /**
     * Menampilkan form 'add_tuk'.
     */
    public function create()
    {
        return view('tuk.add_tuk');
    }

    /**
     * Menampilkan form 'edit_tuk'.
     */
    public function edit($id_tuk) // Kita tambahkan ID di sini
    {
        $tuk = Tuk::findOrFail($id_tuk);
        return view('tuk.edit_tuk', [
            'tuk' => $tuk
        ]);
    }

    // Nanti Anda bisa tambahkan fungsi store() dan update() di sini
}
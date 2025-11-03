<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class HomeController extends Controller
{ 
    /**
     * Halaman utama (menampilkan semua skema)
     */
    public function index(): View
    {
        // Data dummy skema (untuk carousel di home.blade.php)
        $skemas = [
            ['nama' => 'Junior Web Developer', 'gambar' => 'skema1.jpg'],
            ['nama' => 'Network Administrator', 'gambar' => 'skema2.jpg'],
            ['nama' => 'Database Engineer', 'gambar' => 'skema3.jpg'],
            ['nama' => 'UI/UX Designer', 'gambar' => 'skema4.jpg'],
            ['nama' => 'Cyber Security', 'gambar' => 'skema5.jpg'],
            ['nama' => 'Mobile Developer', 'gambar' => 'skema6.jpg'],
            ['nama' => 'Data Analyst', 'gambar' => 'skema7.jpg'],
            ['nama' => 'Game Developer', 'gambar' => 'skema8.jpg'],
            ['nama' => 'IoT Specialist', 'gambar' => 'skema9.jpg'],
            ['nama' => 'Cloud Engineer', 'gambar' => 'skema10.jpg'],
            ['nama' => 'AI Engineer', 'gambar' => 'skema11.jpg'],
            ['nama' => 'Fullstack Developer', 'gambar' => 'skema12.jpg'],
        ];
        return view('landing_page.home')->with($skemas);;
    }


    /**
     * Halaman detail skema
     */
    public function show($id): View
    {
        // Data dummy detail skema
        $skema = [
            'id' => $id,
            'nama' => "Skema Sertifikasi {$id}",
            'deskripsi' => "Deskripsi lengkap untuk skema sertifikasi nomor {$id}.",
            'gambar' => "skema{$id}.jpg",
        ];

        return view('landing_page.detail.detail_skema', compact('skema'));
    }

    /**
     * Halaman jadwal
     */
    public function jadwal(): View
    {
        return view('landing_page.frontend.jadwal');
    }

    /**
     * Halaman laporan
     */
    public function laporan(): View
    {
        return view('landing_page.frontend.laporan');
    }

    /**
     * Halaman profil
     */
    public function profil(): View
    {
        $x ="e";
        return view('landing_page.frontend.profil', compact(x));
    }
}

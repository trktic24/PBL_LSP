<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\Jadwal;

class HomeController extends Controller
{
    public function index(): View
{

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

    return view('landing_page.home', compact('skemas'));
}


    // public function home(Request $request): View
    // {
    //     $jadwals = Jadwal::where('tanggal', '>=', now())
    //                      ->orderBy('tanggal', 'asc')
    //                      ->take(2)
    //                      ->get();

    //     return view('frontend.home', ['jadwals' => $jadwals]);
    // }

    public function show($id): View
    {
        $skema = [
            'id' => $id,
            'nama' => "Skema Sertifikasi {$id}",
            'deskripsi' => "Deskripsi lengkap untuk skema sertifikasi nomor {$id}.",
            'gambar' => "skema{$id}.jpg",
        ];

        return view('landing_page.detail.detail_skema', compact('skema'));
    }

    public function jadwal(): View
    {
        return view('landing_page.frontend.jadwal');
    }

    public function laporan(): View
    {
        return view('landing_page.frontend.laporan');
    }

    public function showJadwalDetail($id): View
    {
        $jadwal = Jadwal::findOrFail($id);

        return view('landing_page.detail.detail_jadwal', [
            'jadwal' => $jadwal
        ]);
    }
}

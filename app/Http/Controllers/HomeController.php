<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\Jadwal; // <-- TAMBAHAN 1: Impor Model Jadwal

class HomeController extends Controller

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
        return view('landing_page.home')->with($skemas);
{
    /**
     * DIEDIT: Method home() sekarang mengambil data
     * dan mengirimkannya ke view.
     */
    public function home(Request $request): View
    {
        // TAMBAHAN 2: Logika untuk mengambil data Jadwal
        // (Ini akan mengambil data dummy dari seeder Anda)
        $jadwals = Jadwal::where('tanggal', '>=', now())
                         ->orderBy('tanggal', 'asc')
                         ->take(2)
                         ->get();

        return view('frontend/home', [
            'jadwals' => $jadwals // Kirim data ke view
        ]);
<<<<<<< HEAD

=======
>>>>>>> 3ef7adc3e335d9d6c4534613859955b9a89479bc
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
     * TAMBAHAN 3: Method baru untuk menangani halaman detail
     * (Dipanggil oleh route 'jadwal.detail' yang baru)
     */
    public function showJadwalDetail($id)
    {
        // 1. Cari jadwal berdasarkan ID yang diklik
        $jadwal = Jadwal::findOrFail($id); 

        // 2. Tampilkan view detail_jadwal dan kirim datanya
        // BENAR (langsung dari views -> landing_page -> detail)
        return view('landing_page.detail.detail_jadwal', [
            'jadwal' => $jadwal
]);
    }
<<<<<<< HEAD
}
=======
}
>>>>>>> 3ef7adc3e335d9d6c4534613859955b9a89479bc

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\Jadwal; // <-- TAMBAHAN 1: Impor Model Jadwal

class HomeController extends Controller
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
    }

    public function jadwal(Request $request): View
    {
        return view('frontend/jadwal');
    }

    public function laporan(Request $request): View
    {
        return view('frontend/laporan');
    }

    public function profil(Request $request): View
    {
        return view('frontend/profil');
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
}
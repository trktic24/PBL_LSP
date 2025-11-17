<?php

namespace App\Http\Controllers\Asesor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // <-- Ditambahkan
use App\Models\User; // <-- Ditambahkan
use App\Models\Asesor; // <-- Ditambahkan
use App\Models\Jadwal; // <-- Ditambahkan
use App\Models\Skema; // <-- Ditambahkan

class DashboardController extends Controller
{
    /**
     * Tampilkan halaman dashboard Home Asesor.
     * (Logika ini dipindahkan dari HomeController)
     */
    public function index(Request $request) // <-- Menggunakan $request
    {
        // Mendapatkan user yang sedang login
        // Ganti ID hard-code dengan ID user yang sedang login
        $user = $request->user();

        // Pastikan user memiliki relasi asesor
        if (!$user->asesor) {
             Auth::logout();
             return redirect('/login')->with('error', 'Data Asesor tidak ditemukan.');
        }

        $id_asesor = $user->asesor->id_asesor;

        // Ambil data asesor berdasarkan ID user yang login
        $asesor = Asesor::with('user', 'skema')->find($id_asesor);

        // ----------------------------------------------------
        // 1. Data Profil
        // ----------------------------------------------------
        $profile = [
            'nama' => $asesor->nama_lengkap ?? 'Nama Asesor',
            'nomor_registrasi' => $asesor->nomor_regis ?? 'Belum ada NOREG',
            'kompetensi' => 'Pemrograman', // Ganti dengan logika skema jika perlu
            'foto_url' => $asesor->pas_foto ?? 'https://placehold.co/60x60/8B5CF6/ffffff?text=AF',
        ];

        // ----------------------------------------------------
        // 2. Data Ringkasan (Dummy)
        // ----------------------------------------------------
        $summary = [
            'belum_direview' => 5,
            'dalam_proses' => 7,
            'telah_direview' => 4,
            'jumlah_asesi' => 18,
        ];

        // ----------------------------------------------------
        // 3. Data Jadwal Asesmen
        // ----------------------------------------------------
        $jadwal = Jadwal::where('id_asesor', $id_asesor) // Menggunakan ID asesor yang login
                            ->with('skema', 'tuk')
                            ->orderBy('tanggal_mulai', 'asc')
                            ->limit(5)
                            ->get();

        if ($jadwal->isEmpty()) {
            $jadwal = []; // Kirim array kosong jika tidak ada jadwal
        } else {
            // Jika data ada, format ulang agar sesuai dengan view
            $jadwal = $jadwal->map(function ($item) {
                return (object) [
                    'id_jadwal' => $item->id_jadwal, // <-- Tambahkan ID untuk link detail
                    'skema_nama' => $item->skema->nama_skema ?? 'Skema Tidak Ditemukan',
                    'tanggal' => $item->tanggal_mulai ? date('d F Y', strtotime($item->tanggal_mulai)) : 'N/A',
                    'waktu_mulai' => $item->waktu_mulai,
                    'tanggal_pelaksanaan' => $item->tanggal_pelaksanaan ? date('d F Y', strtotime($item->tanggal_pelaksanaan)) : 'N/A',
                ];
            });
        }

        // Kirim ke view frontend.home (dashboard asesor)
        return view('frontend.home', [
            'profile' => $profile,
            'summary' => $summary,
            'jadwals' => $jadwal  // <-- Di sini kuncinya
        ]);
    }
}
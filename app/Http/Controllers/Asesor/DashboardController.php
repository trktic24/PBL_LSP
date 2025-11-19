<?php

namespace App\Http\Controllers\Asesor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Asesor;
use App\Models\Jadwal;
use App\Models\Skema;

class DashboardController extends Controller
{
    /**
     * Tampilkan halaman dashboard Home Asesor.
     */
    public function index(Request $request)
    {
        // Mendapatkan user yang sedang login
        $user = $request->user();

        // ----------------------------------------------------
        // 🛡️ TAHAP 1: CEK APAKAH DATA ASESOR ADA?
        // ----------------------------------------------------
        if (!$user->asesor) {
             Auth::logout();
             return redirect('/login')->with('error', 'Data profil Asesor tidak ditemukan.');
        }

        // ----------------------------------------------------
        // 🛡️ TAHAP 2: CEK STATUS VERIFIKASI (SATPAM TAMBAHAN)
        // ----------------------------------------------------
        // Jika is_verified = 0 (False/Pending), tendang keluar.
        if ($user->asesor->is_verified == 0) {

            Auth::logout(); // Logout paksa
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')
                ->with('error', 'Akun Anda belum diverifikasi oleh Admin. Mohon tunggu persetujuan.');
        }

        // ====================================================
        // JIKA LOLOS, LANJUT KE LOGIKA DASHBOARD
        // ====================================================

        $id_asesor = $user->asesor->id_asesor;

        // Ambil data asesor berdasarkan ID user yang login
        $asesor = Asesor::with('user', 'skema')->find($id_asesor);

        // 1. Data Profil
        $profile = [
            'nama' => $asesor->nama_lengkap ?? 'Nama Asesor',
            'nomor_registrasi' => $asesor->nomor_regis ?? 'Belum ada NOREG',
            'kompetensi' => 'Pemrograman',
            // Menggunakan Accessor getUrlFotoAttribute yang sudah kita buat di Model (opsional)
            // atau logika manual jika belum pakai accessor
            'foto_url' => $asesor->url_foto ?? 'https://placehold.co/60x60/8B5CF6/ffffff?text=AF',
        ];

        // 2. Data Ringkasan (Dummy)
        $summary = [
            'belum_direview' => 5,
            'dalam_proses' => 7,
            'telah_direview' => 4,
            'jumlah_asesi' => 18,
        ];

        // 3. Data Jadwal Asesmen
        $jadwal = Jadwal::where('id_asesor', $id_asesor)
                            ->with('skema', 'tuk')
                            ->orderBy('tanggal_mulai', 'asc')
                            ->limit(5)
                            ->get();

        if ($jadwal->isEmpty()) {
            $jadwal = [];
        } else {
            // Format ulang agar sesuai view
            $jadwal = $jadwal->map(function ($item) {
                return (object) [
                    'id_jadwal' => $item->id_jadwal,
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
            'jadwals' => $jadwal
        ]);
    }
}
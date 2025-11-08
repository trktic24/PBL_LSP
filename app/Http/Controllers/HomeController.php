<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Asesor;
use App\Models\Jadwal;
use App\Models\Skema;

class HomeController extends Controller
{
    /**
     * Tampilkan halaman dashboard Home.
     */
    public function index()
    {
        // Mendapatkan user yang sedang login
        $user = Auth::user(); 

        // Ambil data asesor berdasarkan user yang login
        $asesor = $user->asesor()->with('skema')->first(); 

        // Jika user yang login tidak punya profil asesor, berikan error
        if (!$asesor) {
            abort(403, 'Profil Asesor tidak ditemukan untuk user ini.');
        }

        // ----------------------------------------------------
        // 1. Data Profil (Menggunakan data asesor asli)
        // ----------------------------------------------------
        $profile = [
            'nama' => $asesor->nama_lengkap, 
            'nomor_registrasi' => $asesor->nomor_regis, 
            'kompetensi' => $asesor->skema ? $asesor->skema->nama_skema : $asesor->pekerjaan, 
            'foto_url' => $asesor->pas_foto ? asset('storage/' . $asesor->pas_foto) : asset('images/profil_asesor.jpeg'),
        ];

        // ----------------------------------------------------
        // 2. Data Ringkasan (Masih dummy, sesuai file Anda)
        // ----------------------------------------------------
        $summary = [
            'belum_direview' => 5,
            'dalam_proses' => 7,
            'telah_direview' => 4,
            'jumlah_asesi' => 18,
        ];

        // ----------------------------------------------------
        // 3. Data Jadwal Asesmen (FIXED)
        // ----------------------------------------------------
        
        // Asumsi: Kita hanya menampilkan jadwal yang terkait dengan Asesor yang login
        $jadwal = Jadwal::where('id_asesor', $asesor->id_asesor) // <-- SUDAH DIGANTI
                            ->with('skema', 'tuk') 
                            ->orderBy('tanggal_mulai', 'asc')
                            ->limit(5)
                            ->get();

        // Data dummy jika database belum terisi atau testing
        if ($jadwal->isEmpty()) {
            $jadwal = [
                (object)['id_jadwal' => null, 'skema_nama' => 'Junior Web Dev', 'tanggal' => '29 September 2025'],
                (object)['id_jadwal' => null, 'skema_nama' => 'Data Science', 'tanggal' => '24 November 2025'],
                (object)['id_jadwal' => null, 'skema_nama' => 'Programming', 'tanggal' => '30 November 2025'],
                (object)['id_jadwal' => null, 'skema_nama' => 'Game Dev', 'tanggal' => '4 Januari 2026'],
                (object)['id_jadwal' => null, 'skema_nama' => 'Cyber Security', 'tanggal' => '10 Januari 2026'],
            ];
        } else {
            // Jika data ada, format ulang agar sesuai dengan view
            $jadwal = $jadwal->map(function ($item) {
                return (object) [
                    'id_jadwal' => $item->id_jadwal, // <-- Tambahkan ID untuk link
                    'skema_nama' => $item->skema->nama_skema ?? 'Skema Tidak Ditemukan',
                    'tanggal' => $item->tanggal_mulai ? date('d F Y', strtotime($item->tanggal_mulai)) : 'N/A',
                ];
            });
        }

        // Return ke view yang benar
        return view('frontend.home', compact('profile', 'summary', 'jadwal'));
    }
}
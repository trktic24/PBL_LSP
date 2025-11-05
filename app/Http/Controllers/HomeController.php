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
        // Mendapatkan user yang sedang login (Asumsi: User adalah Asesor)
        $id_asesor_tes =1;

        // Ambil data asesor berdasarkan ID hard-code
        $asesor = Asesor::with('user', 'skema')->find($id_asesor_tes);

        // ----------------------------------------------------
        // 1. Data Profil (Menggunakan data dummy jika User tidak lengkap)
        // ----------------------------------------------------
        $profile = [
            // Gunakan nama user yang login
            'nama' => $asesor && $asesor->user ? $asesor->user->name : 'Ajeng Febria Hidayati', 
            // Ambil nomor registrasi dari model User atau Asesor (di sini menggunakan dummy)
            'nomor_registrasi' => '90973646526352', 
            'kompetensi' => 'Pemrograman', // Ambil kompetensi dari relasi Skema/Asesor
            'foto_url' => 'https://placehold.co/60x60/8B5CF6/ffffff?text=AF',
        ];

        // ----------------------------------------------------
        // 2. Data Ringkasan (Menggunakan data dummy karena tabel belum dibuat)
        // ----------------------------------------------------
        // Dalam implementasi nyata, ini akan dihitung dari tabel 'asesmen'
        $summary = [
            'belum_direview' => 5,
            'dalam_proses' => 7,
            'telah_direview' => 4,
            'jumlah_asesi' => 18,
        ];

        // ----------------------------------------------------
        // 3. Data Jadwal Asesmen
        // ----------------------------------------------------
        
        // Asumsi: Kita hanya menampilkan jadwal yang terkait dengan Asesor yang login (id_asesor = user id)
        // Note: Dalam kasus nyata, Anda mungkin perlu relasi User -> Asesor -> Jadwal
        $jadwal = Jadwal::where('id_asesor', $id_asesor_tes) // Jika tidak ada user, gunakan id 1
                        ->with('skema', 'tuk') // Load relasi Skema dan TUK
                        ->orderBy('tanggal_mulai', 'asc')
                        ->limit(5)
                        ->get();

        // Data dummy jika database belum terisi atau testing
        if ($jadwal->isEmpty()) {
            $jadwal = [
                (object)['skema_nama' => 'Junior Web Dev', 'tanggal' => '29 September 2025'],
                (object)['skema_nama' => 'Data Science', 'tanggal' => '24 November 2025'],
                (object)['skema_nama' => 'Programming', 'tanggal' => '30 November 2025'],
                (object)['skema_nama' => 'Game Dev', 'tanggal' => '4 Januari 2026'],
                (object)['skema_nama' => 'Cyber Security', 'tanggal' => '10 Januari 2026'],
            ];
        } else {
            // Jika data ada, format ulang agar sesuai dengan view
            $jadwal = $jadwal->map(function ($item) {
                return (object) [
                    'skema_nama' => $item->skema->nama_skema ?? 'Skema Tidak Ditemukan',
                    'tanggal' => $item->tanggal_mulai ? date('d F Y', strtotime($item->tanggal_mulai)) : 'N/A',
                ];
            });
        }


        return view('home', compact('profile', 'summary', 'jadwal'));
    }
}
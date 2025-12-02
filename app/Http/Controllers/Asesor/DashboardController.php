<?php

namespace App\Http\Controllers\Asesor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Asesor;
use App\Models\Jadwal;
use App\Models\Skema;
use Carbon\Carbon;

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
        // Jika status_verifikasi != 'approved', tendang keluar.
        if ($user->asesor->status_verifikasi !== 'approved') {

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
                            ->orderBy('tanggal_pelaksanaan', 'asc');

        // A. Filter Pencarian (Search Input)
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $jadwal->where(function ($q) use ($searchTerm) {
                $q->where('Status_jadwal', 'like', '%' . $searchTerm . '%')
                  ->orWhere('waktu_mulai', 'like', '%' . $searchTerm . '%')  
                  ->orWhere('tanggal_pelaksanaan', 'like', '%' . $searchTerm . '%')              
                  ->orWhereHas('skema', function ($qSkema) use ($searchTerm) {
                      $qSkema->where('nama_skema', 'like', '%' . $searchTerm . '%');
                  });
            });
        }
        
        // B. Filter Nama Skema (dari Checkbox)
        if ($request->has('namaskema') && is_array($request->namaskema)) {
            $jadwal->whereHas('skema', function ($q) use ($request) {
                $q->whereIn('nama_skema', $request->namaskema);
            });
        }

        // Waktu
        if ($request->filled('waktu')) {
            $jadwal->whereTime('waktu_mulai', '>=', $request->waktu);
        }        

        // C. Filter Tanggal (dari Input Date tunggal)
        // Saya asumsikan Anda ingin mencari jadwal TEPAT PADA tanggal tersebut.
        if ($request->filled('tanggal')) {
            // Pastikan Anda membandingkan tanggal dengan tepat (misalnya, pada hari itu)
             $jadwal->whereDate('tanggal_pelaksanaan', $request->tanggal);
        }
        
        // D. Filter Status (dari Checkbox)
        if ($request->has('status') && is_array($request->status)) {
            $jadwal->whereIn('Status_jadwal', $request->status);
        }
        
        // 4. Eksekusi Query Jadwal (Hanya 5 data pertama untuk Dashboard)
        $jadwals = $jadwal->latest()->paginate(5);

        /*$jadwals->getCollection()->transform(function ($item) {

            return (object) [
                'id_jadwal' => $item->id_jadwal,
                'skema_nama' => $item->skema->nama_skema ?? 'Skema Tidak Ditemukan',
                'waktu_mulai' => $item->waktu_mulai,
                'Status_jadwal' => $item->Status_jadwal,
                'tanggal' => $item->tanggal_pelaksanaan ?? 'N/A',
            ];
        });*/

        $skemaIdsInJadwal = Jadwal::where('id_asesor', $id_asesor)
                                  ->select('id_skema')->distinct()->pluck('id_skema');
        $listSkema = Skema::whereIn('id_skema', $skemaIdsInJadwal)
                          ->pluck('nama_skema')->filter()->sort()->values();
        
        // LIST STATUS
        $listStatus = Jadwal::where('id_asesor', $id_asesor)
                            ->select('Status_jadwal')->distinct()->pluck('Status_jadwal')
                            ->filter()->sort()->values();        

        // Kirim ke view frontend.home (dashboard asesor)
        return view('frontend.home', [
            'profile' => $profile,
            'summary' => $summary,
            'jadwals' => $jadwals,
            'listSkema' => $listSkema, 
            'listStatus' => $listStatus,            
        ]);
    }                        
    
}
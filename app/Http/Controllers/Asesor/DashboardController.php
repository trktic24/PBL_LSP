<?php

namespace App\Http\Controllers\Asesor;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Asesor;
use App\Models\Jadwal;
use App\Models\Skema;
use App\Models\DataSertifikasiAsesi;
use App\Models\ResponApl2a01;
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
        $asesor = Asesor::with('user', 'skemas')->find($id_asesor);

        // 1. Data Profil
        $profile = [
            'nama' => $asesor->nama_lengkap ?? 'Nama Asesor',
            'nomor_registrasi' => $asesor->nomor_regis ?? 'Belum ada NOREG',
            'kompetensi' => 'Pemrograman',
            // Menggunakan Accessor getUrlFotoAttribute yang sudah kita buat di Model (opsional)
            // atau logika manual jika belum pakai accessor
            'foto_url' => $asesor->url_foto ?? 'https://placehold.co/60x60/8B5CF6/ffffff?text=AF',
        ];

        // Ringkasan
        $blmreview = DataSertifikasiAsesi::whereHas('jadwal', function ($q) use ($id_asesor) {
                $q->where('id_asesor', $id_asesor);
            })
            ->whereNull('rekomendasi_apl02')
            ->whereNull('rekomendasi_hasil_asesmen_AK02')
            ->count();
        $dlmproses = DataSertifikasiAsesi::whereHas('jadwal', function ($q) use ($id_asesor) {$q->where('id_asesor', $id_asesor);})
                ->whereNotNull('rekomendasi_apl02')        // sudah mengisi APL02
                ->whereNull('rekomendasi_hasil_asesmen_AK02')
                ->count();        
        $sdhreview = DataSertifikasiAsesi::whereHas('jadwal', function ($q) use ($id_asesor) {$q->where('id_asesor', $id_asesor);})
                ->whereNotNull('rekomendasi_hasil_asesmen_AK02')
                ->count();
        $totalAsesi = DataSertifikasiAsesi::whereHas('jadwal', function ($q) use ($id_asesor) {$q->where('id_asesor', $id_asesor);})
        ->count();
        
        // // 2. Data Ringkasan (Dummy)
        // $summary = [
        //     'belum_direview' => 5,
        //     'dalam_proses' => 7,
        //     'telah_direview' => 4,
        //     'jumlah_asesi' => 18,
        // ];

        // 3. Data Jadwal Asesmen
        $jadwal = Jadwal::where('id_asesor', $id_asesor)
            ->with('skema', 'mastertuk', 'jenisTuk')
            ->orderBy('tanggal_pelaksanaan', 'asc');

        // A. Filter Pencarian (Search Input)

        if ($request->filled('search')) {
            $search = strtolower(trim($request->search));

            $months = [
                'january' => 1, 'february' => 2, 'march' => 3,
                'april' => 4, 'may' => 5, 'june' => 6,
                'july' => 7, 'august' => 8, 'september' => 9,
                'october' => 10, 'november' => 11, 'december' => 12,
            ];

            $jadwal->where(function ($q) use ($search, $months) {

                /* ======================
                * TEXT SEARCH
                * ====================== */
                $q->where('Status_jadwal', 'like', "%{$search}%")
                ->orWhere('sesi', 'like', "%{$search}%")
                ->orWhere(DB::raw("TIME_FORMAT(waktu_mulai, '%H:%i')"), 'like', "%{$search}%");

                /* ======================
                * FULL DATE (16 december 2025)
                * ====================== */
                try {
                    $date = Carbon::parse($search);
                    $q->orWhereDate('tanggal_pelaksanaan', $date->format('Y-m-d'));
                } catch (\Exception $e) {}

                /* ======================
                * MONTH + YEAR (december 2025)
                * ====================== */
                if (preg_match('/(january|february|march|april|may|june|july|august|september|october|november|december)\s+(\d{4})/', $search, $m)) {
                    $q->orWhereMonth('tanggal_pelaksanaan', $months[$m[1]])
                    ->orWhereYear('tanggal_pelaksanaan', $m[2]);
                }

                /* ======================
                * MONTH ONLY (december)
                * ====================== */
                if (isset($months[$search])) {
                    $q->orWhereMonth('tanggal_pelaksanaan', $months[$search]);
                }

                /* ======================
                * YEAR ONLY (2025)
                * ====================== */
                if (preg_match('/^\d{4}$/', $search)) {
                    $q->orWhereYear('tanggal_pelaksanaan', $search);
                }

                // RELASI
                $q->orWhereHas('skema', fn ($q) =>
                    $q->where('nama_skema', 'like', "%{$search}%")
                )
                ->orWhereHas('masterTuk', fn ($q) =>
                    $q->where('nama_lokasi', 'like', "%{$search}%")
                )
                ->orWhereHas('jenisTuk', fn ($q) =>
                    $q->where('jenis_tuk', 'like', "%{$search}%")
                );
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

        // 5. Data Notifikasi (Dari Database)
        // Mengambil notifikasi milik user yang sedang login
        $notifications = $user->notifications()->latest()->take(4)->get()->map(function ($n) {
            return [
                'title' => $n->data['title'] ?? 'Notifikasi',
                'message' => $n->data['body'] ?? 'Pesan tidak tersedia',
                'time' => $n->created_at->diffForHumans(),
                'is_read' => !is_null($n->read_at),
                'link' => $n->data['link'] ?? '#'
            ];
        });

        // Kirim ke view frontend.home (dashboard asesor)
        return view('asesor.home', [
            'profile' => $profile,
            // 'summary' => $summary,
            'jadwals' => $jadwals,
            'listSkema' => $listSkema,
            'listStatus' => $listStatus,
            'notifications' => $notifications,
            'totalAsesi' => $totalAsesi,
            'blmreview' => $blmreview,
            'dlmproses' => $dlmproses,
            'sdhreview' => $sdhreview,
        ]);
    }

    /**
     * Tampilkan halaman semua notifikasi dengan pagination.
     */
    public function semuaNotifikasi(Request $request)
    {
        $user = $request->user();

        // Ambil semua notifikasi dengan pagination (10 per halaman)
        $notifications = $user->notifications()->latest()->paginate(10);

        return view('asesor.notifications_asesor', compact('notifications'));
    }
}

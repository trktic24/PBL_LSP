<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // <-- Wajib untuk Query Chart
use Carbon\Carbon;                 // <-- Wajib untuk manipulasi tanggal
use App\Models\Jadwal;
use App\Models\Asesi;
use App\Models\Asesor;

class DashboardController extends Controller
{
    /**
     * Menampilkan Halaman Dashboard Utama.
     */
    public function index(Request $request)
    {
        // ==========================================
        // BAGIAN 1: STATISTIK KARTU (CARD STATS)
        // ==========================================
        
        $asesmenBerlangsung = Jadwal::where('Status_jadwal', 'Terjadwal')->count();
        $asesmenSelesai     = Jadwal::where('Status_jadwal', 'Selesai')->count();
        $jumlahAsesi        = Asesi::count();
        $jumlahAsesor       = Asesor::count();
        
        // Tambahan: Statistik Asesi Baru Hari Ini & Tren (Untuk Kartu di UI)
        $asesiBaruHariIni = Asesi::whereDate('created_at', Carbon::today())->count();
        $asesiKemarin     = Asesi::whereDate('created_at', Carbon::yesterday())->count();
        // Menghitung persentase kenaikan/penurunan (hindari bagi 0)
        $trenPendaftaran  = $asesiKemarin > 0 ? round((($asesiBaruHariIni - $asesiKemarin) / $asesiKemarin) * 100) : 0; 
        
        // Data Dummy Persentase Kelulusan (Karena belum ada tabel hasil asesmen)
        $persentaseKelulusan = 85; 
        // Hitung asesor yang sudah diverifikasi/aktif
        $asesorAktif = Asesor::where('status_verifikasi', 'approved')->count();


        // ==========================================
        // BAGIAN 2: DATA UNTUK GRAFIK (CHARTS)
        // ==========================================

        // A. CHART STATISTIK SKEMA (Line Chart)
        // Menghitung jadwal per bulan di tahun ini
        $skemaPerBulan = Jadwal::select(
                DB::raw('COUNT(id_jadwal) as count'), 
                DB::raw('MONTH(tanggal_pelaksanaan) as month')
            )
            ->whereYear('tanggal_pelaksanaan', date('Y'))
            ->groupBy('month')
            ->pluck('count', 'month')
            ->toArray();

        // B. CHART STATISTIK ASESI (Bar Chart)
        // Menghitung pendaftaran asesi per bulan di tahun ini
        $asesiPerBulan = Asesi::select(
                DB::raw('COUNT(id_asesi) as count'), 
                DB::raw('MONTH(created_at) as month')
            )
            ->whereYear('created_at', date('Y'))
            ->groupBy('month')
            ->pluck('count', 'month')
            ->toArray();

        // C. Normalisasi Data Chart (Isi bulan kosong dengan 0)
        $dataSkemaChart = [];
        $dataAsesiChart = [];
        
        for ($i = 1; $i <= 12; $i++) {
            $dataSkemaChart[] = $skemaPerBulan[$i] ?? 0;
            $dataAsesiChart[] = $asesiPerBulan[$i] ?? 0;
        }

        // D. CHART PROGRESS (Doughnut Chart)
        // Menggunakan data real dari status jadwal
        $jadwalDibatalkan = Jadwal::where('Status_jadwal', 'Dibatalkan')->count();
        
        // Urutan: [Terjadwal, Selesai, Dibatalkan]
        $dataProgress = [
            $asesmenBerlangsung, 
            $asesmenSelesai,     
            $jadwalDibatalkan
        ];


        // ==========================================
        // BAGIAN 3: LOGIKA TABEL JADWAL (FILTERING)
        // ==========================================
        
        // Setup Sorting Defaults
        $sortColumn = $request->input('sort', 'tanggal_pelaksanaan');
        $sortDirection = $request->input('direction', 'asc');
        
        // Daftar kolom valid untuk sorting
        $allowedColumns = [
            'id_jadwal', 'skema_nama', 'tuk_nama', 'jenis_tuk', 
            'sesi', 'kuota_maksimal', 'tanggal_pelaksanaan'
        ];
        
        if (!in_array($sortColumn, $allowedColumns)) $sortColumn = 'tanggal_pelaksanaan';
        if (!in_array($sortDirection, ['asc', 'desc'])) $sortDirection = 'asc';

        // Base Query dengan Eager Loading
        $query = Jadwal::with(['skema', 'masterTuk', 'jenisTuk', 'asesor']); // Pastikan relasi di Model Jadwal bernama 'tuk', bukan 'masterTuk' jika error, sesuaikan.

        // Logic Filter Status
        $filterStatus = $request->input('filter_status');
        
        if ($filterStatus && in_array($filterStatus, ['Terjadwal', 'Selesai', 'Dibatalkan'])) {
            $query->where('Status_jadwal', $filterStatus);
        } else {
            // Default Filter: Hanya tampilkan yang Terjadwal (Aktif)
            $query->where('Status_jadwal', 'Terjadwal');
        }
        
        // Logic Filter Jenis TUK
        $filterJenisTuk = $request->input('filter_jenis_tuk');
        if ($filterJenisTuk && is_numeric($filterJenisTuk)) {
            $query->where('id_jenis_tuk', $filterJenisTuk);
        }

        // Logic Search (Multi-column search)
        if ($request->has('search') && $request->input('search') != '') {
            $searchTerm = $request->input('search');
            $query->where(function($q) use ($searchTerm) {
                $q->where('id_jadwal', 'like', '%' . $searchTerm . '%')
                  ->orWhere('sesi', 'like', '%' . $searchTerm . '%')
                  ->orWhereHas('skema', function($sq) use ($searchTerm) {
                      $sq->where('nama_skema', 'like', '%' . $searchTerm . '%')
                          ->orWhere('nomor_skema', 'like', '%' . $searchTerm . '%');
                  })
                  // Pastikan nama relasi di model Jadwal sesuai ('tuk' atau 'masterTuk')
                  ->orWhereHas('masterTuk', function($tq) use ($searchTerm) { 
                      $tq->where('nama_lokasi', 'like', '%' . $searchTerm . '%');
                  })
                  ->orWhereHas('asesor', function($aq) use ($searchTerm) {
                      $aq->where('nama_lengkap', 'like', '%' . $searchTerm . '%');
                  });
            });
        }

        // Logic Sorting dengan JOIN (Agar bisa sort berdasarkan nama relasi)
        $query->select('jadwal.*'); 
        
        if ($sortColumn == 'skema_nama') {
            $query->join('skema', 'jadwal.id_skema', '=', 'skema.id_skema')
                  ->orderBy('skema.nama_skema', $sortDirection);
        } elseif ($sortColumn == 'tuk_nama') {
            // Sesuaikan nama tabel: 'master_tuk' atau 'tuk' (cek migrasi kamu)
            $query->join('master_tuk', 'jadwal.id_tuk', '=', 'master_tuk.id_tuk')
                  ->orderBy('master_tuk.nama_lokasi', $sortDirection);
        } elseif ($sortColumn == 'jenis_tuk') {
            $query->join('jenis_tuk', 'jadwal.id_jenis_tuk', '=', 'jenis_tuk.id_jenis_tuk')
                  ->orderBy('jenis_tuk.jenis_tuk', $sortDirection);
        } elseif ($sortColumn == 'asesor_nama') {
             $query->join('asesor', 'jadwal.id_asesor', '=', 'asesor.id_asesor')
                  ->orderBy('asesor.nama_lengkap', $sortDirection);
        } else {
            $query->orderBy($sortColumn, $sortDirection); 
        }

        // Pagination
        $allowedPerpage = [5, 10, 25, 50]; 
        $perPage = $request->input('per_page', 5);
        if (!in_array($perPage, $allowedPerpage)) {
            $perPage = 5;
        }

        $jadwalTerbaru = $query->paginate($perPage)->onEachSide(0.5);
        
        // Append query string agar filter tidak hilang saat ganti halaman
        $jadwalTerbaru->appends($request->only([
            'sort', 'direction', 'search', 'per_page', 'filter_status', 'filter_jenis_tuk'
        ]));

        // Kirim data ke View
        return view('Admin.dashboard.dashboard_admin', [
            // Data Stats
            'asesmenBerlangsung' => $asesmenBerlangsung,
            'asesmenSelesai'     => $asesmenSelesai,
            'jumlahAsesi'        => $jumlahAsesi,
            'jumlahAsesor'       => $jumlahAsesor,
            'asesiBaruHariIni'   => $asesiBaruHariIni,
            'trenPendaftaran'    => $trenPendaftaran,
            'persentaseKelulusan'=> $persentaseKelulusan,
            'asesorAktif'        => $asesorAktif,

            // Data Tabel
            'jadwalTerbaru'      => $jadwalTerbaru,
            'perPage'            => $perPage,
            'sortColumn'         => $sortColumn,
            'sortDirection'      => $sortDirection,
            'filterStatus'       => $filterStatus,
            'filterJenisTuk'     => $filterJenisTuk,

            // Data Chart (PENTING)
            'dataSkemaChart'     => $dataSkemaChart,
            'dataAsesiChart'     => $dataAsesiChart,
            'dataProgress'       => $dataProgress,
        ]);
    }
}
<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
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
        // 1. Hitung Statistik (Tetap Sama)
        $asesmenBerlangsung = Jadwal::where('Status_jadwal', 'Terjadwal')->count();
        $asesmenSelesai     = Jadwal::where('Status_jadwal', 'Selesai')->count();
        $jumlahAsesi        = Asesi::count();
        $jumlahAsesor       = Asesor::count();
        
        // 2. Logika Tabel Jadwal Terdekat (Sorting & Pagination)
        
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
        $query = Jadwal::with(['skema', 'masterTuk', 'jenisTuk', 'asesor']);

        // [PERBAIKAN UTAMA DI SINI]
        // Logic Filter Status
        $filterStatus = $request->input('filter_status');
        
        if ($filterStatus && in_array($filterStatus, ['Terjadwal', 'Selesai', 'Dibatalkan'])) {
            // Jika user memilih filter status spesifik, gunakan itu
            $query->where('Status_jadwal', $filterStatus);
        } else {
            // Jika TIDAK ada filter status yang dipilih, DEFAULT-nya SELALU 'Terjadwal'.
            // Kita menghapus pengecekan "has search" agar pencarian pun tetap dibatasi pada status 'Terjadwal'.
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
                  ->orWhereHas('masterTuk', function($tq) use ($searchTerm) {
                      $tq->where('nama_lokasi', 'like', '%' . $searchTerm . '%');
                  })
                  ->orWhereHas('asesor', function($aq) use ($searchTerm) {
                      $aq->where('nama_lengkap', 'like', '%' . $searchTerm . '%');
                  });
            });
        }

        // Logic Sorting dengan JOIN (Agar bisa sort berdasarkan nama, bukan ID)
        $query->select('jadwal.*'); // Penting: Select tabel utama agar ID tidak tertimpa saat join
        
        if ($sortColumn == 'skema_nama') {
            $query->join('skema', 'jadwal.id_skema', '=', 'skema.id_skema')
                  ->orderBy('skema.nama_skema', $sortDirection);
        } elseif ($sortColumn == 'tuk_nama') {
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
        $perPage = $request->input('per_page', 5); // Default 5 untuk Dashboard
        if (!in_array($perPage, $allowedPerpage)) {
            $perPage = 5;
        }

        $jadwalTerbaru = $query->paginate($perPage)->onEachSide(0.5);
        
        // Append query string ke link pagination
        $jadwalTerbaru->appends($request->only([
            'sort', 'direction', 'search', 'per_page', 'filter_status', 'filter_jenis_tuk'
        ]));

        // Kirim data ke View
        return view('admin.dashboard.dashboard_admin', [
            'asesmenBerlangsung' => $asesmenBerlangsung,
            'asesmenSelesai'     => $asesmenSelesai,
            'jumlahAsesi'        => $jumlahAsesi,
            'jumlahAsesor'       => $jumlahAsesor,
            'jadwalTerbaru'      => $jadwalTerbaru,
            'perPage'            => $perPage,
            'sortColumn'         => $sortColumn,
            'sortDirection'      => $sortDirection,
            // Kirim variabel filter balik ke view untuk state dropdown
            'filterStatus'       => $filterStatus,
            'filterJenisTuk'     => $filterJenisTuk
        ]);
    }
}

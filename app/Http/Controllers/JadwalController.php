<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Jadwal;
use App\Models\Asesor;
use App\Models\Asesi;

class JadwalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        // 1. Dapatkan ID user yang sedang login
        $user_id = Auth::id();

        // 2. Cari data Asesor berdasarkan user_id yang login
        // (Asumsi: tabel 'asesors' punya kolom 'user_id' yang terhubung ke tabel 'users')
        $asesor = Asesor::where('user_id', $user_id)->first();

        // Ambil parameter dari URL (untuk Search & Sort)
        $search = $request->query('search');
        $sortBy = $request->query('sort_by', 'tanggal_pelaksanaan'); // Default sort
        $sortDir = $request->query('sort_dir', 'asc'); // Default direction (sesuai 'oldest' Anda)

        $filterSesi = $request->query('filter_sesi');
        $filterStatus = $request->query('filter_status');
        $filterJenisTuk = $request->query('filter_jenis_tuk');
        $filterTanggal = $request->query('filter_tanggal');

        // 3. Jika asesor tidak ditemukan, kirim data jadwal kosong
        if (!$asesor) {
            // Mengirim Paginator kosong agar method ->links() di view tidak error
            $jadwals = Jadwal::whereRaw('1 = 0')->paginate(10);

            return view('frontend.jadwal-asesor', [
                'jadwals' => $jadwals,
                'currentSearch' => $search,
                'currentSortBy' => $sortBy,
                'currentSortDir' => $sortDir,
                'filter_sesi' => $filterSesi,
                'filter_status' => $filterStatus,
                'filter_jenis_tuk' => $filterJenisTuk,
                'filter_tanggal' => $filterTanggal,
            ]);
        }

        $query = Jadwal::query()
                        ->where('id_asesor', $asesor->id_asesor);

        // --- 3. Bangun Query Utama ---
        $relations = ['skema', 'tuk', 'jenisTuk'];

        // Filter Status
        $query->when($filterStatus, function ($q, $status) {
            return $q->where('Status_jadwal', $status);
        });

        // Filter Jenis TUK
        $query->when($filterJenisTuk, function ($q, $jenis) {
            // Asumsi 'jenisTuk' adalah relasi ke tabel yang punya kolom 'jenis_tuk'
            return $q->whereHas('jenisTuk', function ($rel) use ($jenis) {
                $rel->where('jenis_tuk', $jenis);
            });
        });

        // Filter Tanggal
        $query->when($filterTanggal, function ($q, $tanggal) {
            // Membandingkan kolom tanggal_pelaksanaan dengan tanggal yang di-input
            return $q->whereDate('tanggal_pelaksanaan', $tanggal);
        });

        // --- 4. Tambahkan Logika SEARCHING ---
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('Status_jadwal', 'like', "%{$search}%")
                  ->orWhere('sesi', 'like', "%{$search}%")
                  // Mencari di relasi 'skema'
                  ->orWhereHas('skema', function ($skemaQuery) use ($search) {
                      $skemaQuery->where('nama_skema', 'like', "%{$search}%");
                  })
                  // Mencari di relasi 'tuk'
                  ->orWhereHas('tuk', function ($tukQuery) use ($search) {
                      $tukQuery->where('nama_lokasi', 'like', "%{$search}%");
                  });
            });
        }

        // --- 5. Tambahkan Logika SORTING ---
        $sortableColumns = [
            'tanggal_pelaksanaan',
            'Status_jadwal',
            'sesi',
            'waktu_mulai',
            // Ini untuk relasi
            'nama_skema',
            'nama_lokasi'
        ];

        if (in_array($sortBy, $sortableColumns)) {
            if ($sortBy == 'nama_skema') {
                // Join untuk sorting berdasarkan relasi
                $query->join('skema', 'jadwal.id_skema', '=', 'skema.id_skema')
                      ->orderBy('skema.nama_skema', $sortDir)
                      ->select('jadwal.*'); // Penting: agar tidak bentrok kolom

                // Hapus 'skema' dari $relations karena sudah di-join
                unset($relations[array_search('skema', $relations)]);

            } elseif ($sortBy == 'nama_lokasi') {
                // Join untuk sorting berdasarkan relasi
                $query->join('master_tuk', 'jadwal.id_tuk', '=', 'tuk.id_tuk')
                      ->orderBy('tuk.nama_lokasi', $sortDir)
                      ->select('jadwal.*'); // Penting: agar tidak bentrok kolom

                // Hapus 'tuk' dari $relations karena sudah di-join
                unset($relations[array_search('tuk', $relations)]);
            } else {
                // Sorting untuk kolom di tabel 'jadwals'
                $query->orderBy($sortBy, $sortDir);
            }
        } else {
            // Fallback default sort
            $query->orderBy('tanggal_pelaksanaan', 'asc');
        }

        // --- 6. Eksekusi Query dengan Eager Loading & PAGINATION ---
        // Panggil 'with' di AKHIR, HANYA dengan relasi yang tersisa
        $jadwals = $query->with($relations)
                        ->paginate(10)
                        ->appends($request->query());


        // Kirim data 'jadwals' ke view
        return view('frontend.jadwal-asesor', [
            'jadwals' => $jadwals,
            'currentSearch' => $search,
            'currentSortBy' => $sortBy,
            'currentSortDir' => $sortDir,
            'filter_sesi' => $filterSesi,
            'filter_status' => $filterStatus,
            'filter_jenis_tuk' => $filterJenisTuk,
            'filter_tanggal' => $filterTanggal,
        ]);
        return view('landing_page.jadwal', compact('jadwalList'));
    }

    public function showAsesi($id_jadwal) // <-- Variabel ini datang dari URL
    {
        // 1. Dapatkan data jadwal (Hapus 'asesi' dari 'with')
        $jadwal = Jadwal::with(['skema', 'tuk']) // <-- Modifikasi 1: Hapus 'asesi'
                        ->findOrFail($id_jadwal);

        // 2. PENTING: Cek apakah asesor ini berhak melihat jadwal ini
        // (Ambil $asesor dari Auth seperti di method index)
        $asesor = Asesor::where('user_id', Auth::id())->first();
        if (!$asesor || $jadwal->id_asesor != $asesor->id_asesor) {
             abort(403, 'Anda tidak berhak mengakses jadwal ini.');
        }

        // 3. (MODIFIKASI UTAMA) Dapatkan daftar Asesi secara manual
        //    melalui tabel 'data_sertifikasi_asesi'

        // 3a. Dapatkan dulu semua ID Asesi dari tabel perantara
        //     (Asumsi: tabel 'data_sertifikasi_asesi' punya kolom 'id_jadwal' dan 'id_asesi')
        $asesiIds = DataSertifikasiAsesi::where('id_jadwal', $id_jadwal)
                                ->pluck('id_asesi') // ->pluck() hanya mengambil kolom 'id_asesi'
                                ->unique(); // Pastikan tidak ada ID asesi yang duplikat

        // 3b. Ambil data lengkap Asesi berdasarkan ID yang kita temukan
        //     (Asumsi: primary key di tabel 'asesi' adalah 'id_asesi')
        $asesis = Asesi::whereIn('id_asesi', $asesiIds)->get();

        // 4. Kirim data ke view 'daftar_asesi'
        //    (Struktur data yang dikirim tetap sama, jadi view tidak perlu diubah)
        return view('frontend.daftar_asesi', [
            'jadwal' => $jadwal,
            'asesis' => $asesis, // <-- Variabel $asesis berhasil kita buat
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Jadwal;
use App\Models\Asesor;
use App\Models\Skema;
use App\Models\MasterTuk;
use App\Models\JenisTuk;
use App\Models\Asesi;
use App\Models\DataSertifikasiAsesi;

class JadwalController extends Controller
{
    /**
     * Display a listing of the resource.
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

        /*$profile = [
            'nama' => $asesor->nama_lengkap ?? 'Nama Asesor',
            'nomor_registrasi' => $asesor->nomor_regis ?? 'Belum ada NOREG',
            'kompetensi' => 'Pemrograman',
            // Menggunakan Accessor getUrlFotoAttribute yang sudah kita buat di Model (opsional)
            // atau logika manual jika belum pakai accessor
            'foto_url' => $asesor->url_foto ?? 'https://placehold.co/60x60/8B5CF6/ffffff?text=AF',
        ];*/        

        // 3. Data Jadwal Asesmen
        $jadwal = Jadwal::where('id_asesor', $id_asesor)
                            ->with('skema', 'tuk', 'jenisTuk')
                            ->orderBy('tanggal_pelaksanaan', 'asc');        

        // A. Filter Pencarian (Search Input)
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $jadwal->where(function ($q) use ($searchTerm) {
                $q->where('Status_jadwal', 'like', '%' . $searchTerm . '%')
                  ->orWhere('waktu_mulai', 'like', '%' . $searchTerm . '%')  
                  ->orWhere('tanggal_pelaksanaan', 'like', '%' . $searchTerm . '%')
                  ->orWhere('sesi', 'like', '%' . $searchTerm . '%')              
                  ->orWhereHas('skema', function ($qSkema) use ($searchTerm) {
                      $qSkema->where('nama_skema', 'like', '%' . $searchTerm . '%');
                  })
                  ->orWhereHas('tuk', function ($qTuk) use ($searchTerm) {
                      $qTuk->where('nama_lokasi', 'like', '%' . $searchTerm . '%'); 
                  })                     
                  ->orWhereHas('jenis_tuk', function ($qJenisTuk) use ($searchTerm) {
                      $qJenisTuk->where('jenis_tuk', 'like', '%' . $searchTerm . '%');                      
                  });
            });
        }
        
        // B. Filter Nama Skema (dari Checkbox)
        if ($request->has('namaskema') && is_array($request->namaskema)) {
            $jadwal->whereHas('skema', function ($q) use ($request) {
                $q->whereIn('nama_skema', $request->namaskema);
            });
        }

        // Filter sesi
        if ($request->has('sesi') && is_array($request->sesi)) {
            $jadwal->whereIn('sesi', $request->sesi);
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
        
        // Waktu
        if ($request->filled('waktu')) {
            $jadwal->whereTime('waktu_mulai', '>=', $request->waktu);
        }        
        // Filter TUK
        if ($request->has('tuk') && is_array($request->tuk)) {
            $jadwal->whereIn('tuk', $request->tuk);
        }

        // Filter Jenis TUK
        if ($request->has('jenistuk') && is_array($request->jenistuk)) {
            $jadwal->whereIn('jenistuk', $request->jenistuk);
        }        

        // 4. Eksekusi Query Jadwal (Hanya 5 data pertama untuk Dashboard)
        $jadwals = $jadwal->latest()->paginate(10);

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

        $listSesi = Jadwal::where('id_asesor', $id_asesor)
                            ->select('sesi')->distinct()->pluck('sesi')
                            ->filter()->sort()->values();                            
        
        // LIST STATUS
        $listStatus = Jadwal::where('id_asesor', $id_asesor)
                            ->select('Status_jadwal')->distinct()->pluck('Status_jadwal')
                            ->filter()->sort()->values();
                            
        $tukIdsInJadwal = Jadwal::where('id_asesor', $id_asesor)
                                  ->select('id_tuk')->distinct()->pluck('id_tuk');
        $listTuk = MasterTuk::whereIn('id_tuk', $tukIdsInJadwal)
                          ->pluck('nama_lokasi')->filter()->sort()->values();   
                          
        $jenistukIdsInJadwal = Jadwal::where('id_asesor', $id_asesor)
                                  ->select('id_jenis_tuk')->distinct()->pluck('id_jenis_tuk');
        $listjenisTuk = JenisTuk::whereIn('id_jenis_tuk', $jenistukIdsInJadwal)
                          ->pluck('jenis_tuk')->filter()->sort()->values();                           

        // Kirim ke view frontend.jadwal (dashboard asesor)
        return view('frontend.jadwal-asesor', [
            //'profile' => $profile,
            'jadwals' => $jadwals,
            'listSkema' => $listSkema, 
            'listSesi' => $listSesi,
            'listStatus' => $listStatus,            
            'listTuk' => $listTuk,
            'listjenisTuk' => $listjenisTuk,
        ]);
    }

    public function showAsesi($id_jadwal) // <-- Variabel ini datang dari URL
    {
        // 1. Dapatkan data jadwal (Hapus 'asesi' dari 'with')
        $jadwal = Jadwal::with(['skema', 'tuk', 'dataSertifikasiAsesi.asesi']) // <-- Modifikasi 1: Hapus 'asesi'
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
        /*$asesiIds = DataSertifikasiAsesi::where('id_jadwal', $id_jadwal)
                                ->pluck('id_asesi') // ->pluck() hanya mengambil kolom 'id_asesi'
                                ->unique(); // Pastikan tidak ada ID asesi yang duplikat

        // 3b. Ambil data lengkap Asesi berdasarkan ID yang kita temukan
        //     (Asumsi: primary key di tabel 'asesi' adalah 'id_asesi')
        $asesis = Asesi::whereIn('id_asesi', $asesiIds)->get();*/

        // 4. Kirim data ke view 'daftar_asesi'
        //    (Struktur data yang dikirim tetap sama, jadi view tidak perlu diubah)
        return view('frontend.daftar_asesi', [
            'jadwal' => $jadwal,
            //'asesis' => $asesis, // <-- Variabel $asesis berhasil kita buat
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
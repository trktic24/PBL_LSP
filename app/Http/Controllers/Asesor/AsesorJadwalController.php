<?php

namespace App\Http\Controllers\Asesor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Jadwal;
use App\Models\Asesor;
use App\Models\Skema;
use App\Models\MasterTUK;
use App\Models\JenisTuk;
use App\Models\Asesi;
use App\Models\DataSertifikasiAsesi;
use App\Models\Ak05;
use App\Models\KomentarAk05;
use App\Models\FrAk06;
use App\Models\PoinPotensiAk07;
use App\Models\PersyaratanModifikasiAk07;
use App\Models\ResponPotensiAk07;
use App\Models\ResponDiperlukanPenyesuaianAk07;
use App\Models\HasilPenyesuaianAk07;
use PDF;

class AsesorJadwalController extends Controller
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
            ->with('skema', 'masterTuk', 'jenisTuk')
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
                    ->orWhereHas('masterTuk', function ($qTuk) use ($searchTerm) {
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
            $jadwal->whereIn('id_tuk', $request->tuk);
        }

        // Filter Jenis TUK
        if ($request->has('jenistuk') && is_array($request->jenistuk)) {
            $jadwal->whereIn('jenistuk', $request->jenistuk);
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
        return view('asesor.jadwal-asesor', [
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
        $jadwal = Jadwal::with(['skema', 'masterTuk', 'dataSertifikasiAsesi.asesi', 'dataSertifikasiAsesi']) // <-- Modifikasi 1: Hapus 'asesi'
            ->findOrFail($id_jadwal);

        // 2. PENTING: Cek apakah asesor ini berhak melihat jadwal ini
        // (Ambil $asesor dari Auth seperti di method index)
        $asesor = Asesor::where('id_user', Auth::id())->first();
        if (!$asesor || $jadwal->id_asesor != $asesor->id_asesor) {
            abort(403, 'Anda tidak berhak mengakses jadwal ini.');
        }

        $sudahVerifikasiValidator = !DataSertifikasiAsesi::where('id_jadwal', $id_jadwal)
            ->whereHas('komentarAk05', function ($q) {
                $q->whereNull('verifikasi_validator');
            })
            ->exists();


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
        return view('asesor.daftar_asesi', [
            'jadwal' => $jadwal,
            'sudahVerifikasiValidator' => $sudahVerifikasiValidator,
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
    public function daftarHadir(Request $request, $id_jadwal)
    {
        // 1. Ambil Data Jadwal Utama
        $jadwal = Jadwal::with(['skema', 'masterTuk', 'asesor'])->findOrFail($id_jadwal);

        // 2. Cek Otorisasi HANYA jika role asesor
        if (Auth::user()->role === 'asesor') {
            $asesor = Asesor::where('id_user', Auth::id())->first();

            if (!$asesor || $jadwal->id_asesor != $asesor->id_asesor) {
                abort(403, 'Anda tidak berhak mengakses jadwal ini.');
            }
        }

        // 3. Setup Default Sorting
        $sortColumn = $request->input('sort', 'id_data_sertifikasi_asesi');
        $sortDirection = $request->input('direction', 'asc');

        // 4. Base Query ke tabel Pivot (DataSertifikasiAsesi)
        $query = DataSertifikasiAsesi::query()
            ->with(['asesi.user', 'asesi.dataPekerjaan', 'presensi'])
            ->where('id_jadwal', $id_jadwal)
            ->select('data_sertifikasi_asesi.*');

        // 5. Logic Sorting Lanjutan (Join Table)
        if (in_array($sortColumn, ['nama_lengkap', 'alamat_rumah', 'pekerjaan', 'nomor_hp'])) {
            $query->join('asesi', 'data_sertifikasi_asesi.id_asesi', '=', 'asesi.id_asesi')
                ->orderBy('asesi.' . $sortColumn, $sortDirection);
        } elseif ($sortColumn == 'institusi') {
            $query->join('asesi', 'data_sertifikasi_asesi.id_asesi', '=', 'asesi.id_asesi')
                ->leftJoin('data_pekerjaan_asesi', 'asesi.id_asesi', '=', 'data_pekerjaan_asesi.id_asesi')
                ->orderBy('data_pekerjaan_asesi.nama_institusi_pekerjaan', $sortDirection);
        } else {
            $query->orderBy('id_data_sertifikasi_asesi', $sortDirection);
        }

        // 6. Search Logic
        if ($request->has('search') && $request->input('search') != '') {
            $searchTerm = $request->input('search');
            $query->whereHas('asesi', function ($q) use ($searchTerm) {
                $q->where('nama_lengkap', 'like', '%' . $searchTerm . '%')
                    ->orWhere('alamat_rumah', 'like', '%' . $searchTerm . '%')
                    ->orWhere('nomor_hp', 'like', '%' . $searchTerm . '%')
                    ->orWhere('pekerjaan', 'like', '%' . $searchTerm . '%')
                    ->orWhereHas('dataPekerjaan', function ($q2) use ($searchTerm) {
                        $q2->where('nama_institusi_pekerjaan', 'like', '%' . $searchTerm . '%');
                    });
            });
        }

        // 7. Pagination
        $perPage = $request->input('per_page', 10);
        $pendaftar = $query->paginate($perPage)->appends($request->query());

        // Filter Role
        $mode = Auth::user()->role === 'admin' ? 'view' : 'edit';

        return view('asesor.daftar_hadir', [
            'jadwal' => $jadwal,
            'pendaftar' => $pendaftar,
            'perPage' => $perPage,
            'sortColumn' => $sortColumn,
            'sortDirection' => $sortDirection,
            'role' => Auth::user()->role,
            'mode' => $mode,
        ]);
    }

    public function storeKehadiran(Request $request, $id_jadwal)
    {
        $jadwal = Jadwal::findOrFail($id_jadwal);

        // Cek Otorisasi
        $asesor = Asesor::where('id_user', Auth::id())->first();
        if (!$asesor || $jadwal->id_asesor != $asesor->id_asesor) {
            abort(403, 'Anda tidak berhak mengubah data ini.');
        }

        $dataPresensi = json_decode($request->input('data_presensi'), true);

        if (!is_array($dataPresensi)) {
            return back()->with('error', 'Data presensi tidak valid.');
        }

        foreach ($dataPresensi as $item) {
            // Cari data sertifikasi
            $dataSertifikasi = DataSertifikasiAsesi::with('asesi')->find($item['id_data_sertifikasi_asesi']);

            if (!$dataSertifikasi)
                continue;

            // Update atau Create data di DaftarHadirAsesi
            \App\Models\DaftarHadirAsesi::updateOrCreate(
                [
                    'id_data_sertifikasi_asesi' => $item['id_data_sertifikasi_asesi']
                ],
                [
                    'hadir' => $item['hadir'] ? 1 : 0,
                    'tanda_tangan_asesi' => $dataSertifikasi->asesi->tanda_tangan ?? 'default.png'
                ]
            );
        }

        return back()->with('success', 'Data kehadiran berhasil disimpan.');
    }

    public function beritaAcara(Request $request, $id_jadwal)
    {
        // 1. Ambil Data Jadwal Utama
        $jadwal = Jadwal::with(['skema', 'masterTuk', 'asesor'])->findOrFail($id_jadwal);

        $user = Auth::user();
        $userRole = $user->role->nama_role;
        $isAdmin = in_array($userRole, ['admin', 'superadmin']);
        $isAsesor = $userRole === 'asesor';

        // 2. Cek Otorisasi: Asesor harus punya akses ke jadwal ini, Admin bisa akses semua (readonly)
        $asesor = null;
        if ($isAsesor) {
            $asesor = Asesor::where('id_user', Auth::id())->first();
            if (!$asesor || $jadwal->id_asesor != $asesor->id_asesor) {
                abort(403, 'Anda tidak berhak mengakses jadwal ini.');
            }
        } elseif ($isAdmin) {
            // Admin bisa akses semua jadwal (readonly)
            $asesor = $jadwal->asesor; // Ambil asesor dari jadwal untuk display
        } else {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        // 2.5 Cek apakah seluruh AK05 sudah diverifikasi validator (hanya untuk asesor, admin bisa lihat semua)
        if ($isAsesor) {
            $adaBelumDiverifikasi = DataSertifikasiAsesi::where('id_jadwal', $id_jadwal)
                ->whereHas('komentarAk05', function ($q) {
                    $q->whereNull('verifikasi_validator'); // kolom verifikasi validator
                })
                ->exists();

            if ($adaBelumDiverifikasi) {
                abort(403, 'Berita Acara belum dapat diakses karena hasil asesmen belum diverifikasi.');
            }
        }

        // 3. Setup Default Sorting
        $sortColumn = $request->input('sort', 'id_data_sertifikasi_asesi');
        $sortDirection = $request->input('direction', 'asc');

        // 4. Base Query ke tabel Pivot (DataSertifikasiAsesi)
        $query = DataSertifikasiAsesi::query()
            ->with(['asesi.user', 'asesi.dataPekerjaan', 'presensi', 'komentarAk05'])
            ->where('id_jadwal', $id_jadwal)
            ->select('data_sertifikasi_asesi.*');

        // 5. Logic Sorting Lanjutan (Join Table)
        if (in_array($sortColumn, ['nama_lengkap', 'alamat_rumah', 'pekerjaan', 'nomor_hp'])) {
            $query->join('asesi', 'data_sertifikasi_asesi.id_asesi', '=', 'asesi.id_asesi')
                ->orderBy('asesi.' . $sortColumn, $sortDirection);
        } elseif ($sortColumn == 'institusi') {
            $query->join('asesi', 'data_sertifikasi_asesi.id_asesi', '=', 'asesi.id_asesi')
                ->leftJoin('data_pekerjaan_asesi', 'asesi.id_asesi', '=', 'data_pekerjaan_asesi.id_asesi')
                ->orderBy('data_pekerjaan_asesi.nama_institusi_pekerjaan', $sortDirection);
        } else {
            $query->orderBy('id_data_sertifikasi_asesi', $sortDirection);
        }

        // 6. Search Logic
        if ($request->has('search') && $request->input('search') != '') {
            $searchTerm = $request->input('search');
            $query->whereHas('asesi', function ($q) use ($searchTerm) {
                $q->where('nama_lengkap', 'like', '%' . $searchTerm . '%')
                    ->orWhere('alamat_rumah', 'like', '%' . $searchTerm . '%')
                    ->orWhere('nomor_hp', 'like', '%' . $searchTerm . '%')
                    ->orWhere('pekerjaan', 'like', '%' . $searchTerm . '%')
                    ->orWhereHas('dataPekerjaan', function ($q2) use ($searchTerm) {
                        $q2->where('nama_institusi_pekerjaan', 'like', '%' . $searchTerm . '%');
                    });
            });
        }

        // 7. Pagination
        $perPage = $request->input('per_page', 10);
        $pendaftar = $query->paginate($perPage)->appends($request->query());

        $jumlahKompeten = $jadwal->dataSertifikasiAsesi
            ->filter(fn($d) => $d->komentarAk05?->rekomendasi === 'K')
            ->count();

        $jumlahBelumKompeten = $jadwal->dataSertifikasiAsesi
            ->filter(fn($d) => $d->komentarAk05?->rekomendasi === 'BK')
            ->count();

        return view('asesor.berita_acara', [
            'asesor' => $asesor,
            'jadwal' => $jadwal,
            'pendaftar' => $pendaftar,
            'perPage' => $perPage,
            'sortColumn' => $sortColumn,
            'sortDirection' => $sortDirection,
            'jumlahKompeten' => $jumlahKompeten,
            'jumlahBelumKompeten' => $jumlahBelumKompeten,
            'role' => Auth::user()->role,
            'isReadonly' => $isAdmin, // Flag untuk readonly mode (admin)
        ]);
    }


    public function exportPdfdaftarhadir($id_jadwal)
    {
        $jadwal = Jadwal::with(['skema', 'asesor', 'masterTuk'])->findOrFail($id_jadwal);
        $pendaftar = DataSertifikasiAsesi::with('asesi.dataPekerjaan', 'presensi')
            ->where('id_jadwal', $id_jadwal)
            ->get();

        $pdf = PDF::loadView('pdf.daftar_hadir', compact('jadwal', 'pendaftar'));
        return $pdf->download('daftar_hadir_' . $jadwal->id_jadwal . '.pdf');
    }

    public function exportPdfberitaAcara($id_jadwal)
    {
        $jadwal = Jadwal::with(['skema', 'asesor', 'masterTuk'])->findOrFail($id_jadwal);
        $pendaftar = DataSertifikasiAsesi::with('asesi.dataPekerjaan', 'presensi', 'komentarAk05')
            ->where('id_jadwal', $id_jadwal)
            ->get();

        $jumlahKompeten = $jadwal->dataSertifikasiAsesi
            ->filter(fn($d) => $d->komentarAk05?->rekomendasi === 'K')
            ->count();

        $jumlahBelumKompeten = $jadwal->dataSertifikasiAsesi
            ->filter(fn($d) => $d->komentarAk05?->rekomendasi === 'BK')
            ->count();

        $pdf = PDF::loadView('pdf.berita_acara', compact('jadwal', 'pendaftar', 'jumlahKompeten', 'jumlahBelumKompeten'));
        return $pdf->download('berita_acara_' . $jadwal->id_jadwal . '.pdf');
    }

    public function ak05($id_jadwal)
    {
        $jadwal = Jadwal::with(['skema', 'masterTuk', 'asesor', 'dataSertifikasiAsesi.asesi'])->findOrFail($id_jadwal);

        // Cek Otorisasi
        $asesor = Asesor::where('id_user', Auth::id())->first();
        if (!$asesor || $jadwal->id_asesor != $asesor->id_asesor) {
            abort(403, 'Anda tidak berhak mengakses jadwal ini.');
        }

        return view('frontend.AK_05.FR_AK_05', compact('jadwal'));
    }

    public function ak06($id_jadwal)
    {
        $jadwal = Jadwal::with(['skema', 'masterTuk', 'asesor'])->findOrFail($id_jadwal);

        // Cek Otorisasi
        $asesor = Asesor::where('id_user', Auth::id())->first();
        if (!$asesor || $jadwal->id_asesor != $asesor->id_asesor) {
            abort(403, 'Anda tidak berhak mengakses jadwal ini.');
        }

        return view('frontend.FR_AK_06', compact('jadwal'));
    }

    public function ak07($id_sertifikasi_asesi)
    {
        $sertifikasi = DataSertifikasiAsesi::with(['asesi', 'jadwal.skema', 'jadwal.masterTuk', 'jadwal.skema.asesor'])
            ->findOrFail($id_sertifikasi_asesi);

        // Cek Otorisasi
        $asesor = Asesor::where('id_user', Auth::id())->first();
        if (!$asesor || $sertifikasi->jadwal->id_asesor != $asesor->id_asesor) {
            abort(403, 'Anda tidak berhak mengakses data ini.');
        }

        $masterPotensi = PoinPotensiAk07::all();
        $masterPersyaratan = PersyaratanModifikasiAk07::with('catatanKeterangan')->get();
        $isReadOnly = false;

        return view('frontend.AK_07.FR_AK_07', compact('sertifikasi', 'masterPotensi', 'masterPersyaratan', 'isReadOnly'));
    }

    public function storeAk05(Request $request, $id_jadwal)
    {
        $request->validate([
            'asesi' => 'required|array',
            'asesi.*.rekomendasi' => 'required|in:K,BK',
        ]);

        \DB::beginTransaction();
        try {
            // Save Global AK05 Data
            // Assuming AK05 table might be linked to Jadwal, but the model doesn't show id_jadwal.
            // If table 'ak05' is for template, we might be using it wrong.
            // However, based on the input 'aspek_asesmen', etc, it seems to be a record per schedule.
            // Let's assume we create a new AK05 record or update if logic permits.
            // For now, simpliest approach: Create/Update based on some context if possible,
            // or just Create new one and link Komentar to it.
            // But KomentarAk05 links to id_ak05.

            // Check if there's an existing AK05 for this Jadwal?
            // The model Ak05 doesn't have id_jadwal.
            // Maybe it's many-to-many? Or one-to-one via another table?
            // Given the constraints and lack of clear relation in Ak05 model,
            // I will assume we create an Ak05 record for this submission.

            // Search if we already have an AK05 for this context (via Komentar -> DataSertifikasi -> Jadwal?)
            // This is tricky without explicit foreign key in Ak05.
            // Let's create a new one or update the one associated with the first Asesi in this Jadwal if exists.

            $firstAsesiId = array_key_first($request->asesi);
            $firstKomentar = KomentarAk05::where('id_data_sertifikasi_asesi', $firstAsesiId)->first();

            if ($firstKomentar && $firstKomentar->Ak05) {
                $ak05 = $firstKomentar->Ak05;
            } else {
                $ak05 = new Ak05();
            }

            $ak05->aspek_negatif_positif = $request->aspek_asesmen;
            $ak05->penolakan_hasil_asesmen = $request->catatan_penolakan;
            $ak05->saran_perbaikan = $request->saran_perbaikan;
            $ak05->catatan_akhir = $request->catatan_akhir; // Assuming column exists based on form
            $ak05->save();

            // Save Per-Asesi Data
            foreach ($request->asesi as $id_sertifikasi_asesi => $data) {
                KomentarAk05::updateOrCreate(
                    ['id_data_sertifikasi_asesi' => $id_sertifikasi_asesi],
                    [
                        'id_ak05' => $ak05->id_ak05,
                        'rekomendasi' => $data['rekomendasi'],
                        'keterangan' => $data['keterangan'] ?? null,
                    ]
                );
            }

            \DB::commit();
            return redirect()->back()->with('success', 'Laporan FR.AK.05 berhasil disimpan.');

        } catch (\Exception $e) {
            \DB::rollBack();
            return redirect()->back()->with('error', 'Gagal menyimpan data: ' . $e->getMessage());
        }
    }

    public function storeAk06(Request $request, $id_jadwal)
    {
        // Save FR.AK.06
        FrAk06::create([
            'id_jadwal' => $id_jadwal,
            'tinjauan' => $request->tinjauan,
            'dimensi' => $request->dimensi,
            'peninjau' => $request->peninjau,
            'komentar' => $request->peninjau['komentar'] ?? null,
        ]);

        return redirect()->back()->with('success', 'Form FR.AK.06 berhasil disimpan.');
    }

    public function storeAk07(Request $request, $id_sertifikasi_asesi)
    {
        \DB::beginTransaction();
        try {
            // 1. Simpan Respon Potensi
            if ($request->has('potensi_asesi')) {
                foreach ($request->potensi_asesi as $idPoin => $respon) {
                    ResponPotensiAK07::updateOrCreate(
                        [
                            'id_data_sertifikasi_asesi' => $id_sertifikasi_asesi,
                            'id_poin_potensi_AK07' => $idPoin
                        ],
                        ['respon_asesor' => $respon]
                    );
                }
            }

            // 2. Simpan Penyesuaian
            if ($request->has('penyesuaian')) {
                foreach ($request->penyesuaian as $idPersyaratan => $catatanGroup) {
                    foreach ($catatanGroup as $idCatatan => $data) {
                        ResponDiperlukanPenyesuaianAK07::updateOrCreate(
                            [
                                'id_data_sertifikasi_asesi' => $id_sertifikasi_asesi,
                                'id_persyaratan_modifikasi_AK07' => $idPersyaratan,
                                'id_catatan_keterangan_AK07' => $idCatatan
                            ],
                            [
                                'respon_penyesuaian' => $data['status'] ?? 'Tidak', // Default to Tidak/Ada logic
                                'respon_catatan_keterangan' => $data['notes'] ?? null
                            ]
                        );
                    }
                }
            }

            // 3. Simpan Hasil Penyesuaian
            if ($request->has('hasil_penyesuaian')) {
                HasilPenyesuaianAK07::updateOrCreate(
                    ['id_data_sertifikasi_asesi' => $id_sertifikasi_asesi],
                    [
                        'Acuan_Pembanding_Asesmen' => $request->hasil_penyesuaian['Acuan_Pembanding_Asesmen'] ?? null,
                        'Metode_Asesmen' => $request->hasil_penyesuaian['Metode_Asesmen'] ?? null,
                        'Instrumen_Asesmen' => $request->hasil_penyesuaian['Instrumen_Asesmen'] ?? null,
                    ]
                );
            }

            \DB::commit();
            return redirect()->back()->with('success', 'Form FR.AK.07 berhasil disimpan.');

        } catch (\Exception $e) {
            \DB::rollBack();
            return redirect()->back()->with('error', 'Gagal menyimpan data: ' . $e->getMessage());
        }
    }
}

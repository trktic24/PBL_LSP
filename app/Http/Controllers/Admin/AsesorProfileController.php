<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

// --- Import Model Real ---
use App\Models\Asesor;
use App\Models\Jadwal;
use App\Models\DataSertifikasiAsesi;
use App\Models\Skema;

class AsesorProfileController extends Controller
{
    // ==========================================================
    // HALAMAN PROFIL SETTINGS
    // ==========================================================
    public function showProfile($id_asesor)
    {
        $asesor = Asesor::with('user')->findOrFail($id_asesor);
        
        return view('Admin.profile_asesor.asesor_profile_settings', compact('asesor'));
    }

    // ==========================================================
    // HALAMAN BUKTI KELENGKAPAN
    // ==========================================================
    public function showBukti($id_asesor)
    {
        $asesor = Asesor::findOrFail($id_asesor);
        
        $documents = [
            ['key' => 'ktp', 'title' => 'KTP', 'subtitle' => 'Kartu Tanda Penduduk', 'file_path' => $asesor->ktp],
            ['key' => 'pas_foto', 'title' => 'Foto', 'subtitle' => 'Pas Foto 3x4', 'file_path' => $asesor->pas_foto],
            ['key' => 'NPWP_foto', 'title' => 'NPWP', 'subtitle' => 'Kartu NPWP', 'file_path' => $asesor->NPWP_foto],
            ['key' => 'rekening_foto', 'title' => 'Rekening', 'subtitle' => 'Buku Rekening Bank', 'file_path' => $asesor->rekening_foto],
            ['key' => 'CV', 'title' => 'Curiculum Vitae (CV)', 'subtitle' => 'CV terbaru', 'file_path' => $asesor->CV],
            ['key' => 'ijazah', 'title' => 'Ijazah Pendidikan', 'subtitle' => 'Ijazah pendidikan terakhir', 'file_path' => $asesor->ijazah],
            ['key' => 'sertifikat_asesor', 'title' => 'Sertifikat Asesor Kompetensi', 'subtitle' => 'Sertifikat kompetensi', 'file_path' => $asesor->sertifikat_asesor],
            ['key' => 'sertifikasi_kompetensi', 'title' => 'Sertifikasi Kompetensi', 'subtitle' => 'Sertifikat teknis', 'file_path' => $asesor->sertifikasi_kompetensi],
        ];

        return view('Admin.profile_asesor.asesor_profile_bukti', compact('asesor', 'documents'));
    }

    // ==========================================================
    // HALAMAN TINJAUAN ASESMEN (RIWAYAT JADWAL)
    // ==========================================================
    public function showTinjauan($id_asesor)
    {
        $asesor = Asesor::findOrFail($id_asesor);
        
        // [REAL DATA] Mengambil jadwal milik asesor
        $tinjauan_data = Jadwal::with(['skema', 'masterTuk'])
            ->where('id_asesor', $id_asesor)
            ->orderBy('tanggal_pelaksanaan', 'desc')
            ->get();

        return view('Admin.profile_asesor.asesor_profile_tinjauan', compact('asesor', 'tinjauan_data'));
    }

    // ==========================================================
    // DAFTAR ASESI PADA JADWAL TERTENTU (DIPERBAIKI)
    // ==========================================================
    public function showDaftarAsesi(Request $request, $id_asesor, $id_jadwal)
    {
        // 1. Ambil Parameter Filter dari Request
        $search = $request->input('search');
        $perPage = $request->input('per_page', 10);
        $sortColumn = $request->input('sort', 'nama_lengkap');
        $sortDirection = $request->input('direction', 'asc');

        // 2. Validasi Data Asesor & Jadwal
        $asesor = Asesor::findOrFail($id_asesor);
        
        // PERBAIKAN: Menggunakan 'masterTuk' sesuai model Jadwal
        $jadwal = Jadwal::with(['skema', 'masterTuk'])
            ->where('id_jadwal', $id_jadwal)
            ->where('id_asesor', $id_asesor)
            ->firstOrFail();

        // 3. Query Dasar untuk $pendaftar (DataSertifikasiAsesi)
        $query = DataSertifikasiAsesi::query()
            ->with([
                'asesi.dataPekerjaan', 
                'responApl2Ia01', 
                'responBuktiAk01', 
                'lembarJawabIa05', 
                'komentarAk05'
            ])
            ->where('id_jadwal', $id_jadwal);

        // 4. Logika Pencarian (Search)
        if ($search) {
            $query->whereHas('asesi', function ($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%");
            });
        }

        // 5. Logika Pengurutan (Sorting)
        if ($sortColumn === 'nama_lengkap') {
            // Join table asesi untuk sorting berdasarkan nama
            $query->join('asesi', 'data_sertifikasi_asesi.id_asesi', '=', 'asesi.id_asesi')
                ->orderBy('asesi.nama_lengkap', $sortDirection)
                ->select('data_sertifikasi_asesi.*'); // Penting: Pilih kolom tabel utama agar ID tidak tertimpa
        } else {
            // Default sort
            $query->orderBy('id_data_sertifikasi_asesi', 'asc');
        }

        // 6. Eksekusi Pagination
        $pendaftar = $query->paginate($perPage)->withQueryString();

        // 7. Logika Tombol "Berita Acara" (Cek apakah semua sudah dinilai)
        $semuaDataJadwal = DataSertifikasiAsesi::where('id_jadwal', $id_jadwal)->get();
        
        $semuaSudahAdaKomentar = $semuaDataJadwal->isNotEmpty() && $semuaDataJadwal->every(function ($item) {
            return $item->komentarAk05()->exists() || $item->lembarJawabIa05()->exists();
        });

        // 8. Return View
        return view('Admin.profile_asesor.daftar_asesi', compact(
            'asesor',
            'jadwal',
            'pendaftar',
            'sortColumn',
            'sortDirection',
            'perPage',
            'semuaSudahAdaKomentar'
        ));
    }

    // ==========================================================
    // TRACKER PROFIL ASESI (Timeline) / DAFTAR SKEMA (Index)
    // ==========================================================
    public function showTracker($id_asesor, $id_data_sertifikasi_asesi = null)
    {
        $asesor = Asesor::findOrFail($id_asesor);

        // --- MODE 1: Tampilkan Timeline Detail untuk 1 Asesi (File: asesor_profile_tracker_skema.blade.php) ---
        if ($id_data_sertifikasi_asesi) {
            $dataSertifikasi = DataSertifikasiAsesi::with([
                'asesi.user',
                'jadwal.skema',
                'jadwal.masterTuk', 
                'responApl2Ia01',
                'responbuktiAk01',
                'komentarAk05',
            ])->findOrFail($id_data_sertifikasi_asesi);

            if ($dataSertifikasi->jadwal->id_asesor != $id_asesor) {
                abort(404, 'Data sertifikasi tidak ditemukan untuk asesor ini.');
            }

            $timelineData = [];

            // 1. Pendaftaran
            $timelineData[] = [
                'title' => 'Formulir Pendaftaran Sertifikasi',
                'date' => $dataSertifikasi->created_at->format('l, d F Y'),
                'status_text' => 'Diterima',
                'icon' => 'far fa-user',
            ];

            // 2. Pra-Asesmen
            $hasApl02 = $dataSertifikasi->responApl2Ia01()->exists();
            $timelineData[] = [
                'title' => 'Pra-Asesmen (APL.02)',
                'date' => $hasApl02 ? $dataSertifikasi->responApl2Ia01->updated_at->format('l, d F Y') : '-',
                'status_text' => $hasApl02 ? 'Diterima' : 'Menunggu',
                'icon' => 'fas fa-paperclip',
            ];

            // 3. Asesmen
            $hasAk05 = $dataSertifikasi->komentarAk05()->exists();
            $rekomendasi = $hasAk05 ? ($dataSertifikasi->komentarAk05->rekomendasi ?? '-') : '-';
            
            $subItems = [];
            if ($hasAk05) {
                $subItems[] = [
                    'title' => 'Hasil Asesmen (AK.05)',
                    'date' => $dataSertifikasi->komentarAk05->updated_at->format('d M Y H:i'),
                    'status' => $rekomendasi == 'K' ? 'Kompeten' : 'Belum Kompeten'
                ];
            }

            $timelineData[] = [
                'title' => 'Pelaksanaan Asesmen',
                'date' => $dataSertifikasi->jadwal->tanggal_pelaksanaan,
                'status_text' => $hasAk05 ? 'Selesai' : 'Terjadwal',
                'icon' => 'far fa-edit',
                'sub_items' => $subItems,
                'action_url' => route('admin.asesor.assessment.detail', ['id_asesor' => $asesor->id_asesor, 'id_data_sertifikasi_asesi' => $dataSertifikasi->id_data_sertifikasi_asesi])
            ];

            // Menggunakan asesor_profile_tracker_skema untuk detail timeline (sesuai permintaan user)
            return view('Admin.profile_asesor.asesor_profile_tracker_skema', compact('asesor', 'timelineData', 'dataSertifikasi'));
        } 
        
        // --- MODE 2: Tampilkan Daftar Skema/Jadwal (Index) (File: asesor_profile_tracker.blade.php) ---
        
        // Mengambil semua jadwal yang pernah diasesori oleh asesor ini
        $tracker_data = Jadwal::with(['skema'])
            ->where('id_asesor', $id_asesor)
            ->orderBy('tanggal_pelaksanaan', 'desc')
            ->get();
            
        // Menggunakan asesor_profile_tracker untuk daftar skema (sesuai permintaan user)
        return view('Admin.profile_asesor.asesor_profile_tracker', compact('asesor', 'tracker_data'));
    }

    // ==========================================================
    // TRACKER SKEMA (Daftar Asesi per Jadwal)
    // ==========================================================
    // ==========================================================
    // TRACKER SKEMA (Timeline Progress Jadwal)
    // ==========================================================
    public function showTrackerSkema(Request $request, $id_asesor, $id_jadwal)
    {
        $asesor = Asesor::findOrFail($id_asesor);
        $jadwal = Jadwal::with(['skema', 'masterTuk', 'dataSertifikasiAsesi'])->findOrFail($id_jadwal);

        // Hitung Statistik
        $totalAsesi = $jadwal->dataSertifikasiAsesi->count();
        $verifiedAsesi = $jadwal->dataSertifikasiAsesi->where('rekomendasi_apl02', 'diterima')->count();
        
        // Cek kelengkapan asesmen (misal: sudah ada rekomendasi AK.05)
        $completedAsesi = $jadwal->dataSertifikasiAsesi->filter(function ($asesi) {
            return $asesi->komentarAk05()->exists();
        })->count();

        $schemeTimelineData = [];

        // 1. Penetapan Jadwal
        $schemeTimelineData[] = [
            'title' => 'Penetapan Jadwal',
            'date' => $jadwal->created_at->format('d F Y'),
            'status_text' => 'Jadwal Dibuat',
            'icon' => 'far fa-calendar-check',
            'is_completed' => true,
        ];

        // 2. Verifikasi Peserta (APL.02)
        $isVerifikasiSelesai = ($totalAsesi > 0 && $verifiedAsesi == $totalAsesi);
        $schemeTimelineData[] = [
            'title' => 'Verifikasi Peserta (APL.02)',
            'date' => $isVerifikasiSelesai ? 'Selesai' : 'Dalam Proses',
            'status_text' => "$verifiedAsesi / $totalAsesi Peserta Terverifikasi",
            'icon' => 'fas fa-users-cog',
            'is_completed' => $isVerifikasiSelesai,
        ];

        // 3. Pelaksanaan Asesmen
        $today = \Carbon\Carbon::now();
        $tglPelaksanaan = $jadwal->tanggal_pelaksanaan ? \Carbon\Carbon::parse($jadwal->tanggal_pelaksanaan) : null;
        $isAsesmenSelesai = ($tglPelaksanaan && $today->gte($tglPelaksanaan) && $completedAsesi == $totalAsesi && $totalAsesi > 0);
        
        $schemeTimelineData[] = [
            'title' => 'Pelaksanaan Asesmen',
            'date' => $tglPelaksanaan ? $tglPelaksanaan->format('d F Y') : '-',
            'status_text' => $isAsesmenSelesai ? 'Selesai' : ($today->gte($tglPelaksanaan) ? 'Sedang Berlangsung / Belum Semua Dinilai' : 'Terjadwal'),
            'icon' => 'fas fa-edit',
            'is_completed' => $isAsesmenSelesai,
        ];

        // 4. Pelaporan & Berita Acara
        // Anggap selesai jika asesmen selesai (bisa diperketat dengan cek tabel berita acara jika ada)
        $schemeTimelineData[] = [
            'title' => 'Pelaporan & Berita Acara',
            'date' => $isAsesmenSelesai ? 'Siap Dibuat' : 'Menunggu Asesmen',
            'status_text' => $isAsesmenSelesai ? 'Dapat Dicetak' : '-',
            'icon' => 'fas fa-file-contract',
            'is_completed' => $isAsesmenSelesai,
        ];

        // Gunakan view tracker_skema dengan data timeline skema
        return view('Admin.profile_asesor.asesor_profile_tracker_skema', compact('asesor', 'jadwal', 'schemeTimelineData'));
    }

    // ==========================================================
    // DETAIL ASESMEN ASESI (Formulir Lengkap)
    // ==========================================================
    public function showAssessmentDetail($id_asesor, $id_data_sertifikasi_asesi)
    {
        $asesor = Asesor::findOrFail($id_asesor);

        $dataSertifikasi = DataSertifikasiAsesi::with([
            'asesi.user',
            'jadwal.skema',
            'jadwal.masterTuk', // Konsisten gunakan masterTuk
            'responbuktiAk01',
            'ia10', 
            'ia02',
            'ia07',
            'ia06Answers',
            'komentarAk05'
        ])->findOrFail($id_data_sertifikasi_asesi);

        if ($dataSertifikasi->jadwal->id_asesor != $id_asesor) {
            abort(404);
        }

        $asesi = $dataSertifikasi->asesi;

        return view('Admin.profile_asesor.asesor_assessment_detail', compact('asesor', 'dataSertifikasi', 'asesi'));
    }

    // ==========================================================
    // UPDATE STATUS VERIFIKASI ASESOR
    // ==========================================================
    public function updateStatus(Request $request, $id_asesor)
    {
        $request->validate([
            'status_verifikasi' => 'required|in:approved,rejected,pending',
        ]);

        $asesor = Asesor::findOrFail($id_asesor);
        $asesor->status_verifikasi = $request->status_verifikasi;
        $asesor->save();

        return redirect()->back()->with('success', 'Status verifikasi asesor berhasil diperbarui.');
    }
}
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
    // UPDATE PROFILE ASESOR (ADMIN ACTION)
    // ==========================================================
    public function updateProfile(Request $request, $id_asesor)
    {
        $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'nik' => 'required|string|max:16',
            'tempat_lahir' => 'required|string|max:100',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'kebangsaan' => 'required|string|max:100',
            'pekerjaan' => 'required|string|max:255',
            'kabupaten_kota' => 'required|string|max:255',
            'provinsi' => 'required|string|max:255',
            'kode_pos' => 'required|string|max:10',
            'alamat_rumah' => 'required|string',
            'nomor_regis' => 'required|string|max:50',
            'nomor_hp' => 'required|string|max:14',
        ]);

        $asesor = Asesor::findOrFail($id_asesor);
        
        $asesor->update($request->only([
            'nama_lengkap', 'nik', 'tempat_lahir', 'tanggal_lahir', 
            'jenis_kelamin', 'kebangsaan', 'pekerjaan', 
            'kabupaten_kota', 'provinsi', 'kode_pos', 'alamat_rumah', 
            'nomor_regis', 'nomor_hp'
        ]));

        return redirect()->route('admin.asesor.profile', $id_asesor)->with('success', 'Profil asesor berhasil diperbarui.');
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
    public function showTinjauan(Request $request, $id_asesor)
    {
        $asesor = Asesor::findOrFail($id_asesor);
        $search = $request->input('search');

        // [REAL DATA] Mengambil jadwal milik asesor dengan fitur search
        $query = Jadwal::with(['skema', 'masterTuk'])
            ->where('id_asesor', $id_asesor);

        if ($search) {
            $query->where(function ($q) use ($search) {
                // Cari berdasarkan Nama Skema
                $q->whereHas('skema', function ($qSkema) use ($search) {
                    $qSkema->where('nama_skema', 'like', "%{$search}%");
                })
                // Atau cari berdasarkan Nama Peserta (Asesi) yang terdaftar di jadwal tersebut
                ->orWhereHas('dataSertifikasiAsesi.asesi', function ($qAsesi) use ($search) {
                    $qAsesi->where('nama_lengkap', 'like', "%{$search}%");
                });
            });
        }

        $tinjauan_data = $query->orderBy('tanggal_pelaksanaan', 'desc')->get();

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
                'responApl02Ia01', 
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
            // Optimization: Use subquery ordering to avoid join and redundant select
            $query->orderBy(\App\Models\Asesi::select('nama_lengkap')
                ->whereColumn('asesi.id_asesi', 'data_sertifikasi_asesi.id_asesi'), $sortDirection);
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
        ), [
            'targetRoute' => 'admin.asesor.tracker.view',
            'buttonLabel' => 'Lacak Progres'
        ]);
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
                'responApl02Ia01',
                'responBuktiAk01',
                'komentarAk05',
            ])->findOrFail($id_data_sertifikasi_asesi);

            if ($dataSertifikasi->jadwal->id_asesor != $id_asesor) {
                abort(404, 'Data sertifikasi tidak ditemukan untuk asesor ini.');
            }

            $timelineData = [];

            $timelineData = [];

            // 1. Pendaftaran (APL-01)
            $timelineData[] = [
                'title' => 'Formulir Pendaftaran (APL.01)',
                'date' => $dataSertifikasi->created_at->format('l, d F Y'),
                'status_text' => $dataSertifikasi->rekomendasi_apl01 == 'diterima' ? 'Diterima' : 'Menunggu',
                'is_completed' => $dataSertifikasi->rekomendasi_apl01 == 'diterima',
                'icon' => 'far fa-user',
                'action_url' => route('APL_01_1', $dataSertifikasi->id_data_sertifikasi_asesi),
                'action_label' => 'Verifikasi'
            ];

            // 2. Merencanakan Aktivitas (MAPA-01)
            // Logic: Level >= 20 means MAPA-01/02 considered active/done logic in general flow
            $level = $dataSertifikasi->level_status;
            $isMapa01Done = $level >= 20; 
            $timelineData[] = [
                'title' => 'Merencanakan Aktivitas (MAPA.01)',
                'date' => $isMapa01Done ? 'Selesai' : '-',
                'status_text' => $isMapa01Done ? 'Diterima' : 'Menunggu',
                'is_completed' => $isMapa01Done,
                'icon' => 'fas fa-map',
                'action_url' => route('mapa01.index', $dataSertifikasi->id_data_sertifikasi_asesi),
                'action_label' => 'Verifikasi'
            ];

            // 3. Peta Instrumen (MAPA-02)
            $isMapa02Done = $level >= 20; 
            $timelineData[] = [
                'title' => 'Peta Instrumen (MAPA.02)',
                'date' => $isMapa02Done ? 'Selesai' : '-',
                'status_text' => $isMapa02Done ? 'Diterima' : 'Menunggu',
                'is_completed' => $isMapa02Done,
                'icon' => 'fas fa-list-check',
                'action_url' => route('mapa02.show', $dataSertifikasi->id_data_sertifikasi_asesi),
                'action_label' => 'Verifikasi'
            ];

            // 4. Asesmen Mandiri (APL-02)
            $hasApl02 = $dataSertifikasi->responApl02Ia01()->exists();
            $isApl02Accepted = $dataSertifikasi->rekomendasi_apl02 == 'diterima';
            
            $timelineData[] = [
                'title' => 'Asesmen Mandiri (APL.02)',
                'date' => $hasApl02 ? $dataSertifikasi->responApl02Ia01->first()->updated_at->format('l, d F Y') : '-',
                'status_text' => $isApl02Accepted ? 'Diterima' : ($hasApl02 ? 'Menunggu Verifikasi' : 'Belum Mengisi'),
                'is_completed' => $isApl02Accepted,
                'icon' => 'fas fa-paperclip',
                'action_url' => route('asesor.apl02', $dataSertifikasi->id_data_sertifikasi_asesi),
                'action_label' => 'Verifikasi'
            ];

            // 5. Persetujuan Asesmen (AK-01)
            $isAk01Done = ($level >= 40 || $dataSertifikasi->status_sertifikasi == 'persetujuan_asesmen_disetujui');
            $timelineData[] = [
                'title' => 'Persetujuan Asesmen (AK.01)',
                'date' => $isAk01Done ? 'Disetujui' : '-',
                'status_text' => $isAk01Done ? 'Disetujui' : 'Menunggu',
                'is_completed' => $isAk01Done,
                'icon' => 'fas fa-handshake',
                'action_url' => route('ak01.index', $dataSertifikasi->id_data_sertifikasi_asesi),
                'action_label' => 'Verifikasi'
            ];

            // 6. Asesmen (AK-05) - Simplified for timeline
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
                'is_completed' => $hasAk05,
                'icon' => 'far fa-edit',
                'sub_items' => $subItems,
                'action_url' => route('admin.asesor.assessment.detail', ['id_asesor' => $asesor->id_asesor, 'id_data_sertifikasi_asesi' => $dataSertifikasi->id_data_sertifikasi_asesi]),
                'action_label' => 'Lihat Detail Asesmen'
            ];

            // 7. Keputusan Asesmen (AK-02)
            $isFinalized = ($level >= 100);
            $timelineData[] = [
                'title' => 'Keputusan Asesmen (AK.02)',
                'date' => $isFinalized ? 'Final' : '-',
                'status_text' => $isFinalized ? 'Selesai' : 'Menunggu',
                'is_completed' => $isFinalized,
                'icon' => 'fas fa-gavel',
                'action_url' => route('asesor.ak02.edit', $dataSertifikasi->id_data_sertifikasi_asesi),
                'action_label' => 'Isi Keputusan'
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

    /**
     * Helper to show tracker by Data Sertifikasi ID (used in Master View for Asesor Profile)
     */
    public function showTrackerBySertifikasi($id_data_sertifikasi_asesi)
    {
        $sertifikasi = DataSertifikasiAsesi::findOrFail($id_data_sertifikasi_asesi);
        $id_asesor = $sertifikasi->jadwal->id_asesor;
        
        return $this->showTracker($id_asesor, $id_data_sertifikasi_asesi);
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
            'responBuktiAk01',
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

    // ==========================================================
    // AJAX FILE HANDLING (BUKTI & TTD)
    // ==========================================================
    public function storeBukti(Request $request, $id_asesor)
    {
        $request->validate([
            'file' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'jenis_dokumen' => 'required|string',
            'keterangan' => 'nullable|string' 
        ]);

        $asesor = Asesor::findOrFail($id_asesor);
        $key = $request->jenis_dokumen; // e.g., 'ktp', 'cv'

        // Check valid keys
        $validKeys = ['ktp', 'pas_foto', 'NPWP_foto', 'rekening_foto', 'CV', 'ijazah', 'sertifikat_asesor', 'sertifikasi_kompetensi'];
        if (!in_array($key, $validKeys)) {
            return response()->json(['success' => false, 'message' => 'Jenis dokumen tidak valid.']);
        }

        // Handle File Delete (Old)
        if ($asesor->$key && Storage::disk('private_docs')->exists($asesor->$key)) {
            Storage::disk('private_docs')->delete($asesor->$key);
        }

        // Handle File Upload (New)
        $uploadPath = 'asesor_docs/' . $asesor->id_user;
        $path = $request->file('file')->store($uploadPath, 'private_docs');

        // Update DB
        $asesor->$key = $path;
        $asesor->save();

        return response()->json(['success' => true, 'message' => 'Berhasil diunggah!']);
    }

    public function deleteBukti($id_asesor, $jenis_dokumen)
    {
        $asesor = Asesor::findOrFail($id_asesor);
        $key = $jenis_dokumen;

        // Check valid keys
        $validKeys = ['ktp', 'pas_foto', 'NPWP_foto', 'rekening_foto', 'CV', 'ijazah', 'sertifikat_asesor', 'sertifikasi_kompetensi'];
        if (!in_array($key, $validKeys)) {
            return response()->json(['success' => false, 'message' => 'Jenis dokumen tidak valid.']);
        }

        if ($asesor->$key) {
            if (Storage::disk('private_docs')->exists($asesor->$key)) {
                Storage::disk('private_docs')->delete($asesor->$key);
            }
            $asesor->$key = null;
            $asesor->save();
        }

        return response()->json(['success' => true, 'message' => 'Dokumen berhasil dihapus.']);
    }

    public function storeTtd(Request $request, $id_asesor)
    {
        $request->validate([
            'file_ttd' => 'required|file|mimes:png,jpg,jpeg|max:5120',
        ]);

        $asesor = Asesor::findOrFail($id_asesor);

        // Delete Old TTD
        if ($asesor->tanda_tangan && Storage::disk('private_docs')->exists($asesor->tanda_tangan)) {
            Storage::disk('private_docs')->delete($asesor->tanda_tangan);
        }

        // Upload New TTD
        $uploadPath = 'asesor_docs/' . $asesor->id_user;
        $path = $request->file('file_ttd')->store($uploadPath, 'private_docs');

        $asesor->tanda_tangan = $path;
        $asesor->save();

        return response()->json(['success' => true, 'message' => 'Tanda tangan berhasil disimpan.']);
    }

    public function deleteTtd($id_asesor)
    {
        $asesor = Asesor::findOrFail($id_asesor);

        if ($asesor->tanda_tangan) {
            if (Storage::disk('private_docs')->exists($asesor->tanda_tangan)) {
                Storage::disk('private_docs')->delete($asesor->tanda_tangan);
            }
            $asesor->tanda_tangan = null;
            $asesor->save();
        }

        return response()->json(['success' => true, 'message' => 'Tanda tangan berhasil dihapus.']);
    }

    // ==========================================================
    // QUICK VERIFICATION (AJAX)
    // ==========================================================
    public function verifyDocument(Request $request, $id_asesor)
    {
        $request->validate([
            'id_data_sertifikasi_asesi' => 'required|exists:data_sertifikasi_asesi,id_data_sertifikasi_asesi',
            'document_id' => 'required|string',
            'action' => 'required|in:verify,reject'
        ]);

        $dataSertifikasi = DataSertifikasiAsesi::findOrFail($request->id_data_sertifikasi_asesi);
        // Fix: Use correct ENUM values from migration (tidak diterima vs ditolak)
        $status = $request->action === 'verify' ? 'diterima' : 'tidak diterima';

        // Logic based on Document ID
        switch ($request->document_id) {
            case 'APL01':
                $dataSertifikasi->rekomendasi_apl01 = $status;
                break;
            case 'MAPA01':
                $dataSertifikasi->rekomendasi_mapa01 = $status;
                break;
            case 'MAPA02':
                $dataSertifikasi->rekomendasi_mapa02 = $status;
                break;
            case 'AK01':
                $dataSertifikasi->rekomendasi_ak01 = $status;
                break;
            case 'IA02':
                $dataSertifikasi->rekomendasi_ia02 = $status;
                break;
            case 'IA05':
                $dataSertifikasi->rekomendasi_ia05 = $status;
                break;
            case 'IA06':
                $dataSertifikasi->rekomendasi_ia06 = $status;
                break;
            case 'IA07':
                $dataSertifikasi->rekomendasi_ia07 = $status;
                break;
            case 'IA10':
                $dataSertifikasi->rekomendasi_ia10 = $status;
                break;
            case 'APL02':
                $dataSertifikasi->rekomendasi_apl02 = $status;
                break;
            default:
                return response()->json(['success' => false, 'message' => 'Dokumen tidak dapat diverifikasi secara instan atau ID salah.'], 400);
        }

        $dataSertifikasi->save();

        return response()->json([
            'success' => true, 
            'message' => 'Status dokumen berhasil diperbarui.',
            'new_status' => $status,
            'new_label' => $status === 'diterima' ? 'Diterima' : 'Ditolak' // Frontend might expect 'Ditolak' label even if DB is 'tidak diterima'
        ]);
    }
}
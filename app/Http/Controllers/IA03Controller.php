<?php

namespace App\Http\Controllers;

use App\Models\IA03;
use App\Models\UmpanBalikIA03;
use Illuminate\Http\Request;
use App\Models\DataSertifikasiAsesi;
use Illuminate\Support\Facades\Auth;

class IA03Controller extends Controller
{
    private function getPanduanAsesor()
    {
        return [
            'Formulir ini di isi oleh asesor kompetensi dapat sebelum, pada saat atau setelah melakukan asesmen dengan metode observasi demonstrasi.',
            'Pertanyaan dibuat dengan tujuan untuk menggali, dapat berisi pertanyaan yang berkaitan dengan dimensi kompetensi, batasan variabel dan aspek kritis yang relevan dengan skenario tugas dan praktik demonstrasi.',
            'Jika pertanyaan disampaikan sebelum asesi melakukan praktik demonstrasi, maka pertanyaan dibuat berkaitan dengan aspek K3L, SOP, penggunaan peralatan dan perlengkapan.',
            'Jika setelah asesi melakukan praktik demonstrasi terdapat item pertanyaan pendukung observasi telah terpenuhi, maka pertanyaan tersebut tidak perlu ditanyakan lagi dan cukup memberi catatan bahwa sudah terpenuhi pada saat tugas praktek demonstrasi pada kolom tanggapan.',
            'Jika pada saat observasi ada hal yang perlu dikonfirmasi sedangkan di instrumen daftar pertanyaan pendukung observasi tidak ada, maka asesor dapat memberikan pertanyaan dengan syarat pertanyaan harus berkaitan dengan tugas praktek demonstrasi. Jika dilakukan, asesor harus mencatat dalam instrumen pertanyaan pendukung observasi.',
            'Tanggapan asesi ditulis pada kolom tanggapan.'
        ];
    }

    /**
     * ASESOR VIEW - Menampilkan dan mengedit IA03
     */
    public function asesorIndex($id_data_sertifikasi_asesi)
    {
        $user = Auth::user();
        if (!in_array($user->role->nama_role, ['asesor', 'admin', 'superadmin'])) {
            abort(403, 'Unauthorized access');
        }

        $sertifikasi = DataSertifikasiAsesi::with([
            'asesi',
            'jadwal',
            'jadwal.asesor',
            'jadwal.skema',
            'jadwal.jenisTuk',
            'jadwal.masterTuk',
            'jadwal.skema.kelompokPekerjaan',
            'jadwal.skema.kelompokPekerjaan.unitKompetensi'
        ])->findOrFail($id_data_sertifikasi_asesi);

        if ($user->role->nama_role === 'asesor') {
            $asesorId = $user->asesor->id_asesor ?? null;
            $jadwalAsesorId = $sertifikasi->jadwal->id_asesor ?? null;
            
            if ($asesorId !== $jadwalAsesorId) {
                abort(403, 'Anda tidak memiliki akses ke data asesmen ini.');
            }
        }

        // 🔄 SYNC LOGIC: Ensure questions are generated from template
        $this->ensureQuestionsSynced($sertifikasi);

        $semuaPertanyaan = IA03::with('umpanBalik')
            ->where('id_data_sertifikasi_asesi', $id_data_sertifikasi_asesi)
            ->get();
        
        // ✅ TAMBAHKAN INI - Cek apakah semua pertanyaan sudah dijawab
        $isCompleted = $semuaPertanyaan->isNotEmpty() && 
                    $semuaPertanyaan->every(function($pertanyaan) {
                        return !is_null($pertanyaan->tanggapan) && 
                                $pertanyaan->tanggapan !== '' &&
                                !is_null($pertanyaan->pencapaian);
                    });
        
        $asesi = $sertifikasi->asesi;
        $asesor = $sertifikasi->asesor;
        $skema = $sertifikasi->skema;
        $jenisTuk = $sertifikasi->jenis_tuk;
        $tuk = $sertifikasi->tuk;
        $tanggal = $sertifikasi->tanggal_pelaksanaan;

        $kelompokPekerjaan = $skema?->kelompokPekerjaan ?? collect();
        $pertanyaanPerKelompok = [];
        
        $pertanyaanTanpaKelompok = $semuaPertanyaan->whereNull('id_kelompok_pekerjaan')->values();
        
        $jumlahKelompok = $kelompokPekerjaan->count();
        $indexPertanyaanNull = 0;
        
        foreach ($kelompokPekerjaan as $kelompokIndex => $kelompok) {
            $pertanyaanKelompok = $semuaPertanyaan
                ->where('id_kelompok_pekerjaan', $kelompok->id_kelompok_pekerjaan)
                ->values();
            
            if ($pertanyaanKelompok->isEmpty() && $pertanyaanTanpaKelompok->isNotEmpty()) {
                $pertanyaanPerGroup = (int) ceil($pertanyaanTanpaKelompok->count() / $jumlahKelompok);
                
                $pertanyaanKelompok = $pertanyaanTanpaKelompok
                    ->slice($indexPertanyaanNull, $pertanyaanPerGroup)
                    ->values();
                
                $indexPertanyaanNull += $pertanyaanPerGroup;
            }
            
            $pertanyaanPerKelompok[$kelompok->id_kelompok_pekerjaan] = $pertanyaanKelompok;
        }
        
        $umpanBalikList = UmpanBalikIA03::whereIn('id_ia03', $semuaPertanyaan->pluck('id_IA03'))
            ->get()
            ->pluck('umpan_balik')
            ->filter();

        $unitKompetensi = $kelompokPekerjaan->first()?->unitKompetensi ?? collect();

        $trackerUrl = $sertifikasi->jadwal?->id_jadwal 
            ? route('asesor.tracker', $id_data_sertifikasi_asesi)
            : route('asesor.dashboard');
        
        // ✅ PASTIKAN $isCompleted ada di compact()
        return view('frontend.IA_03.FR_IA_03_asesor', compact(
            'sertifikasi',
            'asesi',
            'asesor',
            'skema',
            'jenisTuk',
            'tuk',
            'tanggal',
            'kelompokPekerjaan',
            'unitKompetensi',
            'semuaPertanyaan',
            'pertanyaanPerKelompok',
            'trackerUrl',
            'umpanBalikList',
            'isCompleted' // ✅ TAMBAHKAN INI
        ));
    }

    /**
     * ASESOR UPDATE - Menyimpan perubahan dari asesor
     */
    public function asesorUpdate(Request $request, $id_data_sertifikasi_asesi)
    {
        $user = Auth::user();
        if (!in_array($user->role->nama_role, ['asesor', 'admin', 'superadmin'])) {
            abort(403, 'Unauthorized access');
        }

        $sertifikasi = DataSertifikasiAsesi::with('jadwal')->findOrFail($id_data_sertifikasi_asesi);

        if ($user->role->nama_role === 'asesor') {
            $asesorId = $user->asesor->id_asesor ?? null;
            $jadwalAsesorId = $sertifikasi->jadwal->id_asesor ?? null;
            
            if ($asesorId !== $jadwalAsesorId) {
                abort(403, 'Anda tidak memiliki akses untuk mengubah data ini.');
            }
        }

        $validated = $request->validate([
            'id_ia03' => 'required|array',
            'id_ia03.*' => 'required|exists:ia03,id_IA03',
            'pencapaian' => 'nullable|array',
            'pencapaian.*' => 'nullable|in:0,1',
            'tanggapan' => 'nullable|array',
            'tanggapan.*' => 'nullable|string',
            'umpan_balik_umum' => 'nullable|string',
            'asesor_ttd_tgl' => 'nullable|date',
        ]);

        try {
            $umpanBalikUmum = $validated['umpan_balik_umum'] ?? null;

            foreach ($validated['id_ia03'] as $index => $idIA03) {
                $ia03 = IA03::findOrFail($idIA03);

                $tanggapan = $validated['tanggapan'][$idIA03] ?? null;
                $pencapaian = $validated['pencapaian'][$idIA03] ?? null;

                // 🔒 Jika belum ada tanggapan, paksa pencapaian NULL
                if (empty(trim((string) $tanggapan))) {
                    $pencapaian = null;
                }

                $ia03->update([
                    'tanggapan' => $tanggapan,
                    'pencapaian' => $pencapaian,
                ]);

                if ($umpanBalikUmum) {
                    UmpanBalikIA03::updateOrCreate(
                        ['id_ia03' => $idIA03],
                        ['umpan_balik' => $umpanBalikUmum]
                    );
                }
            }


            if (isset($validated['asesor_ttd_tgl'])) {
                $sertifikasi->update([
                    'asesor_ttd_tgl' => $validated['asesor_ttd_tgl']
                ]);
            }

            // REDIRECT KE ROUTE YANG SAMA (PENTING!)
            return redirect()
                ->route('asesor.ia03.index', $id_data_sertifikasi_asesi)
                ->with('success', 'Data IA03 berhasil disimpan!');

        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }
    /**
     * ADMIN VIEW - Hanya menampilkan (Read Only)
     */
    public function adminIndex($id_data_sertifikasi_asesi)
    {
        $user = Auth::user();
        if (!in_array($user->role->nama_role, ['admin', 'superadmin'])) {
            abort(403, 'Unauthorized access');
        }

        $sertifikasi = DataSertifikasiAsesi::with([
            'asesi',
            'jadwal',
            'jadwal.asesor',
            'jadwal.skema',
            'jadwal.jenisTuk',
            'jadwal.masterTuk',
            'jadwal.skema.kelompokPekerjaan',
            'jadwal.skema.kelompokPekerjaan.unitKompetensi'
        ])->findOrFail($id_data_sertifikasi_asesi);

        $trackerUrl = route('admin.asesor.tracker', [
            'id_asesor' => $sertifikasi->jadwal->asesor->id_asesor ?? 0,
            'id_data_sertifikasi_asesi' => $id_data_sertifikasi_asesi
        ]);

        // 🔄 SYNC LOGIC: Ensure questions are generated for Admin too
        $this->ensureQuestionsSynced($sertifikasi);

        $pertanyaanIA03 = IA03::with('umpanBalik')
            ->where('id_data_sertifikasi_asesi', $id_data_sertifikasi_asesi)
            ->get();
        
        $catatanUmpanBalik = $pertanyaanIA03
            ->flatMap(function ($pertanyaan) {
                return $pertanyaan->umpanBalik->pluck('umpan_balik');
            })
            ->filter()
            ->map(function ($catatan) {
                return trim($catatan);
            })
            ->filter()
            ->unique();

        if ($catatanUmpanBalik->isEmpty()) {
            $catatanUmpanBalik = collect();
        }

        $asesi = $sertifikasi->asesi;
        $asesor = $sertifikasi->asesor;
        $skema = $sertifikasi->skema;
        $jenisTuk = $sertifikasi->jenis_tuk;
        $tuk = $sertifikasi->tuk;
        $tanggal = $sertifikasi->tanggal_pelaksanaan;

        $kelompokPekerjaan = $skema?->kelompokPekerjaan ?? collect();
        $unitKompetensi = $kelompokPekerjaan->first()?->unitKompetensi ?? collect();

        $backUrl = url()->previous();
        
        return view('frontend.IA_03.FR_IA_03_admin', compact(
            'sertifikasi',
            'asesi',
            'asesor',
            'skema',
            'jenisTuk',
            'tuk',
            'tanggal',
            'kelompokPekerjaan',
            'unitKompetensi',
            'pertanyaanIA03',
            'backUrl',
            'catatanUmpanBalik',
            'trackerUrl'
        ));
    }

    /**
     * ADMIN STORE - Menyimpan/update pertanyaan dari admin
     */
    public function adminStore(Request $request, $id_data_sertifikasi_asesi)
    {
        $user = Auth::user();
        if (!in_array($user->role->nama_role, ['admin', 'superadmin'])) {
            abort(403, 'Unauthorized access');
        }

        $validated = $request->validate([
            'id_ia03' => 'nullable|array',
            'id_ia03.*' => 'nullable|exists:ia03,id_IA03',
            'pertanyaan' => 'required|array',
            'pertanyaan.*' => 'required|string',
            'id_kelompok' => 'required|array',
            'id_kelompok.*' => 'required|exists:kelompok_pekerjaan,id_kelompok_pekerjaan',
        ]);

        try {
            // Ambil ID pertanyaan yang ada di form
            $idYangDikirim = array_filter($validated['id_ia03'] ?? []);
            
            // Hapus pertanyaan yang tidak ada di form (yang dihapus user)
            IA03::where('id_data_sertifikasi_asesi', $id_data_sertifikasi_asesi)
                ->whereNotIn('id_IA03', $idYangDikirim)
                ->delete();

            // Update atau insert pertanyaan
            foreach ($validated['pertanyaan'] as $index => $pertanyaan) {
                $idIA03 = $validated['id_ia03'][$index] ?? null;
                $idKelompok = $validated['id_kelompok'][$index]; // ← TAMBAHKAN INI
                
                if ($idIA03) {
                    IA03::where('id_IA03', $idIA03)->update([
                        'pertanyaan' => $pertanyaan,
                        'id_kelompok_pekerjaan' => $idKelompok, // ← TAMBAHKAN INI
                    ]);
                } else {
                    IA03::create([
                        'id_data_sertifikasi_asesi' => $id_data_sertifikasi_asesi,
                        'id_kelompok_pekerjaan' => $idKelompok, // ← TAMBAHKAN INI
                        'pertanyaan' => $pertanyaan,
                        'pencapaian' => null,
                        'tanggapan' => null,
                    ]);
                }
            }

            return redirect()
                ->route('admin.ia03.index', $id_data_sertifikasi_asesi)
                ->with('success', 'Pertanyaan IA03 berhasil disimpan!');

        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * ADMIN EDIT - Halaman edit pertanyaan (YANG INI YANG PENTING!)
     */
    public function adminEdit($id_data_sertifikasi_asesi)
    {
        $user = Auth::user();
        if (!in_array($user->role->nama_role, ['admin', 'superadmin'])) {
            abort(403, 'Unauthorized access');
        }

        $sertifikasi = DataSertifikasiAsesi::with([
            'asesi',
            'jadwal',
            'jadwal.asesor',
            'jadwal.skema',
            'jadwal.skema.kelompokPekerjaan.unitKompetensi'
        ])->findOrFail($id_data_sertifikasi_asesi);

        // LOAD ULANG pertanyaan dari database setelah simpan
        $pertanyaanIA03 = IA03::where('id_data_sertifikasi_asesi', $id_data_sertifikasi_asesi)
            ->get();
        
        $kelompokPekerjaan = $sertifikasi->jadwal->skema->kelompokPekerjaan ?? collect();
        
        // Data tambahan untuk header
        $asesi = $sertifikasi->asesi;
        $asesor = $sertifikasi->jadwal->asesor ?? null;
        $skema = $sertifikasi->jadwal->skema ?? null;
        
        $backUrl = route('admin.asesor.tracker', [
            'id_asesor' => $asesor->id_asesor ?? 0,
            'id_data_sertifikasi_asesi' => $id_data_sertifikasi_asesi
        ]);
        
        return view('admin.ia03.edit', compact(
            'sertifikasi',
            'pertanyaanIA03',
            'kelompokPekerjaan',
            'id_data_sertifikasi_asesi',
            'asesi',
            'asesor',
            'skema',
            'backUrl'
        ));
    }

    /**
     * ADMIN UPDATE - Update pertanyaan yang sudah ada
     */
    public function adminUpdate(Request $request, $id_data_sertifikasi_asesi)
    {
        $user = Auth::user();
        if (!in_array($user->role->nama_role, ['admin', 'superadmin'])) {
            abort(403, 'Unauthorized access');
        }

        $validated = $request->validate([
            'id_ia03' => 'nullable|array',
            'id_ia03.*' => 'nullable|exists:ia03,id_IA03',
            'pertanyaan' => 'required|array',
            'pertanyaan.*' => 'required|string',
            'id_kelompok' => 'required|array',
            'id_kelompok.*' => 'nullable|exists:kelompok_pekerjaan,id_kelompok_pekerjaan',
        ]);


        try {
            // Update pertanyaan yang sudah ada
            if (isset($validated['id_ia03'])) {
                foreach ($validated['id_ia03'] as $index => $idIA03) {
                    if ($idIA03) {
                        $ia03 = IA03::find($idIA03);
                        if ($ia03) {
                            $ia03->update([
                                'pertanyaan' => $validated['pertanyaan'][$index],
                            ]);
                        }
                    }
                }
            }

            // Tambah pertanyaan baru (yang tidak punya ID)
            foreach ($validated['pertanyaan'] as $index => $pertanyaan) {
                if (empty($validated['id_ia03'][$index])) {
                   IA03::create([
                        'id_data_sertifikasi_asesi' => $id_data_sertifikasi_asesi,
                        'id_kelompok_pekerjaan' => $validated['id_kelompok'][$index] ?? null,
                        'pertanyaan' => $pertanyaan,
                        'pencapaian' => null,
                        'tanggapan' => null,
                    ]);
                }
            }

            return redirect()
                ->route('admin.ia03.edit', $id_data_sertifikasi_asesi)
                ->with('success', 'Pertanyaan IA03 berhasil diupdate!');

        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * ADMIN DELETE - Hapus pertanyaan
     */
    public function adminDeletePertanyaan($id_ia03)
    {
        $user = Auth::user();
        if (!in_array($user->role->nama_role, ['admin', 'superadmin'])) {
            abort(403, 'Unauthorized access');
        }

        try {
            $pertanyaan = IA03::findOrFail($id_ia03);
            $pertanyaan->delete();

            return redirect()
                ->back()
                ->with('success', 'Pertanyaan berhasil dihapus!');

        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
 * ADMIN VIEW JAWABAN - Melihat halaman asesor tapi sebagai admin (Read-Only)
 */
    public function adminViewJawaban($id_data_sertifikasi_asesi)
    {
        $user = Auth::user();
        if (!in_array($user->role->nama_role, ['admin', 'superadmin'])) {
            abort(403, 'Unauthorized access');
        }

        $sertifikasi = DataSertifikasiAsesi::with([
            'asesi',
            'jadwal',
            'jadwal.asesor',
            'jadwal.skema',
            'jadwal.jenisTuk',
            'jadwal.masterTuk',
            'jadwal.skema.kelompokPekerjaan',
            'jadwal.skema.kelompokPekerjaan.unitKompetensi'
        ])->findOrFail($id_data_sertifikasi_asesi);

        $semuaPertanyaan = IA03::with('umpanBalik')
            ->where('id_data_sertifikasi_asesi', $id_data_sertifikasi_asesi)
            ->get();
        
        // Cek apakah sudah completed
        $isCompleted = $semuaPertanyaan->isNotEmpty() && 
                    $semuaPertanyaan->every(function($pertanyaan) {
                        return !is_null($pertanyaan->tanggapan) && 
                                $pertanyaan->tanggapan !== '' &&
                                !is_null($pertanyaan->pencapaian);
                    });
        
        $asesi = $sertifikasi->asesi;
        $asesor = $sertifikasi->asesor;
        $skema = $sertifikasi->skema;
        $jenisTuk = $sertifikasi->jenis_tuk;
        $tuk = $sertifikasi->tuk;
        $tanggal = $sertifikasi->tanggal_pelaksanaan;

        $kelompokPekerjaan = $skema?->kelompokPekerjaan ?? collect();
        $pertanyaanPerKelompok = [];
        
        $pertanyaanTanpaKelompok = $semuaPertanyaan->whereNull('id_kelompok_pekerjaan')->values();
        
        $jumlahKelompok = $kelompokPekerjaan->count();
        $indexPertanyaanNull = 0;
        
        foreach ($kelompokPekerjaan as $kelompokIndex => $kelompok) {
            $pertanyaanKelompok = $semuaPertanyaan
                ->where('id_kelompok_pekerjaan', $kelompok->id_kelompok_pekerjaan)
                ->values();
            
            if ($pertanyaanKelompok->isEmpty() && $pertanyaanTanpaKelompok->isNotEmpty()) {
                $pertanyaanPerGroup = (int) ceil($pertanyaanTanpaKelompok->count() / $jumlahKelompok);
                
                $pertanyaanKelompok = $pertanyaanTanpaKelompok
                    ->slice($indexPertanyaanNull, $pertanyaanPerGroup)
                    ->values();
                
                $indexPertanyaanNull += $pertanyaanPerGroup;
            }
            
            $pertanyaanPerKelompok[$kelompok->id_kelompok_pekerjaan] = $pertanyaanKelompok;
        }
        
        $umpanBalikList = UmpanBalikIA03::whereIn('id_ia03', $semuaPertanyaan->pluck('id_IA03'))
            ->get()
            ->pluck('umpan_balik')
            ->filter();

        $unitKompetensi = $kelompokPekerjaan->first()?->unitKompetensi ?? collect();

        $trackerUrl = route('admin.asesor.tracker', [
            'id_asesor' => $asesor->id_asesor ?? 0,
            'id_data_sertifikasi_asesi' => $id_data_sertifikasi_asesi
        ]);
        
        // ✅ Gunakan view asesor tapi dengan flag admin
        return view('frontend.IA_03.FR_IA_03_asesor', compact(
            'sertifikasi',
            'asesi',
            'asesor',
            'skema',
            'jenisTuk',
            'tuk',
            'tanggal',
            'kelompokPekerjaan',
            'unitKompetensi',
            'semuaPertanyaan',
            'pertanyaanPerKelompok',
            'trackerUrl',
            'umpanBalikList',
            'isCompleted'
        ))->with('adminMode', true); // ✅ Flag bahwa ini mode admin
    }
    /**
     * ASESI VIEW (Jika diperlukan) - Read Only
     */
    public function asesiIndex($id_data_sertifikasi_asesi)
    {
        $user = Auth::user();
        if ($user->role->nama_role !== 'asesi') {
            abort(403, 'Unauthorized access');
        }

        $sertifikasi = DataSertifikasiAsesi::with([
            'asesi',
            'jadwal',
            'jadwal.asesor',
            'jadwal.skema',
            'jadwal.jenisTuk',
            'jadwal.masterTuk',
            'jadwal.skema.kelompokPekerjaan',
            'jadwal.skema.kelompokPekerjaan.unitKompetensi'
        ])->findOrFail($id_data_sertifikasi_asesi);

        if ($sertifikasi->id_asesi !== $user->asesi->id_asesi) {
            abort(403, 'Anda tidak memiliki akses ke data ini.');
        }

        $pertanyaanIA03 = IA03::with('umpanBalik')
            ->where('id_data_sertifikasi_asesi', $id_data_sertifikasi_asesi)
            ->get();
        
        $catatanUmpanBalik = $pertanyaanIA03
            ->flatMap(function ($pertanyaan) {
                return $pertanyaan->umpanBalik->pluck('umpan_balik');
            })
            ->filter()
            ->map(function ($catatan) {
                return trim($catatan);
            })
            ->filter()
            ->unique();

        if ($catatanUmpanBalik->isEmpty()) {
            $catatanUmpanBalik = collect();
        }

        $asesi = $sertifikasi->asesi;
        $asesor = $sertifikasi->asesor;
        $skema = $sertifikasi->skema;
        $jenisTuk = $sertifikasi->jenis_tuk;
        $tuk = $sertifikasi->tuk;
        $tanggal = $sertifikasi->tanggal_pelaksanaan;

        $kelompokPekerjaan = $skema?->kelompokPekerjaan ?? collect();
        $unitKompetensi = $kelompokPekerjaan->first()?->unitKompetensi ?? collect();

        $trackerUrl = $sertifikasi->jadwal?->id_jadwal 
            ? route('asesi.tracker', $sertifikasi->jadwal->id_jadwal)
            : route('asesi.dashboard');
        
        return view('frontend.IA_03.FR_IA_03_admin', compact(
            'sertifikasi',
            'asesi',
            'asesor',
            'skema',
            'jenisTuk',
            'tuk',
            'tanggal',
            'kelompokPekerjaan',
            'unitKompetensi',
            'pertanyaanIA03',
            'trackerUrl',
            'catatanUmpanBalik'
        ));
    }
    /**
     * [MASTER] Menampilkan editor tamplate (Bank Soal) per Skema & Jadwal
     */
    public function editTemplate($id_skema, $id_jadwal)
    {
        $skema = \App\Models\Skema::with(['kelompokPekerjaan.unitKompetensi'])->findOrFail($id_skema);
        $template = \App\Models\MasterFormTemplate::where('id_skema', $id_skema)
                                    ->where('id_jadwal', $id_jadwal)
                                    ->where('form_code', 'FR.IA.03')
                                    ->first();
        
        $questions = [];

        // Load existing template data
        if ($template && isset($template->content) && isset($template->content['questions'])) {
            $questions = $template->content['questions'];
        } else {
            // Fallback to default template (without schedule)
            $defaultTemplate = \App\Models\MasterFormTemplate::where('id_skema', $id_skema)
                                            ->whereNull('id_jadwal')
                                            ->where('form_code', 'FR.IA.03')
                                            ->first();
            $questions = $defaultTemplate ? ($defaultTemplate->content['questions'] ?? []) : [];
        }

        // Ensure support for old flat format (migration on the fly)
        // If questions is a flat array (numeric keys 0, 1, 2...), we might want to warn or just start empty for groups?
        // Since we are moving to grouped structure, if the old data was flat, it won't map to groups automatically.
        // For now, we assume we start fresh or the data is already compatible/empty.
        // If $questions is NOT keyed by group ID (which are integers), we might need logic here. 
        // But let's assume standard behavior: $questions is [group_id => [q_list]] or []

        return view('Admin.master.skema.template.ia03', [
            'skema' => $skema,
            'id_jadwal' => $id_jadwal,
            'questions' => $questions,
            'kelompokPekerjaan' => $skema->kelompokPekerjaan
        ]);
    }

    /**
     * [MASTER] Simpan/Update template per Skema & Jadwal
     */
    public function storeTemplate(Request $request, $id_skema, $id_jadwal)
    {
        $request->validate([
            'questions' => 'nullable|array',
            'questions.*' => 'nullable|array', // Group ID keys, array of strings values
            'questions.*.*' => 'nullable|string',
        ]);

        $questionsToSave = [];

        if ($request->has('questions')) {
            foreach ($request->questions as $groupId => $groupQuestions) {
                // Filter empty strings from the group's questions
                $filtered = array_values(array_filter($groupQuestions, fn($q) => !empty(trim($q))));
                if (!empty($filtered)) {
                    $questionsToSave[$groupId] = $filtered;
                }
            }
        }

        \App\Models\MasterFormTemplate::updateOrCreate(
            [
                'id_skema' => $id_skema, 
                'id_jadwal' => $id_jadwal,
                'form_code' => 'FR.IA.03'
            ],
            [
                'content' => [
                    'questions' => $questionsToSave
                ]
            ]
        );

        return redirect()->back()->with('success', 'Bank pertanyaan (template IA.03) berhasil diperbarui.');
    }

    /**
     * CETAK PDF - Generate PDF for IA.03
     */
    public function cetakPDF($id_data_sertifikasi_asesi)
    {
        $sertifikasi = DataSertifikasiAsesi::with([
            'asesi',
            'jadwal',
            'jadwal.asesor',
            'jadwal.skema',
            'jadwal.jenisTuk',
            'jadwal.masterTuk',
            'jadwal.skema.kelompokPekerjaan.unitKompetensi'
        ])->findOrFail($id_data_sertifikasi_asesi);

        $pertanyaanIA03 = IA03::with('umpanBalik')
            ->where('id_data_sertifikasi_asesi', $id_data_sertifikasi_asesi)
            ->get();

        $umpanBalikList = UmpanBalikIA03::whereIn('id_ia03', $pertanyaanIA03->pluck('id_IA03'))
            ->get()
            ->pluck('umpan_balik')
            ->filter()
            ->unique()
            ->implode(', ');
        
        // Logic Recommendations
        $allCompetent = $pertanyaanIA03->every(function ($item) {
            return $item->pencapaian === true || $item->pencapaian === 1; // 1 = Kompeten
        });
        
        // If empty questions, fallback to not competent or check if it was filled
        if ($pertanyaanIA03->isEmpty()) {
            $allCompetent = false;
        }

        $rekomendasi = $allCompetent ? 'Kompeten' : 'Belum Kompeten';
        $umpanBalik = $umpanBalikList ?: 'Tidak ada umpan balik spesifik.';

        $asesi = $sertifikasi->asesi;
        $asesor = $sertifikasi->asesor;
        $skema = $sertifikasi->jadwal->skema;
        $tuk = $sertifikasi->tuk; // Uses accessor or relation
        $tanggal = $sertifikasi->tanggal_pelaksanaan;
        
        // Collect Units
        $units = collect();
        if ($skema && $skema->kelompokPekerjaan) {
            foreach ($skema->kelompokPekerjaan as $kp) {
                if ($kp->unitKompetensi) {
                    $units = $units->merge($kp->unitKompetensi);
                }
            }
        }

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.ia_03', compact(
            'sertifikasi',
            'pertanyaanIA03',
            'units',
            'umpanBalik',
            'rekomendasi',
            'asesi',
            'asesor',
            'skema',
            'tuk',
            'tanggal'
        ));
        
        $pdf->setPaper('A4', 'portrait');

        return $pdf->stream('FR.IA.03_' . $asesi->nama_lengkap . '.pdf');
    }

    /**
     * [HELPER] Memastikan pertanyaan tersinkronisasi dari Master Template
     * Logic: Create Missing -> Update Empty -> Lock Filled
     */
    private function ensureQuestionsSynced($sertifikasi)
    {
        $id_data_sertifikasi_asesi = $sertifikasi->id_data_sertifikasi_asesi;
        
        // Ambil Template untuk Skema & Jadwal ini
        $template = \App\Models\MasterFormTemplate::where('id_skema', $sertifikasi->jadwal->id_skema)
                                    ->where('id_jadwal', $sertifikasi->jadwal->id_jadwal)
                                    ->where('form_code', 'FR.IA.03')
                                    ->first();
        
        // Fallback ke Default Template jika spesifik jadwal tidak ada
        if (!$template) {
            $template = \App\Models\MasterFormTemplate::where('id_skema', $sertifikasi->jadwal->id_skema)
                                        ->whereNull('id_jadwal')
                                        ->where('form_code', 'FR.IA.03')
                                        ->first();
        }

        if ($template && isset($template->content) && isset($template->content['questions'])) {
            $templateQuestions = $template->content['questions']; 
            
            if (is_array($templateQuestions) && !empty($templateQuestions)) {
                
                $kelompokPekerjaanList = $sertifikasi->jadwal->skema->kelompokPekerjaan;
                
                foreach ($kelompokPekerjaanList as $kelompok) {
                    $groupId = $kelompok->id_kelompok_pekerjaan;
                    
                    $questionsForGroup = $templateQuestions[$groupId] ?? [];

                    if (empty($questionsForGroup)) continue;

                    // Ambil IA03 yang SUDAH ADA untuk grup ini
                    $existingIA03 = IA03::where('id_data_sertifikasi_asesi', $id_data_sertifikasi_asesi)
                                        ->where('id_kelompok_pekerjaan', $groupId)
                                        ->orderBy('id_IA03', 'asc')
                                        ->get();
                    
                    foreach ($questionsForGroup as $index => $qText) {
                        if (empty(trim($qText))) continue;

                        if ($existingIA03->has($index)) {
                            // 🔒 DATA SUDAH ADA: Update HANYA jika belum dijawab
                            $currentRecord = $existingIA03[$index];
                            if ($currentRecord->tanggapan === null) {
                                $currentRecord->update(['pertanyaan' => $qText]);
                            }
                        } else {
                            // 🆕 DATA BELUM ADA: Create Baru
                            IA03::create([
                                'id_data_sertifikasi_asesi' => $id_data_sertifikasi_asesi,
                                'id_kelompok_pekerjaan' => $groupId,
                                'pertanyaan' => $qText,
                                'pencapaian' => null,
                                'tanggapan' => null,
                            ]);
                        }
                    }
                }
            }
        }
    }
}
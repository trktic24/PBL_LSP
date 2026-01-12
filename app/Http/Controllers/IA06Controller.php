<?php

namespace App\Http\Controllers;

use App\Models\SoalIA06;
use App\Models\JawabanIa06;
use App\Models\UmpanBalikIa06;
use App\Models\DataSertifikasiAsesi;
use App\Models\Skema;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class IA06Controller extends Controller
{
    // =======================================================================
    // BAGIAN 1: ADMIN (MANAJEMEN BANK SOAL)
    // =======================================================================

    /**
     * Menampilkan Halaman Bank Soal (Admin)
     */
    public function adminIndex(Request $request)
    {
        // $this->authorizeRole(1); // Handled by Middleware

        $skemas = Skema::all();
        $selectedSkema = $request->query('skema_id');

        $soals = [];
        if ($selectedSkema) {
            $soals = SoalIA06::where('id_skema', $selectedSkema)->get();
        }

        // Pastikan Anda punya view: resources/views/admin/bank_soal/ia06/index.blade.php
        return view('frontend.IA_06.FR_IA_06_admin', compact('skemas', 'soals', 'selectedSkema'));
    }

    /**
     * Menyimpan Soal Baru (Admin)
     */
    public function adminStoreSoal(Request $request)
    {
        // $this->authorizeRole(1);

        $request->validate([
            'id_skema' => 'required|exists:skema,id_skema',
            'soal_ia06' => 'required|string',
            'kunci_jawaban_ia06' => 'required|string',
        ]);

        SoalIA06::create($request->all());

        return back()->with('success', 'Soal berhasil ditambahkan.');
    }

    /**
     * Update Soal (Admin)
     */
    public function adminUpdateSoal(Request $request, $id)
    {
        // $this->authorizeRole(1);

        $soal = SoalIA06::findOrFail($id);
        $soal->update($request->only(['soal_ia06', 'kunci_jawaban_ia06']));

        return back()->with('success', 'Soal berhasil diperbarui.');
    }

    /**
     * Hapus Soal (Admin)
     */
    public function adminDestroySoal($id)
    {
        // $this->authorizeRole(1);
        SoalIA06::destroy($id);
        return back()->with('success', 'Soal dihapus.');
    }


    // =======================================================================
    // BAGIAN 2: ASESI (MENGERJAKAN SOAL)
    // =======================================================================

    /**
     * Menampilkan Lembar Soal (Asesi)
     */
    public function asesiShow($idSertifikasi)
    {
        $this->authorizeRole(2); // Cek apakah Asesi
        $user = Auth::user();

        // 1. Validasi Kepemilikan Data
        $sertifikasi = DataSertifikasiAsesi::with(['jadwal.skema', 'asesi'])->findOrFail($idSertifikasi);

        // Asumsi: Relasi user ke asesi menggunakan 'user_id' di tabel asesi
        if ($sertifikasi->asesi->user_id !== $user->id_user) {
            abort(403, 'Akses Ditolak. Ini bukan lembar ujian Anda.');
        }

        // 2. Generate Lembar Jawab (Jika belum ada)
        $this->generateLembarJawab($sertifikasi);

        // 3. Ambil Jawaban
        $daftar_soal = JawabanIa06::with('soal')
            ->where('id_data_sertifikasi_asesi', $idSertifikasi)
            ->get();

        $role = 2; // Kita set manual angka 2 karena ini function khusus Asesi

        // Tentukan URL tujuan submit form
        $formAction = route('asesi.ia06.update', $idSertifikasi);

        // Kita juga butuh variabel umpanBalik agar view tidak error (meski asesi cuma baca)
        $umpanBalik = UmpanBalikIa06::where('id_data_sertifikasi_asesi', $idSertifikasi)->first();

        // Panggil View UNIFIED (Satu untuk semua)
        return view('frontend.IA_06.FR_IA_06', compact('sertifikasi', 'daftar_soal', 'role', 'formAction', 'umpanBalik'));
    }

    /**
     * Menyimpan Jawaban (Asesi)
     */
    public function asesiStoreJawaban(Request $request, $idSertifikasi)
    {
        $this->authorizeRole(2);

        $request->validate([
            'jawaban' => 'required|array',
            'jawaban.*' => 'nullable|string',
        ]);

        DB::transaction(function () use ($request, $idSertifikasi) {
            foreach ($request->jawaban as $idJawaban => $teks) {
                JawabanIa06::where('id_jawaban_ia06', $idJawaban)
                    ->where('id_data_sertifikasi_asesi', $idSertifikasi) // Security check extra
                    ->update(['jawaban_asesi' => $teks]);
            }
        });

        return back()->with('success', 'Jawaban berhasil disimpan.');
    }


    // =======================================================================
    // BAGIAN 3: ASESOR (PENILAIAN)
    // =======================================================================

    /**
     * Menampilkan Form Penilaian (Asesor)
     */
    public function asesorShow($idSertifikasi)
    {
        // 1. Cek Permission (Admin & Asesor boleh masuk)
        // Asumsi: 1 = Admin, 3 = Asesor, 4 = Admin Master (sesuaikan dengan DB Anda)
        $userRoleId = Auth::user()->role_id;

        if (!in_array($userRoleId, [1, 3, 4])) {
            abort(403, 'Unauthorized Action.');
        }

        $sertifikasi = DataSertifikasiAsesi::with(['jadwal.skema', 'asesi'])->findOrFail($idSertifikasi);

        // --- TAMBAHAN BARU: Generate data jika belum ada (agar Asesor bisa lihat soal meski Asesi belum login) ---
        $this->generateLembarJawab($sertifikasi);
        // ---------------------------------------------------------------------------------------------------------

        $daftar_soal = JawabanIa06::with('soal')
            ->where('id_data_sertifikasi_asesi', $idSertifikasi)
            ->get();

        $umpanBalik = UmpanBalikIa06::where('id_data_sertifikasi_asesi', $idSertifikasi)->first();

        // ============================================================
        // [PERBAIKAN] TENTUKAN ROLE SECARA DINAMIS
        // ============================================================
        if ($userRoleId == 3) {
            $role = 3; // Mode ASESOR (Bisa Input Nilai)
        } else {
            $role = 1; // Mode ADMIN (Monitor/Read Only)
        }
        // ============================================================

        // Tentukan URL tujuan submit form (Hanya berguna jika Role 3)
        $formAction = route('asesor.ia06.update', $idSertifikasi);

        // Panggil View
        return view('frontend.IA_06.FR_IA_06', compact('sertifikasi', 'daftar_soal', 'umpanBalik', 'role', 'formAction'));
    }


    /**
     * Menyimpan Nilai & Feedback (Asesor)
     */
    public function asesorStorePenilaian(Request $request, $idSertifikasi)
    {
        $this->authorizeRole(3);

        $request->validate([
            'penilaian' => 'required|array',
            'penilaian.*' => 'required|boolean', // 1=K, 0=BK
            'umpan_balik' => 'required|string',
        ]);

        DB::transaction(function () use ($request, $idSertifikasi) {
            // 1. Simpan Nilai Per Soal
            foreach ($request->penilaian as $idJawaban => $nilai) {
                JawabanIa06::where('id_jawaban_ia06', $idJawaban)
                    ->update(['pencapaian' => $nilai]);
            }

            // 2. Simpan Umpan Balik
            UmpanBalikIa06::updateOrCreate(
                ['id_data_sertifikasi_asesi' => $idSertifikasi],
                ['umpan_balik' => $request->umpan_balik]
            );
        });

        return redirect()->route('asesor.tracker', $idSertifikasi)->with('success', 'Penilaian FR.IA.06 berhasil disimpan.');
    }

    /**
     * Sinkronisasi Ulang Pertanyaan dari Templat (Reset)
     */
    public function resetQuestions($idSertifikasi)
    {
        $userRoleId = Auth::user()->role_id;
        if (!in_array($userRoleId, [1, 3, 4])) {
            abort(403, 'Unauthorized Action.');
        }

        $sertifikasi = DataSertifikasiAsesi::findOrFail($idSertifikasi);

        // Hapus Jawaban Lama & Umpan Balik
        JawabanIa06::where('id_data_sertifikasi_asesi', $idSertifikasi)->delete();
        UmpanBalikIa06::where('id_data_sertifikasi_asesi', $idSertifikasi)->delete();

        // Generate Baru dari Templat
        $this->generateLembarJawab($sertifikasi);

        return redirect()->back()->with('success', 'Pertanyaan IA-06 berhasil disinkronkan ulang dari templat.');
    }

    public function cetakPDF($idSertifikasi)
    {
        // 1. Ambil Data Sertifikasi
        $sertifikasi = DataSertifikasiAsesi::with([
            'jadwal.skema',
            'jadwal.asesor',
            'jadwal.masterTuk',
            'asesi'
        ])->findOrFail($idSertifikasi);

        // 2. Ambil Jawaban & Soal
        $daftar_soal = JawabanIa06::with('soal')
            ->where('id_data_sertifikasi_asesi', $idSertifikasi)
            ->get();

        // 3. Ambil Umpan Balik
        $umpanBalik = UmpanBalikIa06::where('id_data_sertifikasi_asesi', $idSertifikasi)->first();

        // 4. Render PDF
        $pdf = Pdf::loadView('pdf.ia_06', [
            'sertifikasi' => $sertifikasi,
            'daftar_soal' => $daftar_soal,
            'umpanBalik' => $umpanBalik
        ]);

        $pdf->setPaper('A4', 'portrait');

        return $pdf->stream('FR_IA_06_' . $sertifikasi->asesi->nama_lengkap . '.pdf');
    }


    // =======================================================================
    // HELPER FUNCTIONS (PRIVATE)
    // =======================================================================

    /**
     * Fungsi Helper untuk proteksi Role ID manual (Backup Middleware)
     */
    private function authorizeRole($roleId)
    {
        if (Auth::user()->role_id != $roleId) {
            abort(403, 'Unauthorized Action.');
        }
    }

    /**
     * Generate Lembar Jawab Kosong Otomatis
     */
    private function generateLembarJawab($sertifikasi)
    {
        // Cek apakah jawaban sudah ada?
        $exists = JawabanIa06::where('id_data_sertifikasi_asesi', $sertifikasi->id_data_sertifikasi_asesi)->exists();

        if (!$exists) {
            // Ambil soal berdasarkan id_skema DAN id_jadwal, jika kosong ambil Master (NULL)
            $id_jadwal = $sertifikasi->id_jadwal;
            $id_skema  = $sertifikasi->jadwal->id_skema;

            $soals = SoalIA06::where('id_skema', $id_skema)
                ->where('id_jadwal', $id_jadwal)
                ->get();

            if ($soals->isEmpty()) {
                $soals = SoalIA06::where('id_skema', $id_skema)
                    ->whereNull('id_jadwal')
                    ->get();
            }

            // [STATIC FALLBACK] Jika skema tidak punya soal sama sekali (termasu Master), pakai soal statis
            if ($soals->isEmpty()) {
                $defaultSoals = [
                    ['q' => 'Jelaskan langkah-langkah dalam perencanaan kerja sesuai dengan unit kompetensi yang Anda ambil.', 'k' => 'Langkah-langkah meliputi persiapan alat, materi, dan jadwal kerja.'],
                    ['q' => 'Bagaimana Anda memastikan standar kualitas hasil kerja tetap terjaga?', 'k' => 'Dengan melakukan pengecekan mandiri dan mengikuti SOP.'],
                    ['q' => 'Apa tindakan yang Anda ambil jika ditemukan ketidaksesuaian pada produk atau proses kerja?', 'k' => 'Melakukan koreksi segera dan melaporkan kepada atasan/supervisor.'],
                ];

                foreach ($defaultSoals as $ds) {
                    SoalIA06::create([
                        'id_skema' => $id_skema,
                        'id_jadwal' => $id_jadwal,
                        'soal_ia06' => $ds['q'],
                        'kunci_jawaban_ia06' => $ds['k'],
                    ]);
                }
                // Re-fetch
                $soals = SoalIA06::where('id_skema', $id_skema)
                    ->where('id_jadwal', $id_jadwal)
                    ->get();
            }

            $dataInsert = [];
            foreach ($soals as $soal) {
                $dataInsert[] = [
                    'id_soal_ia06' => $soal->id_soal_ia06,
                    'id_data_sertifikasi_asesi' => $sertifikasi->id_data_sertifikasi_asesi,
                    'jawaban_asesi' => null,
                    'pencapaian' => null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
            JawabanIa06::insert($dataInsert);
        }
    }

    /**
     * [MASTER] Menampilkan editor tamplate (Essay) per Skema & Jadwal
     */
    public function editTemplate($id_skema, $id_jadwal)
    {
        $skema = Skema::findOrFail($id_skema);
        $semua_soal = SoalIa06::where('id_skema', $id_skema)
                                ->where('id_jadwal', $id_jadwal)
                                ->orderBy('id_soal_ia06')
                                ->get();

        return view('Admin.master.skema.template.ia06', [
            'skema' => $skema,
            'id_jadwal' => $id_jadwal,
            'semua_soal' => $semua_soal
        ]);
    }

    /**
     * [MASTER] Simpan/Update soal template per Skema & Jadwal
     */
    public function storeTemplate(Request $request, $id_skema, $id_jadwal)
    {
        $request->validate([
            'soal' => 'required|array',
            'soal.*.pertanyaan' => 'required|string',
            'soal.*.kunci' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            foreach ($request->soal as $index => $data) {
                $id_soal = $data['id'] ?? null;
                
                SoalIa06::updateOrCreate(
                    [
                        'id_soal_ia06' => $id_soal, 
                        'id_skema' => $id_skema,
                        'id_jadwal' => $id_jadwal
                    ],
                    [
                        'id_skema' => $id_skema,
                        'id_jadwal' => $id_jadwal,
                        'soal_ia06' => $data['pertanyaan'],
                        'kunci_jawaban_ia06' => $data['kunci'] ?? '',
                    ]
                );
            }
            DB::commit();
            return redirect()->back()->with('success', 'Templat Soal IA-06 berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal menyimpan templat: ' . $e->getMessage());
        }
    }

    /**
     * [MASTER] Hapus soal template
     */
    public function destroyTemplate($id_skema, $id_soal)
    {
        $soal = SoalIa06::where('id_skema', $id_skema)->findOrFail($id_soal);
        $soal->delete();

        return redirect()->back()->with('success', 'Soal berhasil dihapus.');
    }

    /**
     * Menampilkan Template Form FR.IA.06 (Admin Master View) - DEPRECATED for management
     */
    public function adminShow($id_skema)
    {
        $skema = \App\Models\Skema::with(['kelompokPekerjaan.unitKompetensi'])->findOrFail($id_skema);
        
        // Mock data sertifikasi
        $sertifikasi = new \App\Models\DataSertifikasiAsesi();
        $sertifikasi->id_data_sertifikasi_asesi = 0;
        
        $asesi = new \App\Models\Asesi(['nama_lengkap' => 'Template Master']);
        $sertifikasi->setRelation('asesi', $asesi);
        
        $jadwal = new \App\Models\Jadwal(['tanggal_pelaksanaan' => now()]);
        $jadwal->setRelation('skema', $skema);
        $jadwal->setRelation('asesor', new \App\Models\Asesor(['nama_lengkap' => 'Nama Asesor']));
        $jadwal->setRelation('jenisTuk', new \App\Models\JenisTUK(['jenis_tuk' => 'Tempat Kerja']));
        $sertifikasi->setRelation('jadwal', $jadwal);

        $this->generateLembarJawab($sertifikasi);

        $daftar_soal = JawabanIa06::with('soal')
            ->where('id_data_sertifikasi_asesi', 0)
            ->get();

        return view('frontend.IA_06.FR_IA_06', [
            'sertifikasi' => $sertifikasi,
            'daftar_soal' => $daftar_soal,
            'umpanBalik' => null,
            'role' => 1,
            'formAction' => '#',
            'isMasterView' => true,
        ]);
    }
}

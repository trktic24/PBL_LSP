<?php

namespace App\Http\Controllers;

use App\Models\Ia07;
use App\Models\MasterFormTemplate;
use App\Models\Skema;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\DataSertifikasiAsesi;
use App\Models\JenisTUK;

class IA07Controller extends Controller
{
    /**
     * Menampilkan halaman Form FR.IA.07 (khusus Asesor, menggunakan view tunggal FR_IA_07.blade.php).
     */
    public function index($idSertifikasi)
    {
        // ----------------------------------------------------
        // 1. PENGAMBILAN DATA (MENGGUNAKAN MODEL NYATA)
        // ----------------------------------------------------

        // Ambil data berdasarkan ID Sertifikasi Asesi
        $sertifikasi = DataSertifikasiAsesi::with([
            'asesi',
            'jadwal.asesor',
            'jadwal.skema.unitKompetensi',
            'jadwal.jenisTuk'
        ])->findOrFail($idSertifikasi);

        $asesi = $sertifikasi->asesi;
        $asesor = $sertifikasi->jadwal->asesor;
        $skema = $sertifikasi->jadwal->skema;
        $jadwal = $sertifikasi->jadwal;



        // Ambil data Jenis TUK untuk radio button
        $jenisTukOptions = JenisTUK::pluck('jenis_tuk', 'id_jenis_tuk');

        // Ambil Unit Kompetensi dari Skema
        $units = $skema->unitKompetensi->map(function ($unit) {
            return [
                'code' => $unit->kode_unit,
                'title' => $unit->judul_unit
            ];
        });

        // --- Handle Data Kosong (Fallbacks) ---
        if (!$asesi) {
            $asesi = (object) ['nama_lengkap' => 'Nama Asesi (DB KOSONG)'];
        }
        if (!$asesor) {
            $asesor = (object) ['nama_lengkap' => 'Nama Asesor (DB KOSONG)', 'nomor_regis' => 'MET.000.000000.2019'];
        }
        if (!$skema) {
            $skema = (object) ['nama_skema' => 'SKEMA KOSONG', 'nomor_skema' => 'N/A'];
        }

        // ----------------------------------------------------
        // 2. KEMBALIKAN KE VIEW TUNGGAL ASESOR
        // ----------------------------------------------------

        // Mengembalikan view ke file frontend/FR_IA_07.blade.php
        // Mengembalikan view ke file frontend/FR_IA_07.blade.php
        return view('frontend.FR_IA_07', compact('asesi', 'asesor', 'skema', 'units', 'jenisTukOptions', 'jadwal', 'sertifikasi'));
    }

    /**
     * Menyimpan data dari Form FR.IA.07.
     */
    public function store(Request $request)
    {
        // --- LOGIKA PENYIMPANAN DATA DI SINI ---

        // 1. Validasi
        //   - Pastikan ada input radio TUK
        //   - Pastikan tanggal terisi
        $request->validate([
            'id_jenis_tuk' => 'required',
            'tanggal_pelaksanaan' => 'required|date',
            'umpan_balik_asesi' => 'nullable|string',
            'id_data_sertifikasi_asesi' => 'required|exists:data_sertifikasi_asesi,id_data_sertifikasi_asesi',
        ]);

        // 2. Tentukan DataSertifikasiAsesi
        $idSertifikasi = $request->input('id_data_sertifikasi_asesi');
        $sertifikasi = DataSertifikasiAsesi::findOrFail($idSertifikasi);
        if (!$sertifikasi) {
            return redirect()->back()->with('error', 'Data Sertifikasi tidak ditemukan (DB Kosong).');
        }
        $idSertifikasi = $sertifikasi->id_data_sertifikasi_asesi;

        DB::beginTransaction();
        try {
            // 3. Simpan Jawaban
            //    Loop semua input yang polanya jawaban_{code}_q{num} dan keputusan_{code}_q{num}
            //    Kita ambil semua data request
            $allData = $request->all();

            // Regex untuk menangkap jawaban_CODE_qX
            $unitCodes = [];

            // Loop data untuk mencari pattern jawaban
            foreach ($allData as $key => $value) {
                if (preg_match('/^jawaban_(.+)_q(\d+)$/', $key, $matches)) {
                    $unitCode = $matches[1];
                    $questionNum = $matches[2];

                    $pertanyaan = "Pertanyaan No $questionNum Unit $unitCode"; // Atau ambil real text dari DB jika ada
                    $jawabanKey = $key;
                    $keputusanKey = "keputusan_{$unitCode}_q{$questionNum}";

                    $jawabanDiharapkan = "Lihat Kunci Jawaban"; // Placeholder

                    $keputusanVal = $request->input($keputusanKey); // K atau BK
                    $isKompeten = ($keputusanVal === 'K');

                    // Simpan ke tabel ia07
                    // Kita anggap satu record per pertanyaan
                    // Cari record lama atau buat baru
                    Ia07::updateOrCreate(
                        [
                            'id_data_sertifikasi_asesi' => $idSertifikasi,
                            'pertanyaan' => $pertanyaan, // Identifier sederhana untuk soal
                        ],
                        [
                            'jawaban_asesi' => $value, // Ringkasan jawaban asesi
                            'jawaban_diharapkan' => $jawabanDiharapkan,
                            'pencapaian' => $isKompeten ? 1 : 0,
                        ]
                    );

                    $unitCodes[$unitCode] = true;
                }
            }

            // 4. Update Umpan Balik (Jika ada kolom di tabel sertifikasi/lainnya, atau simpan di tempat lain)
            //    Model Ia07 sepertinya per-pertanyaan. Jika umpan balik global, mungkin masuk ke log activity atau field lain.
            //    Sesuai struktur yang ada, kita biarkan dulu atau simpan di salah satu record jika terpaksa.
            //    Tapi controller IA05 menyimpan umpan balik di tabel LembarJawab, di sini tabel Ia07 juga punya struktur mirip?
            //    Cek Ia07 model: fillable ['id_data_sertifikasi_asesi', 'pertanyaan', 'jawaban_asesi', 'jawaban_diharapkan', 'pencapaian']
            //    Tidak ada kolom umpan_balik khusus di Ia07.

            // 5. Update Jadwal / Jenis TUK (Opsional, karena jadwal biasanya master data)
            //    Tapi kita bisa update id_jenis_tuk di sertifikasi/jadwal jika perlu.

            DB::commit();

            return redirect()->route('ia07.cetak', $idSertifikasi)->with('success', 'Penilaian FR.IA.07 berhasil disimpan.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal menyimpan penilaian: ' . $e->getMessage());
        }
    }

    public function cetakPDF($idSertifikasi)
    {
        // 1. Ambil Data Sertifikasi Lengkap
        $sertifikasi = DataSertifikasiAsesi::with([
            'asesi',
            'jadwal.masterTuk',
            'jadwal.skema.asesor',
            'jadwal.skema.unitKompetensi' // Ambil unit untuk ditampilkan di tabel awal
        ])->findOrFail($idSertifikasi);

        // 2. Ambil Daftar Pertanyaan & Jawaban (IA07)
        // Ambil Data IA07 milik asesi ini
        $ia07 = Ia07::where('id_data_sertifikasi_asesi', $idSertifikasi)->get();

        // [AUTO-LOAD TEMPLATE & STATIC FALLBACK]
        if ($ia07->isEmpty()) {
            $template = MasterFormTemplate::where('id_skema', $sertifikasi->jadwal->id_skema)
                                        ->where('id_jadwal', $sertifikasi->id_jadwal)
                                        ->where('form_code', 'FR.IA.07')
                                        ->first();
            
            if (!$template) {
                $template = MasterFormTemplate::where('id_skema', $sertifikasi->jadwal->id_skema)
                                            ->whereNull('id_jadwal')
                                            ->where('form_code', 'FR.IA.07')
                                            ->first();
            }
            
            $defaultQuestions = [
                "Sebutkan komponen utama dari unit kompetensi yang sedang diuji.",
                "Apa yang Anda lakukan jika terjadi kendala teknis saat pelaksanaan tugas?",
                "Bagaimana cara memastikan keselamatan kerja selama proses berlangsung?"
            ];

            $questions = ($template && !empty($template->content)) ? $template->content : $defaultQuestions;

            foreach ($questions as $qText) {
                Ia07::create([
                    'id_data_sertifikasi_asesi' => $idSertifikasi,
                    'pertanyaan' => $qText,
                    'jawaban_asesi' => '',
                    'pencapaian' => null
                ]);
            }
            // Refresh collection
            $ia07 = Ia07::where('id_data_sertifikasi_asesi', $idSertifikasi)->get();
        }

        // 3. Ambil Unit Kompetensi (Fallback ke collection kosong jika null)
        $unitKompetensi = $sertifikasi->jadwal->skema->unitKompetensi ?? collect();

        // 4. Render PDF
        $pdf = Pdf::loadView('pdf.ia_07', [
            'sertifikasi' => $sertifikasi,
            'daftar_pertanyaan' => $ia07,
            'unitKompetensi' => $unitKompetensi
        ]);

        $pdf->setPaper('A4', 'portrait');

        return $pdf->stream('FR_IA_07_' . $sertifikasi->asesi->nama_lengkap . '.pdf');
    }

    /**
     * [MASTER] Menampilkan editor tamplate (Pertanyaan Lisan) per Skema & Jadwal
     */
    public function editTemplate($id_skema, $id_jadwal)
    {
        $skema = Skema::findOrFail($id_skema);
        $template = MasterFormTemplate::where('id_skema', $id_skema)
                                    ->where('id_jadwal', $id_jadwal)
                                    ->where('form_code', 'FR.IA.07')
                                    ->first();
        
        // Default values if no template exists
        $questions = $template ? $template->content : [];

        return view('Admin.master.skema.template.ia07', [
            'skema' => $skema,
            'id_jadwal' => $id_jadwal,
            'questions' => $questions
        ]);
    }

    /**
     * [MASTER] Simpan/Update template per Skema & Jadwal
     */
    public function storeTemplate(Request $request, $id_skema, $id_jadwal)
    {
        $request->validate([
            'questions' => 'required|array',
            'questions.*' => 'required|string',
        ]);

        MasterFormTemplate::updateOrCreate(
            [
                'id_skema' => $id_skema, 
                'id_jadwal' => $id_jadwal,
                'form_code' => 'FR.IA.07'
            ],
            ['content' => $request->questions]
        );

        return redirect()->back()->with('success', 'Templat IA-07 berhasil diperbarui.');
    }

    /**
     * Menampilkan Template Form FR.IA.07 (Admin Master View) - DEPRECATED for management
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

        $this->cetakPDF(0); // This will trigger default questions for ID 0 if we handle it

        $units = $skema->unitKompetensi->map(function ($unit) {
            return [
                'code' => $unit->kode_unit,
                'title' => $unit->judul_unit
            ];
        });

        return view('frontend.FR_IA_07', [
            'asesi' => $asesi,
            'asesor' => $jadwal->asesor,
            'skema' => $skema,
            'units' => $units,
            'jenisTukOptions' => \App\Models\JenisTUK::pluck('jenis_tuk', 'id_jenis_tuk'),
            'jadwal' => $jadwal,
            'sertifikasi' => $sertifikasi,
            'isMasterView' => true,
        ]);
    }
}
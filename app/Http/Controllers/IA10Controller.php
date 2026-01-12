<?php

namespace App\Http\Controllers;

use App\Models\DataSertifikasiAsesi;
use App\Models\MasterFormTemplate;
use App\Models\Skema;
use App\Models\Ia10;
use App\Models\PertanyaanIa10;
use App\Models\DetailIa10;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class IA10Controller extends Controller
{
    public function create($id_asesi)
    {
        $asesi = DataSertifikasiAsesi::with([
            'jadwal.asesor',
            'jadwal.skema',
        ])->findOrFail($id_asesi);

        $jadwal = $asesi->jadwal;

        // =====================================================
        // 1. HEADER IA10 (boleh auto-create, karena 1:1)
        // =====================================================
        $header = Ia10::firstOrCreate(
            ['id_data_sertifikasi_asesi' => $id_asesi],
            [
                'nama_pengawas' => null,
                'tempat_kerja' => null,
                'alamat' => null,
                'telepon' => null,
            ]
        );

        // =====================================================
        // 2. AMBIL TEMPLATE (SUMBER PERTANYAAN)
        // =====================================================
        $template = MasterFormTemplate::where('form_code', 'FR.IA.10')
            ->where(function ($q) use ($asesi) {
                $q->where('id_jadwal', $asesi->id_jadwal)
                    ->orWhereNull('id_jadwal');
            })
            ->where('id_skema', $asesi->jadwal->id_skema)
            ->first();

        $pertanyaanTemplate = $template?->content ?? [];

        // =====================================================
        // 3. AMBIL JAWABAN YANG SUDAH ADA
        // =====================================================
        $jawabanChecklist = PertanyaanIa10::where(
            'id_data_sertifikasi_asesi',
            $id_asesi
        )->get()->keyBy('pertanyaan');

        // =====================================================
        // 4. AMBIL ESSAY
        // =====================================================
        $essayAnswers = DetailIa10::where('id_ia10', $header->id_ia10)
            ->get()
            ->mapWithKeys(function ($item) {
                return [$item->isi_detail => $item->jawaban];
            })
            ->toArray();

        return view('frontend.FR_IA_10', [
            'asesi' => $asesi,
            'jadwal' => $jadwal,
            'header' => $header,

            // PENTING
            'pertanyaanTemplate' => $pertanyaanTemplate,
            'jawabanChecklist' => $jawabanChecklist,
            'essay_answers' => $essayAnswers,

            'user' => auth()->user(),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_data_sertifikasi_asesi' => 'required|exists:data_sertifikasi_asesi,id_data_sertifikasi_asesi',
            'supervisor_name' => 'required|string',
            'workplace' => 'required|string',
        ]);

        DB::transaction(function () use ($request) {

            // =====================================================
            // 1. HEADER
            // =====================================================
            $ia10 = Ia10::updateOrCreate(
                ['id_data_sertifikasi_asesi' => $request->id_data_sertifikasi_asesi],
                [
                    'nama_pengawas' => $request->supervisor_name,
                    'tempat_kerja' => $request->workplace,
                    'alamat' => $request->address,
                    'telepon' => $request->phone,
                ]
            );

            // =====================================================
            // 2. CHECKLIST
            // =====================================================
            if ($request->has('checklist')) {
                foreach ($request->checklist as $pertanyaan => $nilai) {

                    // nilai HARUS 1 atau 0, NULL kalau kosong
                    if (!in_array($nilai, ['1', '0'], true)) {
                        $nilai = null;
                    }

                    PertanyaanIa10::updateOrCreate(
                        [
                            'id_data_sertifikasi_asesi' => $request->id_data_sertifikasi_asesi,
                            'id_ia10' => $ia10->id_ia10,
                            'pertanyaan' => $pertanyaan,
                        ],
                        [
                            'jawaban_pilihan_iya_tidak' => $nilai,
                        ]
                    );
                }
            }

            // =====================================================
            // 3. ESSAY
            // =====================================================
            $essayMap = [
                'relation' => 'Apa hubungan Anda dengan asesi?',
                'duration' => 'Berapa lama Anda bekerja dengan asesi?',
                'proximity' => 'Seberapa dekat Anda bekerja dengan asesi di area yang dinilai?',
                'experience' => 'Apa pengalaman teknis dan / atau kualifikasi Anda di bidang yang dinilai?',
                'consistency' => 'Secara keseluruhan, apakah Anda yakin asesi melakukan sesuai standar?',
                'training_needs' => 'Identifikasi kebutuhan pelatihan lebih lanjut untuk asesi:',
                'other_comments' => 'Ada komentar lain:',
            ];

            foreach ($essayMap as $key => $label) {
                DetailIa10::updateOrCreate(
                    [
                        'id_ia10' => $ia10->id_ia10,
                        'isi_detail' => $label,
                    ],
                    [
                        'jawaban' => $request->essay[$key] ?? null,
                    ]
                );
            }
        });

        return redirect()->back()->with('success', 'FR.IA.10 berhasil disimpan.');
    }

    public function cetakPDF($id_asesi)
    {
        // 1. Ambil Data Asesi Lengkap
        $asesi = DataSertifikasiAsesi::with([
            'asesi',
            'jadwal.masterTuk',
            'jadwal.skema.asesor',
        ])->findOrFail($id_asesi);

        // 2. Ambil Header IA10
        $header_ia10 = Ia10::where('id_data_sertifikasi_asesi', $id_asesi)->first();

        // ==========================================================
        // PERBAIKAN DI SINI:
        // Gunakan logika yang sama dengan 'create' (Ambil Template Dulu)
        // ==========================================================
        
        // A. Cari Template Soal yang berlaku (Soal 5 & 6)
        $template = MasterFormTemplate::where('form_code', 'FR.IA.10')
            ->where(function ($q) use ($asesi) {
                $q->where('id_jadwal', $asesi->id_jadwal)
                    ->orWhereNull('id_jadwal');
            })
            ->where('id_skema', $asesi->jadwal->id_skema)
            ->first();

        $list_pertanyaan_template = $template?->content ?? [];

        // B. Ambil Jawaban User dari Database (Punya soal 1-6)
        $semua_jawaban = PertanyaanIa10::where('id_data_sertifikasi_asesi', $id_asesi)
            ->get()
            ->keyBy('pertanyaan'); // Biar gampang dicari pakai text pertanyaan

        // C. Gabungkan: Kita bikin daftar soal BARU khusus PDF
        // Hanya masukkan soal yang ada di Template (5 & 6), abaikan sisanya (1-4)
        $daftar_soal_untuk_pdf = collect();

        foreach ($list_pertanyaan_template as $pertanyaan_text) {
            // Cari apakah ada jawaban untuk pertanyaan ini?
            $jawaban_db = $semua_jawaban->get($pertanyaan_text);

            // Kita buat objek semu (stdClass) agar formatnya sama dengan View
            $item = new \stdClass();
            $item->pertanyaan = $pertanyaan_text;
            
            // Ambil nilainya (1, 0, atau null jika belum dijawab)
            $item->jawaban_pilihan_iya_tidak = $jawaban_db ? $jawaban_db->jawaban_pilihan_iya_tidak : null;

            $daftar_soal_untuk_pdf->push($item);
        }

        // ==========================================================
        // Selesai Logic Baru
        // ==========================================================

        // 4. Ambil Jawaban Essay (Tetap sama)
        $essay_answers = [];
        if ($header_ia10) {
            $details = DetailIa10::where('id_ia10', $header_ia10->id_ia10)->get();
            foreach ($details as $dt) {
                $essay_answers[$dt->isi_detail] = $dt->jawaban;
            }
        }

        // 5. Render PDF (Kirim variabel baru $daftar_soal_untuk_pdf)
        $pdf = Pdf::loadView('pdf.ia_10', [
            'asesi' => $asesi,
            'header' => $header_ia10,
            'daftar_soal' => $daftar_soal_untuk_pdf, // <-- Variabel ini sudah bersih
            'essay_answers' => $essay_answers
        ]);

        $pdf->setPaper('A4', 'portrait');

        return $pdf->stream('FR_IA_10_' . $asesi->asesi->nama_lengkap . '.pdf');
    }

    /**
     * [MASTER] Menampilkan editor tamplate (Klarifikasi Pihak Ketiga) per Skema & Jadwal
     */
    public function editTemplate($id_skema, $id_jadwal)
    {
        $skema = Skema::findOrFail($id_skema);
        $template = MasterFormTemplate::where('id_skema', $id_skema)
            ->where('id_jadwal', $id_jadwal)
            ->where('form_code', 'FR.IA.10')
            ->first();

        // Default values if no template exists
        $questions = $template ? $template->content : [];

        return view('Admin.master.skema.template.ia10', [
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
                'form_code' => 'FR.IA.10'
            ],
            ['content' => $request->questions]
        );

        return redirect()->back()->with('success', 'Templat IA-10 berhasil diperbarui.');
    }

    /**
     * Menampilkan Template Master View untuk IA.10 (Admin) - DEPRECATED for management
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

        $defaultQuestions = [
            "Apakah asesi melakukan pekerjaan sesuai dengan standar industri?",
            "Bagaimana sikap asesi dalam bekerja sama dengan tim?",
            "Apakah asesi selalu menggunakan alat pelindung diri (APD) dengan benar?"
        ];

        $daftar_soal = collect();
        foreach ($defaultQuestions as $idx => $qText) {
            $daftar_soal->push(new \App\Models\PertanyaanIA10([
                'id_pertanyaan_ia10' => $idx + 1,
                'pertanyaan' => $qText,
                'jawaban_pilihan_iya_tidak' => 1
            ]));
        }

        $header = new \App\Models\Ia10([
            'nama_pengawas' => 'Nama Pengawas',
            'tempat_kerja' => 'Tempat Kerja',
            'alamat' => 'Alamat Lengkap',
            'telepon' => '08123456789'
        ]);

        $user = new \stdClass();
        $user->role = 'admin';

        return view('frontend.FR_IA_10', [
            'asesi' => $sertifikasi,
            'daftar_soal' => $daftar_soal,
            'header' => $header,
            'essay_answers' => [],
            'user' => $user,
            'jadwal' => $jadwal,
            'isMasterView' => true,
        ]);
    }
}
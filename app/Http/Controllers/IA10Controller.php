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
        ])->findOrFail($id_asesi);

        $jadwal = $asesi->jadwal;

        // 1. Ambil Data Header (Jika belum ada, buat record kosong agar FK id_ia10 tersedia)
        $header_ia10 = Ia10::firstOrCreate(
            ['id_data_sertifikasi_asesi' => $id_asesi],
            [
                'nama_pengawas' => '-',
                'tempat_kerja' => '-',
                'alamat' => '-',
                'telepon' => '-',
            ]
        );

        // 2. Ambil Pertanyaan Checklist 
        $daftar_soal = PertanyaanIa10::where('id_data_sertifikasi_asesi', $id_asesi)->get();

        // [AUTO-LOAD TEMPLATE] Jika belum ada pertanyaan, ambil dari Master Template
        // [AUTO-LOAD TEMPLATE] Jika belum ada pertanyaan, pakai Master Template atau Statis
        if ($daftar_soal->isEmpty()) {
            $template = MasterFormTemplate::where('id_skema', $asesi->jadwal->id_skema)
                                        ->where('form_code', 'FR.IA.10')
                                        ->first();
            
            $defaultQuestions = [
                "Apakah Anda memiliki hubungan langsung dengan asesi dan mengobservasi kinerjanya?",
                "Apakah Anda dapat mengonfirmasi bahwa asesi telah melakukan seluruh tugas secara konsisten dan memenuhi standar?",
                "Apakah asesi bekerja sesuai dengan prosedur keselamatan kerja (K3)?",
                "Apakah Anda bersedia dihubungi untuk klarifikasi lebih lanjut mengenai verifikasi ini?"
            ];

            $questions = ($template && !empty($template->content)) ? $template->content : $defaultQuestions;

            foreach ($questions as $qText) {
                PertanyaanIa10::create([
                    'id_data_sertifikasi_asesi' => $id_asesi,
                    'id_ia10' => $header_ia10->id_ia10,
                    'pertanyaan' => $qText,
                    'jawaban_pilihan_iya_tidak' => 0 // Default 'Tidak'
                ]);
            }
            // Refresh collection
            $daftar_soal = PertanyaanIa10::where('id_data_sertifikasi_asesi', $id_asesi)->get();
        }

        // 3. Ambil Jawaban Essay (Jika ada) untuk ditampilkan kembali
        $essay_answers = [];
        if ($header_ia10) {
            $details = DetailIa10::where('id_ia10', $header_ia10->id_ia10)->get();
            foreach ($details as $dt) {
                // Kita map berdasarkan isi_detail (pertanyaannya) agar mudah dipanggil di view
                // Contoh key: 'Apa hubungan Anda dengan asesi?' => 'Saya atasan langsungnya'
                $essay_answers[$dt->isi_detail] = $dt->jawaban;
            }
        }

        // Dummy User (Sesuai kodemu)
        $user = new \stdClass();
        $user->id = 3;
        $user->role = 'admin';
        $user->name = 'Asesor Testing';

        return view('frontend.FR_IA_10', [
            'asesi' => $asesi,
            'daftar_soal' => $daftar_soal,
            'header' => $header_ia10,
            'essay_answers' => $essay_answers, // Data jawaban essay
            'user' => $user,
            "jadwal" => $jadwal
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_data_sertifikasi_asesi' => 'required',
            'supervisor_name' => 'required',
            'workplace' => 'required',
            // Validasi lain sesuai kebutuhan
        ]);

        DB::beginTransaction();
        try {
            // ---------------------------------------------------------
            // 1. SIMPAN HEADER (Tabel: ia10)
            // ---------------------------------------------------------
            $ia10 = Ia10::updateOrCreate(
                ['id_data_sertifikasi_asesi' => $request->id_data_sertifikasi_asesi],
                [
                    'nama_pengawas' => $request->supervisor_name,
                    'tempat_kerja' => $request->workplace,
                    'alamat' => $request->address ?? '-',
                    'telepon' => $request->phone ?? '-',
                ]
            );

            // ---------------------------------------------------------
            // 2. SIMPAN CHECKLIST (Tabel: pertanyaan_ia10)
            // ---------------------------------------------------------
            // Form mengirim array: checklist[id_pertanyaan] = 1 (ya) atau 0 (tidak)
            if ($request->has('checklist')) {
                foreach ($request->checklist as $id_pertanyaan => $nilai) {
                    PertanyaanIa10::where('id_pertanyaan_ia10', $id_pertanyaan)
                        ->update(['jawaban_pilihan_iya_tidak' => $nilai]);
                }
            }

            // ---------------------------------------------------------
            // 3. SIMPAN ESSAY (Tabel: detail_ia10)
            // ---------------------------------------------------------
            // Kita mapping key dari form ke pertanyaan lengkap untuk disimpan di DB
            $essay_map = [
                'relation' => 'Apa hubungan Anda dengan asesi?',
                'duration' => 'Berapa lama Anda bekerja dengan asesi?',
                'proximity' => 'Seberapa dekat Anda bekerja dengan asesi di area yang dinilai?',
                'experience' => 'Apa pengalaman teknis dan / atau kualifikasi Anda di bidang yang dinilai? (termasuk asesmen atau kualifikasi pelatihan)',
                'consistency' => 'Secara keseluruhan, apakah Anda yakin asesi melakukan sesuai standar yang diminta oleh unit kompetensi secara konsisten?',
                'training_needs' => 'Identifikasi kebutuhan pelatihan lebih lanjut untuk asesi:',
                'other_comments' => 'Ada komentar lain:'
            ];

            if ($request->has('essay')) {
                foreach ($essay_map as $key_form => $label_pertanyaan) {
                    // Ambil input dari form name="essay[relation]", dll
                    $jawaban_user = $request->input("essay.$key_form");

                    DetailIa10::updateOrCreate(
                        [
                            'id_ia10' => $ia10->id_ia10,
                            'isi_detail' => $label_pertanyaan // Kunci pencarian adalah Label Pertanyaannya
                        ],
                        [
                            'jawaban' => $jawaban_user // Nilai yang diupdate
                        ]
                    );
                }
            }

            DB::commit();
            return redirect()->back()->with('success', 'Verifikasi Pihak Ketiga Berhasil Disimpan!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi Kesalahan: ' . $e->getMessage());
        }
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

        // 3. Ambil Checklist Pertanyaan
        $daftar_soal = PertanyaanIa10::where('id_data_sertifikasi_asesi', $id_asesi)->get();

        // 4. Ambil Jawaban Essay dan Mapping kuncinya
        $essay_answers = [];
        if ($header_ia10) {
            $details = DetailIa10::where('id_ia10', $header_ia10->id_ia10)->get();
            foreach ($details as $dt) {
                // Key array = Pertanyaan, Value = Jawaban
                $essay_answers[$dt->isi_detail] = $dt->jawaban;
            }
        }

        // 5. Render PDF
        $pdf = Pdf::loadView('pdf.ia_10', [
            'asesi' => $asesi,
            'header' => $header_ia10,
            'daftar_soal' => $daftar_soal,
            'essay_answers' => $essay_answers
        ]);

        $pdf->setPaper('A4', 'portrait');

        return $pdf->stream('FR_IA_10_' . $asesi->asesi->nama_lengkap . '.pdf');
    }

    /**
     * [MASTER] Menampilkan editor tamplate (Klarifikasi Pihak Ketiga) per Skema
     */
    public function editTemplate($id_skema)
    {
        $skema = Skema::findOrFail($id_skema);
        $template = MasterFormTemplate::where('id_skema', $id_skema)
                                    ->where('form_code', 'FR.IA.10')
                                    ->first();
        
        // Default values if no template exists
        $questions = $template ? $template->content : [];

        return view('Admin.master.skema.template.ia10', [
            'skema' => $skema,
            'questions' => $questions
        ]);
    }

    /**
     * [MASTER] Simpan/Update template per Skema
     */
    public function storeTemplate(Request $request, $id_skema)
    {
        $request->validate([
            'questions' => 'required|array',
            'questions.*' => 'required|string',
        ]);

        MasterFormTemplate::updateOrCreate(
            ['id_skema' => $id_skema, 'form_code' => 'FR.IA.10'],
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
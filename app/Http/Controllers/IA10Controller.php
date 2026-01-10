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

        // 1. Ambil Data Header (Jika sudah ada)
        $header_ia10 = Ia10::where('id_data_sertifikasi_asesi', $id_asesi)->first();

        // 2. Ambil Pertanyaan Checklist 
        $daftar_soal = PertanyaanIa10::where('id_data_sertifikasi_asesi', $id_asesi)->get();

        // [AUTO-LOAD TEMPLATE] Jika belum ada pertanyaan, ambil dari Master Template
        if ($daftar_soal->isEmpty()) {
            $template = MasterFormTemplate::where('id_skema', $asesi->jadwal->id_skema)
                                        ->where('form_code', 'FR.IA.10')
                                        ->first();
            if ($template && !empty($template->content)) {
                foreach ($template->content as $qText) {
                    PertanyaanIa10::create([
                        'id_data_sertifikasi_asesi' => $id_asesi,
                        'pertanyaan' => $qText,
                        'jawaban_pilihan_iya_tidak' => 0 // Default 'Tidak'
                    ]);
                }
                // Refresh collection
                $daftar_soal = PertanyaanIa10::where('id_data_sertifikasi_asesi', $id_asesi)->get();
            }
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
        $skema = \App\Models\Skema::findOrFail($id_skema);

        $query = \App\Models\DataSertifikasiAsesi::with([
            'asesi.dataPekerjaan',
            'jadwal.skema',
            'jadwal.masterTuk',
            'jadwal.asesor',
            'responApl2Ia01',
            'responBuktiAk01',
            'lembarJawabIa05',
            'komentarAk05'
        ])->whereHas('jadwal', function($q) use ($id_skema) {
            $q->where('id_skema', $id_skema);
        });

        if (request('search')) {
            $search = request('search');
            $query->whereHas('asesi', function($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%");
            });
        }

        $pendaftar = $query->paginate(request('per_page', 10))->withQueryString();

        $user = auth()->user();
        $asesor = new \App\Models\Asesor();
        $asesor->id_asesor = 0;
        $asesor->nama_lengkap = $user ? $user->name : 'Administrator';
        $asesor->pas_foto = $user ? $user->profile_photo_path : null;
        $asesor->status_verifikasi = 'approved';
        $asesor->setRelation('skemas', collect());
        $asesor->setRelation('jadwals', collect());
        $asesor->setRelation('skema', null);

        $jadwal = new \App\Models\Jadwal([
            'tanggal_pelaksanaan' => now(),
            'waktu_mulai' => '08:00',
        ]);
        $jadwal->setRelation('skema', $skema);
        $jadwal->setRelation('masterTuk', new \App\Models\MasterTUK(['nama_lokasi' => 'Semua TUK (Filter Skema)']));

        return view('Admin.master.skema.daftar_asesi', [
            'pendaftar' => $pendaftar,
            'asesor' => $asesor,
            'jadwal' => $jadwal,
            'isMasterView' => true,
            'sortColumn' => request('sort', 'nama_lengkap'),
            'sortDirection' => request('direction', 'asc'),
            'perPage' => request('per_page', 10),
            'targetRoute' => 'fr-ia-10.create',
            'buttonLabel' => 'FR.IA.10',
            'formName' => 'Klarifikasi Bukti Pihak Ketiga',
        ]);
    }
}
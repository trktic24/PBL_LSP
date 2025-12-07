<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DataSertifikasiAsesi;
use App\Models\Ia10;
use App\Models\PertanyaanIa10;
use App\Models\DetailIa10;
use Illuminate\Support\Facades\DB;

class IA10Controller extends Controller
{
    public function create($id_asesi)
    {
        $asesi = DataSertifikasiAsesi::findOrFail($id_asesi);
        
        // 1. Ambil Data Header (Jika sudah ada)
        $header_ia10 = Ia10::where('id_data_sertifikasi_asesi', $id_asesi)->first();

        // 2. Ambil Pertanyaan Checklist 
        // NOTE: Karena struktur DB 'pertanyaan_ia10' punya kolom 'id_data_sertifikasi_asesi',
        // Saya asumsikan pertanyaan ini SUDAH DIGENERATE sebelumnya untuk asesi ini.
        $daftar_soal = PertanyaanIa10::where('id_data_sertifikasi_asesi', $id_asesi)->get();

        // 3. Ambil Jawaban Essay (Jika ada) untuk ditampilkan kembali
        $essay_answers = [];
        if ($header_ia10) {
            $details = DetailIa10::where('id_ia10', $header_ia10->id_ia10)->get();
            foreach($details as $dt) {
                // Kita map berdasarkan isi_detail (pertanyaannya) agar mudah dipanggil di view
                // Contoh key: 'Apa hubungan Anda dengan asesi?' => 'Saya atasan langsungnya'
                $essay_answers[$dt->isi_detail] = $dt->jawaban;
            }
        }

        // Dummy User (Sesuai kodemu)
        $user = new \stdClass();
        $user->id = 3; $user->role = 'admin'; $user->name = 'Asesor Testing';

        return view('frontend.FR_IA_10', [
            'asesi'         => $asesi,
            'daftar_soal'   => $daftar_soal,
            'header'        => $header_ia10,
            'essay_answers' => $essay_answers, // Data jawaban essay
            'user'          => $user
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_data_sertifikasi_asesi' => 'required',
            'supervisor_name' => 'required',
            'workplace'       => 'required',
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
                    'tempat_kerja'  => $request->workplace,
                    'alamat'        => $request->address ?? '-',
                    'telepon'       => $request->phone ?? '-',
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
                'relation'       => 'Apa hubungan Anda dengan asesi?',
                'duration'       => 'Berapa lama Anda bekerja dengan asesi?',
                'proximity'      => 'Seberapa dekat Anda bekerja dengan asesi di area yang dinilai?',
                'experience'     => 'Apa pengalaman teknis dan / atau kualifikasi Anda di bidang yang dinilai? (termasuk asesmen atau kualifikasi pelatihan)',
                'consistency'    => 'Secara keseluruhan, apakah Anda yakin asesi melakukan sesuai standar yang diminta oleh unit kompetensi secara konsisten?',
                'training_needs' => 'Identifikasi kebutuhan pelatihan lebih lanjut untuk asesi:',
                'other_comments' => 'Ada komentar lain:'
            ];

            if ($request->has('essay')) {
                foreach ($essay_map as $key_form => $label_pertanyaan) {
                    // Ambil input dari form name="essay[relation]", dll
                    $jawaban_user = $request->input("essay.$key_form");

                    DetailIa10::updateOrCreate(
                        [
                            'id_ia10'    => $ia10->id_ia10,
                            'isi_detail' => $label_pertanyaan // Kunci pencarian adalah Label Pertanyaannya
                        ],
                        [
                            'jawaban'    => $jawaban_user // Nilai yang diupdate
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
}
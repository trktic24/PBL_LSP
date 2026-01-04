<?php

namespace App\Http\Controllers\Asesi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DataSertifikasiAsesi;
use App\Models\ResponHasilAk03;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Ak03Controller extends Controller
{
    // Daftar Pertanyaan (Bisa juga diambil dari DB PoinAk03 jika sudah di-seed)
    private $pertanyaan = [
        "Saya mendapatkan penjelasan yang cukup memadai mengenai proses asesmen/uji kompetensi",
        "Saya diberikan kesempatan untuk mempelajari standar kompetensi yang akan diujikan dan menilai diri sendiri terhadap pencapaiannya",
        "Asesor memberikan kesempatan untuk mendiskusikan/ menegosiasikan metoda, instrumen dan sumber asesmen serta jadwal asesmen",
        "Asesor berusaha menggali seluruh bukti pendukung yang sesuai dengan latar belakang pelatihan dan pengalaman yang saya miliki",
        "Saya sepenuhnya diberikan kesempatan untuk mendemonstrasikan kompetensi yang saya miliki selama asesmen",
        "Saya mendapatkan penjelasan yang memadai mengenai keputusan asesmen",
        "Asesor memberikan umpan balik yang mendukung setelah asesmen serta tindak lanjutnya",
        "Asesor bersama saya mempelajari semua dokumen asesmen serta menandatanganinya",
        "Saya mendapatkan jaminan kerahasiaan hasil asesmen serta penjelasan penanganan dokumen asesmen",
        "Asesor menggunakan keterampilan komunikasi yang efektif selama asesmen"
    ];

    public function create($id_sertifikasi)
    {
        $asesi_id = Auth::user()->asesi->id_asesi;

        // Ambil data sertifikasi
        $sertifikasi = DataSertifikasiAsesi::with(['jadwal.skema', 'jadwal.asesor', 'asesi'])
            ->where('id_asesi', $asesi_id)
            ->findOrFail($id_sertifikasi);

        // Ambil jawaban yang sudah pernah diisi (Mapping berdasarkan urutan/index pertanyaan)
        // Kita asumsikan id_poin_ak03 = index + 1
        $jawaban = ResponHasilAk03::where('id_data_sertifikasi_asesi', $id_sertifikasi)
            ->get()
            ->keyBy('id_poin_ak03');

        return view('frontend.AK_03.FR_AK_03', [
            'sertifikasi' => $sertifikasi,
            'questions' => $this->pertanyaan,
            'jawaban' => $jawaban
        ]);
    }

    public function store(Request $request, $id_sertifikasi)
    {
        // Validasi
        $request->validate([
            'umpan_balik' => 'required|array',
            'komentar_lain' => 'nullable|string'
        ]);

        DB::beginTransaction();
        try {
            // 1. Simpan Jawaban Per Poin (Looping)
            foreach ($this->pertanyaan as $index => $soal) {
                // Index di array mulai dari 0, tapi ID poin di DB biasanya mulai dari 1
                $idPoin = $index + 1; 
                
                $hasil = $request->umpan_balik[$index] ?? null; // ya/tidak
                $catatan = $request->catatan[$index] ?? null;

                if ($hasil) {
                    ResponHasilAk03::updateOrCreate(
                        [
                            'id_data_sertifikasi_asesi' => $id_sertifikasi,
                            'id_poin_ak03' => $idPoin
                        ],
                        [
                            'hasil' => $hasil, // pastikan kolom di DB tipe enum/varchar ('ya','tidak')
                            'catatan' => $catatan
                        ]
                    );
                }
            }

            // 2. Simpan Komentar Umum ke Tabel Induk
            $sertifikasi = DataSertifikasiAsesi::find($id_sertifikasi);
            $sertifikasi->catatan_asesi_AK03 = $request->komentar_lain;
            // $sertifikasi->status_sertifikasi = 'umpan_balik_selesai'; // Uncomment jika ingin update status
            $sertifikasi->save();

            DB::commit();
            return redirect()->route('tracker')->with('success', 'Umpan Balik (AK-03) berhasil disimpan.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
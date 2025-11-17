<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\SoalIA05;
use App\Models\KunciJawabanIA05;
use App\Models\LembarJawabIA05;
use App\Models\DataSertifikasiAsesi;

class IA05Controller extends Controller
{
    /**
     * =================================================================
     * FORM A: SOAL (untuk Admin) & LEMBAR JAWAB (untuk Asesi)
     * =================================================================
     */
    public function showSoalForm(Request $request, $id_asesi)
    {
        $user = Auth::user();
        $asesi = DataSertifikasiAsesi::findOrFail($id_asesi);
        $semua_soal = SoalIA05::orderBy('id_soal_ia05')->get();

        $data_jawaban_asesi = collect();
        if ($user->role == 'asesi' || $user->role == 'asesor') {
            // UBAH: Ambil 'teks_jawaban_asesi_ia05'
            $data_jawaban_asesi = LembarJawabIA05::where('id_data_sertifikasi_asesi', $id_asesi)
                                            ->pluck('teks_jawaban_asesi_ia05', 'id_soal_ia05');
        }

        return view('frontend.fr_IA_05_A', [
            'user' => $user,
            'asesi' => $asesi,
            'semua_soal' => $semua_soal,
            'data_jawaban_asesi' => $data_jawaban_asesi,
        ]);
    }

    /**
     * [KHUSUS ADMIN] Menyimpan data Soal (Form A)
     */
    public function storeSoal(Request $request)
    {
        $request->validate(['soal' => 'required|array']);

        DB::beginTransaction();
        try {
            foreach ($request->soal as $id_soal => $data) {
                SoalIA05::updateOrCreate(
                    ['id_soal_ia05' => $id_soal],
                    [ // UBAH: Sesuaikan nama kolom
                        'soal_ia05' => $data['pertanyaan'],
                        'opsi_jawaban_a' => $data['opsi_a'],
                        'opsi_jawaban_b' => $data['opsi_b'],
                        'opsi_jawaban_c' => $data['opsi_c'],
                        'opsi_jawaban_d' => $data['opsi_d'] ?? null,
                    ]
                );
            }
            DB::commit();
            return redirect()->back()->with('success', 'Bank Soal (IA-05 A) berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal menyimpan soal: ' . $e->getMessage());
        }
    }

    /**
     * [KHUSUS ASESI] Menyimpan jawaban Asesi (Form A)
     */
    public function storeJawabanAsesi(Request $request, $id_asesi)
    {
        $request->validate(['jawaban' => 'required|array']);

        DB::beginTransaction();
        try {
            foreach ($request->jawaban as $id_soal => $pilihan_jawaban) {
                // (Asumsi) 'jawaban' ('A'/'B'/'C') disimpan di 'teks_jawaban_asesi_ia05'
                LembarJawabIA05::updateOrCreate(
                    [
                        'id_data_sertifikasi_asesi' => $id_asesi,
                        'id_soal_ia05' => $id_soal,
                    ],
                    [ // UBAH: Sesuaikan nama kolom
                        'teks_jawaban_asesi_ia05' => $pilihan_jawaban,
                        'pencapaian_ia05_iya' => 0, // Reset penilaian
                        'pencapaian_ia05_tidak' => 0, // Reset penilaian
                    ]
                );
            }
            DB::commit();
            return redirect()->back()->with('success', 'Jawaban Anda berhasil disimpan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal menyimpan jawaban: ' . $e->getMessage());
        }
    }

    /**
     * =================================================================
     * FORM B: KUNCI JAWABAN (Admin & Asesor)
     * =================================================================
     */
    public function showKunciForm(Request $request)
    {
        $user = Auth::user();
        $semua_soal = SoalIA05::orderBy('id_soal_ia05')->get();
        
        // UBAH: Ambil 'teks_kunci_jawaban_ia05'
        $kunci_jawaban = KunciJawabanIA05::pluck('teks_kunci_jawaban_ia05', 'id_soal_ia05'); 

        return view('frontend.fr_IA_05_B', [
            'user' => $user,
            'semua_soal' => $semua_soal,
            'kunci_jawaban' => $kunci_jawaban,
        ]);
    }

    /**
     * [KHUSUS ADMIN] Menyimpan Kunci Jawaban (Form B)
     */
    public function storeKunci(Request $request)
    {
        // Asumsi: Form mengirim array 'kunci[id_soal]' => 'A. Teks Jawaban'
        $request->validate(['kunci' => 'required|array']);

        DB::beginTransaction();
        try {
            foreach ($request->kunci as $id_soal => $teks_kunci) {
                KunciJawabanIA05::updateOrCreate(
                    ['id_soal_ia05' => $id_soal],
                    [ // UBAH: Sesuaikan nama kolom
                        'teks_kunci_jawaban_ia05' => $teks_kunci,
                        // 'nomor_kunci_jawaban_ia05' => $nomor (jika Anda kirim nomor)
                    ]
                );
            }
            DB::commit();
            return redirect()->back()->with('success', 'Kunci Jawaban (IA-05 B) berhasil disimpan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal menyimpan kunci jawaban: ' . $e->getMessage());
        }
    }

    /**
     * =================================================================
     * FORM C: HASIL JAWABAN & PENILAIAN (Admin & Asesor)
     * =================================================================
     */
    public function showJawabanForm(Request $request, $id_asesi)
    {
        $user = Auth::user();
        $asesi = DataSertifikasiAsesi::findOrFail($id_asesi);
        $semua_soal = SoalIA05::orderBy('id_soal_ia05')->get();
        
        $kunci_jawaban = KunciJawabanIA05::pluck('teks_kunci_jawaban_ia05', 'id_soal_ia05');
        
        // Ambil semua data lembar jawab (termasuk pencapaian_iya/tidak)
        $lembar_jawab = LembarJawabIA05::where('id_data_sertifikasi_asesi', $id_asesi)
                                        ->get()
                                        ->keyBy('id_soal_ia05');

        return view('frontend.fr_IA_05_C', [
            'user' => $user,
            'asesi' => $asesi,
            'semua_soal' => $semua_soal,
            'kunci_jawaban' => $kunci_jawaban,
            'lembar_jawab' => $lembar_jawab,
        ]);
    }

    /**
     * [KHUSUS ASESOR] Menyimpan Penilaian Asesor (Form C)
     * UBAH: Logika ini diubah total untuk menyimpan Ya/Tidak
     */
    public function storePenilaianAsesor(Request $request, $id_asesi)
    {
        // Asumsi: Form mengirim 'penilaian[id_soal]' => 'ya' atau 'tidak'
        $request->validate([
            'penilaian' => 'required|array',
            'penilaian.*' => 'required|in:ya,tidak',
            // 'umpan_balik' => 'nullable|string' // (Lihat catatan di Blade C)
        ]);

        DB::beginTransaction();
        try {
            foreach ($request->penilaian as $id_soal => $hasil_penilaian) {
                $jawaban = LembarJawabIA05::where('id_data_sertifikasi_asesi', $id_asesi)
                                         ->where('id_soal_ia05', $id_soal)
                                         ->first();

                if ($jawaban) {
                    $jawaban->update([
                        // UBAH: Terjemahkan 'ya'/'tidak' ke 1/0
                        'pencapaian_ia05_iya' => ($hasil_penilaian == 'ya') ? 1 : 0,
                        'pencapaian_ia05_tidak' => ($hasil_penilaian == 'tidak') ? 1 : 0,
                    ]);
                }
            }
            DB::commit();
            return redirect()->back()->with('success', 'Penilaian (IA-05 C) berhasil disimpan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal menyimpan penilaian: ' . $e->getMessage());
        }
    }
}
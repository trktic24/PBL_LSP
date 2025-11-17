<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\KelompokPekerjaan;
use App\Models\UnitKompetensi;
use App\Models\ResponApl02Ia01;
use Illuminate\Support\Facades\Auth;

class Ia01Controller extends Controller
{
    /**
     * HALAMAN 1: COVER / HEADER DATA
     * Cuma nampilin form data diri, asesor, TUK, tanggal.
     */
    public function showCover(Request $request, $skema_id)
    {
        $kelompok = KelompokPekerjaan::findOrFail($skema_id);

        // Ambil data sesi kalo user pernah ngisi dan klik back
        $data_sesi = $request->session()->get("ia01.skema_{$skema_id}", []);

        // Kita kirim variable $unitKompetensi pertama cuma buat ambil data Skema/Judul
        // Tapi gak dipake buat load KUK
        $unitKompetensi = $kelompok->unitKompetensis()->orderBy('urutan')->first();

        return view('frontend.IA_01.tampilan_awal', compact('kelompok', 'unitKompetensi', 'data_sesi'));
    }

    /**
     * SIMPAN DATA COVER
     */
    public function storeCover(Request $request, $skema_id)
    {
        $validatedData = $request->validate([
            'nama_asesor' => 'required|string|max:255',
            'tuk' => 'required|in:sewaktu,tempat_kerja,mandiri',
            'tanggal_asesmen' => 'required|date',
        ], [
            'nama_asesor.required' => 'Nama Asesor wajib diisi.',
            'tuk.required' => 'Jenis TUK wajib dipilih.',
            'tanggal_asesmen.required' => 'Tanggal Asesmen wajib diisi.',
        ]);

        // Simpan ke Session
        $sessionKey = "ia01.skema_{$skema_id}";
        $oldData = $request->session()->get($sessionKey, []);
        $newData = array_merge($oldData, $validatedData);
        $request->session()->put($sessionKey, $newData);

        // Redirect ke Step 1 (Unit Pertama)
        return redirect()->route('ia01.showStep', ['skema_id' => $skema_id, 'urutan' => 1]);
    }

    /**
     * HALAMAN 2 dst: FORM KUK (Unit 1, Unit 2, ...)
     * Ini dipake berulang untuk semua unit.
     */
    public function showStep(Request $request, $skema_id, $urutan)
    {
        $kelompok = KelompokPekerjaan::findOrFail($skema_id);
        $unitKompetensi = UnitKompetensi::where('id_kelompok_pekerjaan', $skema_id)
                                        ->where('urutan', $urutan)
                                        ->firstOrFail();

        $kuks = $unitKompetensi->kriteriaUnjukKerja()->orderBy('no_kriteria')->get();
        $totalSteps = $kelompok->unitKompetensis()->count();
        $data_sesi = $request->session()->get("ia01.skema_{$skema_id}", []);

        // Cek tipe form dari KUK pertama (aktivitas/demonstrasi)
        // Biar view tau mau nampilin header 'Ya/Tidak' atau 'K/BK'
        $formType = $kuks->first()->tipe ?? 'demonstrasi';

        // Kita pake SATU VIEW AJA 'IA_01' tapi nanti tabelnya dinamis
        return view('frontend.IA_01.IA_01', compact(
            'unitKompetensi', 'kuks', 'data_sesi', 'totalSteps', 'formType'
        ));
    }

    /**
     * SIMPAN DATA STEP
     */
    public function storeStep(Request $request, $skema_id, $urutan)
    {
        $kelompok = KelompokPekerjaan::findOrFail($skema_id);
        $unitKompetensi = UnitKompetensi::where('id_kelompok_pekerjaan', $skema_id)
                                        ->where('urutan', $urutan)
                                        ->firstOrFail();

        // Validasi KUK
        $kukIds = $unitKompetensi->kriteriaUnjukKerja()->pluck('id_kriteria')->toArray();
        $validationRules['kuk'] = 'required|array';
        foreach ($kukIds as $id) {
            $validationRules["kuk.{$id}"] = ['required', Rule::in(['1', '0', 1, 0])];
        }

        $validatedData = $request->validate($validationRules, [
            'kuk.*.required' => 'Semua poin penilaian harus diisi.',
        ]);

        // Merge Session
        $sessionKey = "ia01.skema_{$skema_id}";
        $oldData = $request->session()->get($sessionKey, []);

        $oldKuks = $oldData['kuk'] ?? [];
        $newKuks = $validatedData['kuk'] ?? [];
        $validatedData['kuk'] = $oldKuks + $newKuks;

        if ($request->has('penilaian_lanjut')) {
             $validatedData['penilaian_lanjut'] = $request->input('penilaian_lanjut');
        }

        $newData = array_merge($oldData, $validatedData);
        $request->session()->put($sessionKey, $newData);

        // Logic Next Step / Finish
        $totalSteps = $kelompok->unitKompetensis()->count();

        if ($urutan < $totalSteps) {
            return redirect()->route('ia01.showStep', ['skema_id' => $skema_id, 'urutan' => $urutan + 1]);
        } else {
            // --- FINAL SAVE ---
            $finalData = $request->session()->get($sessionKey);
            $idDataSertifikasi = 1; // TODO: Ganti Auth dynamic

            try {
                foreach ($finalData['kuk'] as $kukId => $hasil) {
                    ResponApl02Ia01::updateOrCreate(
                        ['id_data_sertifikasi_asesi' => $idDataSertifikasi, 'id_kriteria' => $kukId],
                        [
                            'pencapaian_ia01' => $hasil,
                            'penilaian_lanjut_ia01' => $finalData['penilaian_lanjut'] ?? null,
                        ]
                    );
                }
                $request->session()->forget($sessionKey);
                return redirect()->route('home')->with('success', 'Asesmen IA.01 Selesai!');
            } catch (\Exception $e) {
                return back()->with('error', $e->getMessage());
            }
        }
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\KelompokPekerjaan;
use App\Models\UnitKompetensi;
use App\Models\ResponApl02Ia01;
use Illuminate\Support\Facades\Auth;

class IA01Controller extends Controller
{
    /**
     * HALAMAN 1: COVER / HEADER DATA
     * Cuma nampilin form data diri, asesor, TUK, tanggal.
     */
    public function showCover(Request $request, $skema_id)
    {
        $kelompok = KelompokPekerjaan::findOrFail($skema_id);
        $data_sesi = $request->session()->get("ia01.skema_{$skema_id}", []);
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
        $unitKompetensi = UnitKompetensi::with(['elemens.kriteriaUnjukKerja'])
                                        ->where('id_kelompok_pekerjaan', $skema_id)
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

        // Ambil semua ID KUK di unit ini buat validasi
        // Pake kriteriaUnjukKerja() (HasManyThrough) biar ringkas
        $kukIds = $unitKompetensi->kriteriaUnjukKerja()->pluck('id_kriteria')->toArray();

        // 2. SINKRONISASI NAMA INPUT: Ganti 'kuk' jadi 'hasil', 'standar_industri', dll
        $validationRules = [
            'hasil' => 'required|array',
            'standar_industri' => 'nullable|array',
            'penilaian_lanjut' => 'nullable|array',
        ];

        foreach ($kukIds as $id) {
            // Frontend ngirim value "kompeten" atau "belum_kompeten"
            $validationRules["hasil.{$id}"] = ['required', Rule::in(['kompeten', 'belum_kompeten'])];
            $validationRules["standar_industri.{$id}"] = ['nullable', 'string'];
            $validationRules["penilaian_lanjut.{$id}"] = ['nullable', 'string'];
        }

        $validatedData = $request->validate($validationRules, [
            'hasil.*.required' => 'Pencapaian (Ya/Tidak) harus dipilih.',
        ]);

        // 3. LOGIC PENYIMPANAN SESSION YANG RAPI
        $sessionKey = "ia01.skema_{$skema_id}";
        $currentSession = $request->session()->get($sessionKey, []);

        // Merge data array per KUK (biar step sebelumnya gak ilang)
        $currentSession['hasil'] = ($currentSession['hasil'] ?? []) + $validatedData['hasil'];

        // Standar Industri & Penilaian Lanjut optional, pake operator null coalescing
        if ($request->has('standar_industri')) {
            $currentSession['standar_industri'] = ($currentSession['standar_industri'] ?? []) + $request->input('standar_industri');
        }
        if ($request->has('penilaian_lanjut')) {
            $currentSession['penilaian_lanjut'] = ($currentSession['penilaian_lanjut'] ?? []) + $request->input('penilaian_lanjut');
        }

        $request->session()->put($sessionKey, $currentSession);


        // 4. LOGIC NAVIGASI NEXT / FINISH
        $totalSteps = $kelompok->unitKompetensis()->count();

        if ($urutan < $totalSteps) {
            return redirect()->route('ia01.showStep', ['skema_id' => $skema_id, 'urutan' => $urutan + 1]);
        } else {
            // --- FINAL SAVE KE DATABASE ---
            return $this->finalSaveToDatabase($request, $sessionKey);
        }
    }

    /**
     * Private function biar rapi
     */
    private function finalSaveToDatabase(Request $request, $sessionKey)
    {
        $finalData = $request->session()->get($sessionKey);

        // TODO: Ambil ID Asesi Dinamis dari Auth/Query yg bener
        // Misal: $asesi = DataSertifikasiAsesi::where('user_id', Auth::id())->first();
        $idDataSertifikasi = 1; // Hardcode dulu buat testing

        try {
            // Loop berdasarkan 'hasil' karena itu yang wajib ada
            foreach ($finalData['hasil'] as $kukId => $status) {

                // Konversi Value Blade ('kompeten') ke Boolean Database (1/0)
                $isKompeten = ($status === 'kompeten') ? 1 : 0;

                ResponApl02Ia01::updateOrCreate(
                    [
                        'id_data_sertifikasi_asesi' => $idDataSertifikasi,
                        'id_kriteria' => $kukId
                    ],
                    [
                        // Kolom Database => Data dari Session
                        'pencapaian_ia01' => $isKompeten,
                        'standar_industri_ia01' => $finalData['standar_industri'][$kukId] ?? null,
                        'penilaian_lanjut_ia01' => $finalData['penilaian_lanjut'][$kukId] ?? null,

                        // Kita kosongin APL-02 dulu karena ini konteks IA.01
                        // Atau biarin existing kalo updateOrCreate
                    ]
                );
            }

            // Bersihin session kalo sukses
            $request->session()->forget($sessionKey);

            // Redirect ke Home atau Halaman Sukses
            return redirect()->route('home')->with('success', 'Asesmen IA.01 Berhasil Disimpan!');

        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menyimpan ke database: ' . $e->getMessage());
        }
    }
}

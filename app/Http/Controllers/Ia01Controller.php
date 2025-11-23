<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\UnitKompetensi;
use App\Models\ResponApl02Ia01;
use App\Models\Skema;
use Illuminate\Support\Facades\Auth;

class IA01Controller extends Controller
{
    // ============================================================================
    // 🛠️ HELPER UNTUK MOCK DATA (PENTING!)
    // Function ini nipu View biar seolah-olah ada data Asesor/Asesi/Sertifikasi.
    // ============================================================================
    private function getMockInfo()
    {
        // 🔥 [TEAM BACKEND]: Nanti ganti return ini pakai Query Database beneran
        // Contoh: return DataSertifikasi::with('asesor','asesi')->find($id);

        return (object) [
            'id_data_sertifikasi' => 1, // ID Dummy Transaksi
            'asesor_name' => Auth::user()->name ?? 'Budi Santoso (Asesor Dummy)',
            'asesor_id'   => Auth::id() ?? 99,
            'asesor_ttd'  => null, // Path gambar TTD Asesor (bisa null)

            'asesi_name'  => 'Siti Aminah (Asesi Dummy)',
            'asesi_id'    => 88,
            'asesi_ttd'   => null, // Path gambar TTD Asesi (bisa null)
        ];
    }

    /**
     * 1. HALAMAN COVER / HEADER
     */
    public function showCover(Request $request, $skema_id)
    {
        $skema = Skema::with('kelompokPekerjaans.unitKompetensis')->findOrFail($skema_id);
        $kelompok = $skema->kelompokPekerjaans->first();

        if (!$kelompok) {
            return back()->with('error', 'Skema ini belum memiliki Kelompok Pekerjaan.');
        }

        $unitKompetensi = $kelompok->unitKompetensis()->orderBy('urutan')->first();
        $data_sesi = $request->session()->get("ia01.skema_{$skema_id}", []);

        // 🔥 [INTEGRASI]: Panggil Mock Data biar form gak error undefined variable
        $sertifikasi = $this->getMockInfo();

        return view('frontend.IA_01.tampilan_awal', compact(
            'kelompok','skema', 'unitKompetensi', 'data_sesi', 'sertifikasi'
        ));
    }

    /**
     * 2. SIMPAN DATA COVER KE SESSION
     */
    public function storeCover(Request $request, $skema_id)
    {
        $validatedData = $request->validate([
            'tuk' => 'required|in:sewaktu,tempat_kerja,mandiri',
            'tanggal_asesmen' => 'required|date',
        ]);

        // 🔥 [INTEGRASI]: Ambil nama statis dari Mock
        $sertifikasi = $this->getMockInfo();

        $metaData = [
            'nama_asesor' => $sertifikasi->asesor_name,
            'nama_asesi'  => $sertifikasi->asesi_name,
        ];

        // Simpan ke Session
        $sessionKey = "ia01.skema_{$skema_id}";
        $oldData = $request->session()->get($sessionKey, []);

        // Gabung data: (Data Lama) + (Input User) + (Data Mock)
        $newData = array_merge($oldData, $validatedData, $metaData);
        $request->session()->put($sessionKey, $newData);

        // Lanjut ke Step 1
        return redirect()->route('ia01.showStep', ['skema_id' => $skema_id, 'urutan' => 1]);
    }

    /**
     * 3. FORM WIZARD (UNIT KOMPETENSI 1, 2, DST)
     */
    public function showStep(Request $request, $skema_id, $urutan)
    {
        $skema = Skema::with('kelompokPekerjaans')->findOrFail($skema_id);
        $kelompok = $skema->kelompokPekerjaans->firstOrFail();

        $unitKompetensi = UnitKompetensi::with(['elemens.kriteriaUnjukKerja'])
                                    ->where('id_kelompok_pekerjaan', $kelompok->id_kelompok_pekerjaan)
                                    ->where('urutan', $urutan)
                                    ->firstOrFail();

        $kuks = $unitKompetensi->kriteriaUnjukKerja()->orderBy('no_kriteria')->get();
        $totalSteps = $kelompok->unitKompetensis()->count();
        $data_sesi = $request->session()->get("ia01.skema_{$skema_id}", []);

        // Cek tipe form (Aktivitas vs Demonstrasi, opsional)
        $formType = $kuks->first()->tipe ?? 'aktivitas';

        // Mock data (siapa tau butuh nampilin nama asesi di header setiap page)
        $sertifikasi = $this->getMockInfo();

        return view('frontend.IA_01.IA_01', compact(
            'unitKompetensi', 'kuks','skema', 'data_sesi', 'totalSteps', 'formType', 'sertifikasi'
        ));
    }

    /**
     * 4. SIMPAN DATA PER STEP (PER UNIT)
     */
    public function storeStep(Request $request, $skema_id, $urutan)
    {
        $skema = Skema::with('kelompokPekerjaans')->findOrFail($skema_id);
        $kelompok = $skema->kelompokPekerjaans->firstOrFail();
        $unitKompetensi = UnitKompetensi::where('id_kelompok_pekerjaan', $kelompok->id_kelompok_pekerjaan)
                                    ->where('urutan', $urutan)
                                    ->firstOrFail();

        $kukIds = $unitKompetensi->kriteriaUnjukKerja()->pluck('id_kriteria')->toArray();

        // Validasi Input Dinamis
        $validationRules = [
            'hasil' => 'required|array',
            'standar_industri' => 'nullable|array',
            'penilaian_lanjut' => 'nullable|array',
        ];

        foreach ($kukIds as $id) {
            $validationRules["hasil.{$id}"] = ['required', Rule::in(['kompeten', 'belum_kompeten'])];
            $validationRules["standar_industri.{$id}"] = ['nullable', 'string'];
            $validationRules["penilaian_lanjut.{$id}"] = ['nullable', 'string'];
        }

        $validatedData = $request->validate($validationRules, [
            'hasil.*.required' => 'Semua poin pencapaian (Ya/Tidak) harus dipilih.',
        ]);

        $sessionKey = "ia01.skema_{$skema_id}";
        $currentSession = $request->session()->get($sessionKey, []);

        // Gunakan array_replace biar data baru (revisi) nimpah data lama
        $currentSession['hasil'] = array_replace(
            ($currentSession['hasil'] ?? []),
            $validatedData['hasil']
        );

        if ($request->has('standar_industri')) {
            $currentSession['standar_industri'] = array_replace(
                ($currentSession['standar_industri'] ?? []),
                $request->input('standar_industri')
            );
        }
        if ($request->has('penilaian_lanjut')) {
            $currentSession['penilaian_lanjut'] = array_replace(
                ($currentSession['penilaian_lanjut'] ?? []),
                $request->input('penilaian_lanjut')
            );
        }

        $request->session()->put($sessionKey, $currentSession);

        // Navigasi Next / Finish
        $totalSteps = $kelompok->unitKompetensis()->count();
        if ($urutan < $totalSteps) {
            return redirect()->route('ia01.showStep', ['skema_id' => $skema_id, 'urutan' => $urutan + 1]);
        } else {
            return redirect()->route('ia01.finish', ['skema_id' => $skema_id]);
        }
    }

    /**
     * 5. HALAMAN FINISH (REKOMENDASI & TTD)
     */
    public function showFinish(Request $request, $skema_id)
    {
        $skema = Skema::with('kelompokPekerjaans')->findOrFail($skema_id);
        $kelompok = $skema->kelompokPekerjaans->first();

        $sessionKey = "ia01.skema_{$skema_id}";
        $data_sesi = $request->session()->get($sessionKey, []);

        if (empty($data_sesi)) {
            return redirect()->route('ia01.cover', ['skema_id' => $skema_id]);
        }

        // LOGIC: Jika ada satu saja 'belum_kompeten', sistem menyarankan BK
        $adaBK = in_array('belum_kompeten', $data_sesi['hasil'] ?? []);
        $rekomendasiSistem = $adaBK ? 'belum_kompeten' : 'kompeten';

        // 🔥 [INTEGRASI]: Convert Mock Data jadi Object untuk View
        $mock = $this->getMockInfo();

        // Kita bungkus jadi object sertifikasi biar view-nya tetep elegan
        // View lu manggil: $sertifikasi->asesi->name
        $sertifikasi = (object) [
            'id_data_sertifikasi' => $mock->id_data_sertifikasi,
            'asesi' => (object) ['name' => $mock->asesi_name, 'ttd_path' => $mock->asesi_ttd],
            'asesor' => (object) ['name' => $mock->asesor_name, 'ttd_path' => $mock->asesor_ttd],
        ];

        return view('frontend.IA_01.finish', compact(
            'skema', 'kelompok', 'rekomendasiSistem', 'sertifikasi', 'data_sesi'
        ));
    }

    /**
     * 6. FINAL SUBMIT KE DATABASE
     */
    public function storeFinish(Request $request, $skema_id)
    {
        $sessionKey = "ia01.skema_{$skema_id}";
        $finalData = $request->session()->get($sessionKey);

        // Validasi Logic Backend (Double Check)
        $adaBK = in_array('belum_kompeten', $finalData['hasil'] ?? []);
        $statusWajib = $adaBK ? 'belum_kompeten' : 'kompeten';

        $request->validate([
            'rekomendasi' => [
                'required',
                'in:kompeten,belum_kompeten',
                // Custom Rule: Kalau sistem BK, user gaboleh pilih K
                function ($attribute, $value, $fail) use ($statusWajib) {
                    if ($statusWajib === 'belum_kompeten' && $value === 'kompeten') {
                        $fail('Sistem mendeteksi ada KUK yang bernilai "TIDAK". Rekomendasi akhir wajib "Belum Kompeten".');
                    }
                },
            ],
            'umpan_balik' => 'required|string|min:5',
            // ==============================================================
            // [TODO TEAM ASESOR]: HAPUS KOMENTAR INI SAAT PRODUCTION
            // Agar jika status BK, asesor WAJIB mengisi detailnya.
            // ==============================================================
            // 'bk_unit'   => 'required_if:rekomendasi,belum_kompeten',
            // 'bk_elemen' => 'required_if:rekomendasi,belum_kompeten',
            // 'bk_kuk'    => 'required_if:rekomendasi,belum_kompeten',
        ]);

        // 🔥 [INTEGRASI]: Ambil ID Sertifikasi dari Mock Data
        $mock = $this->getMockInfo();
        $idDataSertifikasi = $mock->id_data_sertifikasi;

        try {
            // Simpan data per KUK ke tabel Respon
            if (!empty($finalData['hasil'])) {
                foreach ($finalData['hasil'] as $kukId => $status) {
                    $isKompeten = ($status === 'kompeten') ? 1 : 0;

                    ResponApl02Ia01::updateOrCreate(
                        [
                            'id_data_sertifikasi_asesi' => $idDataSertifikasi,
                            'id_kriteria' => $kukId
                        ],
                        [
                            'pencapaian_ia01' => $isKompeten,
                            'standar_industri_ia01' => $finalData['standar_industri'][$kukId] ?? null,
                            'penilaian_lanjut_ia01' => $finalData['penilaian_lanjut'][$kukId] ?? null,
                        ]
                    );
                }
            }

            // 🔥 [TEAM BACKEND]: Insert Logic Update Status Global Disini
            // Contoh: DataSertifikasi::where('id', $idDataSertifikasi)->update(...)
            // Tapi karena ini tugas lu cuma form, biarin aja kosong atau dikomen.

            // Bersihkan Session setelah sukses
            $request->session()->forget($sessionKey);

            return redirect()->route('ia01.success_page');

        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menyimpan data: ' . $e->getMessage());
        }
    }
}

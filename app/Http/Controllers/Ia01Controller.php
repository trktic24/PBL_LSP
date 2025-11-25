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
    // 🛡️ HELPER: CEK ADMIN
    // ============================================================================
    private function isAdmin()
    {
        // Asumsi relasi user->role->nama_role ada dan valid
        return Auth::check() && Auth::user()->role->nama_role === 'admin';
    }

    // ============================================================================
    // 🛠️ HELPER: GET DATA SERTIFIKASI
    // ============================================================================
    private function getSertifikasi($id_sertifikasi)
    {
        return \App\Models\DataSertifikasiAsesi::with([
            'asesi',
            'jadwal.tuk',
            'jadwal.skema',
            'jadwal.asesor',
            'jadwal.skema.kelompokPekerjaans.UnitKompetensis'
        ])->findOrFail($id_sertifikasi);
    }

    /**
     * 1. HALAMAN COVER / HEADER
     */
    public function showCover(Request $request, $id_sertifikasi)
    {
        // 🔥 [ADMIN CHECK]: Redirect ke halaman khusus admin
        if ($this->isAdmin()) {
            return redirect()->route('ia01.admin.show', ['id_sertifikasi' => $id_sertifikasi]);
        }

        $sertifikasi = $this->getSertifikasi($id_sertifikasi);
        $skema = $sertifikasi->jadwal->skema;
        $kelompok = $skema->kelompokPekerjaans->first();

        if (!$kelompok) {
            return back()->with('error', 'Skema ini belum memiliki Kelompok Pekerjaan.');
        }

        $unitKompetensi = $kelompok->unitKompetensis()->orderBy('urutan')->first();
        $data_sesi = $request->session()->get("ia01.sertifikasi_{$id_sertifikasi}", []);

        return view('frontend.IA_01.tampilan_awal', compact(
            'kelompok','skema', 'unitKompetensi', 'data_sesi', 'sertifikasi'
        ));
    }

    /**
     * 2. SIMPAN DATA COVER KE SESSION
     */
    public function storeCover(Request $request, $id_sertifikasi)
    {
        if ($this->isAdmin()) {
            abort(403, 'Admin tidak diizinkan mengisi form.');
        }

        $validatedData = $request->validate([
            'tuk' => 'required|in:sewaktu,tempat_kerja,mandiri',
            'tanggal_asesmen' => 'required|date',
        ]);

        $sertifikasi = $this->getSertifikasi($id_sertifikasi);

        $metaData = [
            'nama_asesor' => $sertifikasi->jadwal->asesor->name ?? 'Asesor',
            'nama_asesi'  => $sertifikasi->asesi->name ?? 'Asesi',
        ];

        // Simpan ke Session
        $sessionKey = "ia01.sertifikasi_{$id_sertifikasi}";
        $oldData = $request->session()->get($sessionKey, []);

        // Gabung data: (Data Lama) + (Input User) + (Data Meta)
        $newData = array_merge($oldData, $validatedData, $metaData);
        $request->session()->put($sessionKey, $newData);

        // Lanjut ke Step 1
        return redirect()->route('ia01.showStep', ['id_sertifikasi' => $id_sertifikasi, 'urutan' => 1]);
    }

    /**
     * 3. FORM WIZARD (UNIT KOMPETENSI 1, 2, DST)
     */
    public function showStep(Request $request, $id_sertifikasi, $urutan)
    {
        // 🔥 [ADMIN CHECK]: Redirect ke halaman khusus admin
        if ($this->isAdmin()) {
            return redirect()->route('ia01.admin.show', ['id_sertifikasi' => $id_sertifikasi]);
        }

        $sertifikasi = $this->getSertifikasi($id_sertifikasi);
        $skema = $sertifikasi->jadwal->skema;
        $kelompok = $skema->kelompokPekerjaans->firstOrFail();

        $unitKompetensi = UnitKompetensi::with(['elemens.kriteriaUnjukKerja'])
                                    ->where('id_kelompok_pekerjaan', $kelompok->id_kelompok_pekerjaan)
                                    ->where('urutan', $urutan)
                                    ->firstOrFail();

        $kuks = $unitKompetensi->kriteriaUnjukKerja()->orderBy('no_kriteria')->get();
        $totalSteps = $kelompok->unitKompetensis()->count();
        $data_sesi = $request->session()->get("ia01.sertifikasi_{$id_sertifikasi}", []);

        // Cek tipe form (Aktivitas vs Demonstrasi, opsional)
        $formType = $kuks->first()->tipe ?? 'aktivitas';

        return view('frontend.IA_01.IA_01', compact(
            'unitKompetensi', 'kuks','skema', 'data_sesi', 'totalSteps', 'formType', 'sertifikasi'
        ));
    }

    /**
     * 4. SIMPAN DATA PER STEP (PER UNIT)
     */
    public function storeStep(Request $request, $id_sertifikasi, $urutan)
    {
        if ($this->isAdmin()) {
            abort(403, 'Admin tidak diizinkan mengisi form.');
        }

        $sertifikasi = $this->getSertifikasi($id_sertifikasi);
        $skema = $sertifikasi->jadwal->skema;
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

        $sessionKey = "ia01.sertifikasi_{$id_sertifikasi}";
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
            return redirect()->route('ia01.showStep', ['id_sertifikasi' => $id_sertifikasi, 'urutan' => $urutan + 1]);
        } else {
            return redirect()->route('ia01.finish', ['id_sertifikasi' => $id_sertifikasi]);
        }
    }

    /**
     * 5. HALAMAN FINISH (REKOMENDASI & TTD)
     */
    public function showFinish(Request $request, $id_sertifikasi)
    {
        // 🔥 [ADMIN CHECK]: Redirect ke halaman khusus admin
        if ($this->isAdmin()) {
            return redirect()->route('ia01.admin.show', ['id_sertifikasi' => $id_sertifikasi]);
        }

        $sertifikasi = $this->getSertifikasi($id_sertifikasi);
        $skema = $sertifikasi->jadwal->skema;
        $kelompok = $skema->kelompokPekerjaans->first();

        $sessionKey = "ia01.sertifikasi_{$id_sertifikasi}";
        $data_sesi = $request->session()->get($sessionKey, []);

        if (empty($data_sesi)) {
            return redirect()->route('ia01.cover', ['id_sertifikasi' => $id_sertifikasi]);
        }

        // LOGIC: Jika ada satu saja 'belum_kompeten', sistem menyarankan BK
        $adaBK = in_array('belum_kompeten', $data_sesi['hasil'] ?? []);
        $rekomendasiSistem = $adaBK ? 'belum_kompeten' : 'kompeten';

        return view('frontend.IA_01.finish', compact(
            'skema', 'kelompok', 'rekomendasiSistem', 'sertifikasi', 'data_sesi'
        ));
    }



    /**
     * 6. FINAL SUBMIT KE DATABASE
     */
    public function storeFinish(Request $request, $id_sertifikasi)
    {
        if ($this->isAdmin()) {
            abort(403, 'Admin tidak diizinkan menyimpan data.');
        }

        $sessionKey = "ia01.sertifikasi_{$id_sertifikasi}";
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
        ]);

        $sertifikasi = $this->getSertifikasi($id_sertifikasi);
        $idDataSertifikasi = $sertifikasi->id_data_sertifikasi_asesi;

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

            // Update Data Sertifikasi (Umpan Balik & Rekomendasi)
            $sertifikasi->update([
                'feedback_ia01' => $request->umpan_balik,
                'rekomendasi_ia01' => $request->rekomendasi,
            ]);


            // Bersihkan Session setelah sukses
            $request->session()->forget($sessionKey);

            return redirect()->route('ia01.success_page');

        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menyimpan data: ' . $e->getMessage());
        }
    }

    /**
     * 7. HALAMAN KHUSUS ADMIN (READ ONLY - ALL UNITS)
     */
    public function showAdmin($id_sertifikasi)
    {
        if (!$this->isAdmin()) {
            return redirect()->route('ia01.cover', ['id_sertifikasi' => $id_sertifikasi]);
        }

        $sertifikasi = $this->getSertifikasi($id_sertifikasi);
        $skema = $sertifikasi->jadwal->skema;
        $kelompok = $skema->kelompokPekerjaans->firstOrFail();

        // Ambil SEMUA unit kompetensi beserta elemen dan KUK
        $units = UnitKompetensi::with(['elemens.kriteriaUnjukKerja'])
                    ->where('id_kelompok_pekerjaan', $kelompok->id_kelompok_pekerjaan)
                    ->orderBy('urutan')
                    ->get();

        // Ambil semua jawaban yang tersimpan di DB
        $responses = ResponApl02Ia01::where('id_data_sertifikasi_asesi', $sertifikasi->id_data_sertifikasi_asesi)
                        ->get()
                        ->keyBy('id_kriteria');

        return view('frontend.IA_01.admin_show', compact(
            'skema', 'kelompok', 'units', 'sertifikasi', 'responses'
        ));
    }
}

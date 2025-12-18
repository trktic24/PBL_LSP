<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\UnitKompetensi;
use App\Models\ResponApl02Ia01;
use App\Models\Skema;
use App\Models\KriteriaUnjukKerja; // Added import
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;




class IA01Controller extends Controller
{
    // ============================================================================
    // 🛡️ HELPER: CEK ADMIN
    // ============================================================================
    private function isAdmin()
    {
        return Auth::check() && Auth::user()->role->nama_role === 'admin';
    }

    // ============================================================================
    // 🛠️ HELPER: GET DATA SERTIFIKASI
    // ============================================================================
    private function getSertifikasi($id_sertifikasi)
    {
        return \App\Models\DataSertifikasiAsesi::with([
            'asesi',
            'jadwal.masterTuk',
            'jadwal.skema',
            'jadwal.asesor',
            'jadwal.skema.kelompokPekerjaan.unitKompetensi.elemen.kriteria' // Eager load ALL nested data
        ])->findOrFail($id_sertifikasi);
    }

    /**
     * SHOW SINGLE PAGE FORM
     */
    public function index(Request $request, $id_sertifikasi)
    {
        // 1. Admin redirect
        if ($this->isAdmin()) {
            return redirect()->route('ia01.admin.show', ['id_sertifikasi' => $id_sertifikasi]);
        }

        // 2. Load Data
        $sertifikasi = $this->getSertifikasi($id_sertifikasi);
        $skema = $sertifikasi->jadwal->skema;
        $kelompok = $skema->kelompokPekerjaan->first();

        if (!$kelompok) {
            return back()->with('error', 'Skema ini belum memiliki Kelompok Pekerjaan.');
        }

        // 3. Get existing session data AND DB data
        $existingResponses = ResponApl02Ia01::where('id_data_sertifikasi_asesi', $sertifikasi->id_data_sertifikasi_asesi)
            ->get()
            ->keyBy('id_kriteria');

        $data_sesi = [
             'tuk' => old('tuk'), 
        ]; 

        // Calculate System Recommendation based on saved data
        // If ANY KUK is '0' (Belum Kompeten), then system recommends 'belum_kompeten'
        $hasBK = $existingResponses->contains(function ($response) {
            return $response->pencapaian_ia01 == 0;
        });
        $rekomendasiSistem = $hasBK ? 'belum_kompeten' : 'kompeten';

        return view('frontend.IA_01.single_page', compact(
            'skema',
            'kelompok',
            'sertifikasi',
            'data_sesi',
            'existingResponses',
            'rekomendasiSistem'
        ));
    }

    /**
     * STORE ALL DATA (Validation & Save)
     */
    public function store(Request $request, $id_sertifikasi)
    {
        if ($this->isAdmin()) {
            abort(403, 'Admin tidak diizinkan menyimpan data.');
        }

        $sertifikasi = $this->getSertifikasi($id_sertifikasi);
        
        // 1. VALIDATION RULES
        $rules = [
            // Data Diri
            'tuk' => 'required|in:sewaktu,tempat_kerja,mandiri',
            'tanggal_asesmen' => 'required|date',
            
            // Units (Looping Logic)
            'hasil' => 'required|array',
            'hasil.*' => 'required|in:kompeten,belum_kompeten',
            'standar_industri' => 'nullable|array',
            'penilaian_lanjut' => 'nullable|array',

            // Finish
             'umpan_balik' => 'required|string|min:5',
             'rekomendasi' => 'required|in:kompeten,belum_kompeten',
        ];

        // 2. Custom Validator for K vs BK Consistency
        $request->validate($rules, [
            'hasil.required' => 'Hasil observasi belum diisi.',
            'hasil.*.required' => 'Setiap Kriteria Unjuk Kerja harus dinilai (K/BK).',
            'umpan_balik.required' => 'Umpan balik wajib diisi.',
            'rekomendasi.required' => 'Rekomendasi akhir wajib dipilih.'
        ]);

        // 3. Logic Check: If any 'belum_kompeten', Rekomendasi MUST be 'belum_kompeten'
        if (in_array('belum_kompeten', $request->input('hasil', [])) && $request->input('rekomendasi') === 'kompeten') {
            return back()->withErrors([
                'rekomendasi' => 'Terdapat penilaian "Belum Kompeten" pada unit kompetensi. Rekomendasi akhir tidak boleh "Kompeten".'
            ])->withInput();
        }

        // 4. SAVE TO DB (ResponApl02Ia01 & Update Sertifikasi)
        try {
            DB::beginTransaction();

            // Save Details (KUK Results)
            $idDataSertifikasi = $sertifikasi->id_data_sertifikasi_asesi;
            foreach ($request->input('hasil', []) as $kukId => $status) {
                ResponApl02Ia01::updateOrCreate(
                    [
                        'id_data_sertifikasi_asesi' => $idDataSertifikasi,
                        'id_kriteria' => $kukId
                    ],
                    [
                        'pencapaian_ia01' => ($status === 'kompeten') ? 1 : 0,
                        'standar_industri_ia01' => $request->input("standar_industri.{$kukId}"),
                        'penilaian_lanjut_ia01' => $request->input("penilaian_lanjut.{$kukId}"),
                    ]
                );
            }

        // Update Sertifikasi Header
        $feedback = $request->umpan_balik;
        
        // Append BK Details if Rekomendasi is Belum Kompeten
        if ($request->rekomendasi === 'belum_kompeten' && $request->has('bk_unit')) {
            $feedback .= "\n\n--- DETAIL BELUM KOMPETEN ---\n";
            $feedback .= "Unit: " . $request->bk_unit . "\n";
            $feedback .= "Elemen: " . $request->bk_elemen . "\n";
            $feedback .= "No. KUK: " . $request->bk_kuk . "\n";
        }

        $sertifikasi->update([
            'feedback_ia01' => $feedback,
            'rekomendasi_ia01' => $request->rekomendasi,
        ]);

            DB::commit();

            return redirect()->route('ia01.success_page');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * SUCCESS PAGE (No Change)
     */
    public function successPage()
    {
        return view('frontend.IA_01.success'); // Ensure this view exists
    }


    /**
     * 7. HALAMAN KHUSUS ADMIN (READ ONLY - ALL UNITS)
     */
    public function showAdmin($id_sertifikasi)
    {
        if (!$this->isAdmin()) {
            // Redirect to index for normal user
             return redirect()->route('ia01.index', ['id_sertifikasi' => $id_sertifikasi]);
        }

        $sertifikasi = $this->getSertifikasi($id_sertifikasi);
        $skema = $sertifikasi->jadwal->skema;
        $kelompok = $skema->kelompokPekerjaan->firstOrFail();

        // Ambil SEMUA unit kompetensi beserta elemen dan KUK
        $units = UnitKompetensi::with(['elemen.kriteria'])
            ->where('id_kelompok_pekerjaan', $kelompok->id_kelompok_pekerjaan)
            ->orderBy('urutan')
            ->get();

        // Ambil semua jawaban yang tersimpan di DB
        $responses = ResponApl02Ia01::where('id_data_sertifikasi_asesi', $sertifikasi->id_data_sertifikasi_asesi)
            ->get()
            ->keyBy('id_kriteria');

        return view('frontend.IA_01.admin_show', compact(
            'skema',
            'kelompok',
            'units',
            'sertifikasi',
            'responses'
        ));
    }
}

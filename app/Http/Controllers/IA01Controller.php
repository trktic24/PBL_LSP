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
    // 🛡️ HELPER: CEK ROLE
    // ============================================================================
    private function isAdmin()
    {
        $role = Auth::user()->role->nama_role ?? '';
        return in_array($role, ['admin', 'superadmin']);
    }

    private function isAsesor()
    {
        return Auth::check() && Auth::user()->role->nama_role === 'asesor';
    }

    private function isAsesi()
    {
        return Auth::check() && Auth::user()->role->nama_role === 'asesi';
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
        // 1. Admin/Asesi → Redirect to Read-Only View
        if ($this->isAdmin() || $this->isAsesi()) {
            return redirect()->route('ia01.view', ['id_sertifikasi' => $id_sertifikasi]);
        }

        // 2. Proteksi: Hanya Asesor yang bisa akses form editable
        if (!$this->isAsesor()) {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
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
        // Hanya Asesor yang bisa submit
        if (!$this->isAsesor()) {
            abort(403, 'Hanya Asesor yang dapat mengisi form ini.');
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
        $updateData = [
            'feedback_ia01' => $request->umpan_balik,
            'rekomendasi_ia01' => $request->rekomendasi,
        ];

        // Save BK Details to dedicated columns if Belum Kompeten
        if ($request->rekomendasi === 'belum_kompeten') {
            $updateData['bk_unit_ia01'] = $request->bk_unit;
            $updateData['bk_elemen_ia01'] = $request->bk_elemen;
            $updateData['bk_kuk_ia01'] = $request->bk_kuk;
        } else {
            // Clear BK details if Kompeten
            $updateData['bk_unit_ia01'] = null;
            $updateData['bk_elemen_ia01'] = null;
            $updateData['bk_kuk_ia01'] = null;
        }

        $sertifikasi->update($updateData);

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
     * VIEW READ-ONLY (Admin & Asesi)
     */
    public function showView($id_sertifikasi)
    {
        // Asesor diarahkan ke form editable
        if ($this->isAsesor()) {
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

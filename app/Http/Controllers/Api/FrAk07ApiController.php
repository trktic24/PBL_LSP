<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\DataSertifikasiAsesi;
use App\Models\PoinPotensiAk07;
use App\Models\PersyaratanModifikasiAk07;
use App\Models\ResponPotensiAk07;
use App\Models\ResponDiperlukanPenyesuaianAk07;
use App\Models\HasilPenyesuaianAk07;

class FrAk07ApiController extends Controller
{
    /**
     * Get FR_AK_07 Form Data
     * URL: GET /api/fr-ak-07/{id_sertifikasi}
     */
    public function show($id)
    {
        try {
            // 1. Ambil Data Sertifikasi
            $sertifikasi = DataSertifikasiAsesi::with([
                'asesi',
                'jadwal.skema',
                'jadwal.asesor'
            ])->find($id);

            if (!$sertifikasi) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Data Sertifikasi tidak ditemukan.'
                ], 404);
            }

            // 2. Cek Skema
            if (!$sertifikasi->skema) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Data Skema tidak ditemukan untuk sertifikasi ini.'
                ], 404);
            }

            // 3. Ambil Master Data
            $masterPotensi = PoinPotensiAk07::all();
            $masterPersyaratan = PersyaratanModifikasiAk07::with('opsiKeterangan')->get();

            // 4. Ambil Data Existing (jika ada, untuk pre-fill)
            $existingPotensi = ResponPotensiAk07::where('id_data_sertifikasi_asesi', $id)->get();
            $existingPenyesuaian = ResponDiperlukanPenyesuaianAk07::where('id_data_sertifikasi_asesi', $id)->get();
            $existingHasil = HasilPenyesuaianAk07::where('id_data_sertifikasi_asesi', $id)->first();

            return response()->json([
                'status' => 'success',
                'data' => [
                    'sertifikasi' => $sertifikasi,
                    'master_potensi' => $masterPotensi,
                    'master_persyaratan' => $masterPersyaratan,
                    'existing_data' => [
                        'potensi' => $existingPotensi,
                        'penyesuaian' => $existingPenyesuaian,
                        'hasil' => $existingHasil
                    ]
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Submit FR_AK_07 Form Data
     * URL: POST /api/fr-ak-07/{id_sertifikasi}
     */
    public function store(Request $request, $id_sertifikasi)
    {
        // 1. Validasi Input
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'potensi_asesi' => 'nullable|array',
            'potensi_asesi.*' => 'exists:poin_potensi_AK07,id_poin_potensi_AK07',
            'penyesuaian' => 'required|array',
            'acuan_pembanding' => 'nullable|string',
            'metode_asesmen' => 'nullable|string',
            'instrumen_asesmen' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        DB::beginTransaction();

        try {
            // --- SIMPAN BAGIAN A: POTENSI ASESI ---
            ResponPotensiAk07::where('id_data_sertifikasi_asesi', $id_sertifikasi)->delete();

            if ($request->has('potensi_asesi')) {
                foreach ($request->potensi_asesi as $id_potensi) {
                    ResponPotensiAk07::create([
                        'id_data_sertifikasi_asesi' => $id_sertifikasi,
                        'id_poin_potensi_AK07' => $id_potensi,
                        'respon_asesor' => null
                    ]);
                }
            }

            // --- SIMPAN BAGIAN B: PERSYARATAN MODIFIKASI ---
            ResponDiperlukanPenyesuaianAk07::where('id_data_sertifikasi_asesi', $id_sertifikasi)->delete();

            foreach ($request->penyesuaian as $id_soal => $data) {
                $status = $data['status'] ?? 'Tidak';
                $keteranganIds = $data['keterangan'] ?? [];
                $catatanManual = $data['catatan_manual'] ?? null;

                if ($status === 'Ya' && !empty($keteranganIds)) {
                    foreach ($keteranganIds as $id_ket) {
                        ResponDiperlukanPenyesuaianAk07::create([
                            'id_data_sertifikasi_asesi' => $id_sertifikasi,
                            'id_persyaratan_modifikasi_AK07' => $id_soal,
                            'id_catatan_keterangan_AK07' => $id_ket,
                            'respon_penyesuaian' => 'Ya',
                            'respon_catatan_keterangan' => $catatanManual
                        ]);
                    }
                } else {
                    ResponDiperlukanPenyesuaianAk07::create([
                        'id_data_sertifikasi_asesi' => $id_sertifikasi,
                        'id_persyaratan_modifikasi_AK07' => $id_soal,
                        'id_catatan_keterangan_AK07' => null,
                        'respon_penyesuaian' => $status,
                        'respon_catatan_keterangan' => $catatanManual
                    ]);
                }
            }

            // --- SIMPAN BAGIAN C: HASIL AKHIR ---
            HasilPenyesuaianAk07::updateOrCreate(
                ['id_data_sertifikasi_asesi' => $id_sertifikasi],
                [
                    'Acuan_Pembanding_Asesmen' => $request->acuan_pembanding,
                    'Metode_Asesmen' => $request->metode_asesmen,
                    'Instrumen_Asesmen' => $request->instrumen_asesmen
                ]
            );

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Formulir FR.AK.07 berhasil disimpan!'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menyimpan data: ' . $e->getMessage()
            ], 500);
        }
    }
}

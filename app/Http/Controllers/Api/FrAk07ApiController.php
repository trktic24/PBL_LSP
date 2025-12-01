<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DataSertifikasiAsesi;
use App\Models\HasilPenyesuaianAK07;
use App\Models\PersyaratanModifikasiAK07;
use App\Models\PoinPotensiAK07;
use App\Models\ResponDiperlukanPenyesuaianAK07;
use App\Models\ResponPotensiAK07;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class FrAk07ApiController extends Controller
{
    /**
     * Get FR.AK.07 Form Data
     * URL: GET /api/fr-ak-07/{id_sertifikasi}
     */
    public function show($id)
    {
        try {
            // 1. Fetch Data Sertifikasi with relations
            $dataSertifikasi = DataSertifikasiAsesi::with([
                'asesi',
                'jadwal.skema',
                'jadwal.asesor',
                'responPotensiAk07',
                'responPenyesuaianAk07',
                'hasilPenyesuaianAk07'
            ])->find($id);

            if (!$dataSertifikasi) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Data Sertifikasi not found.'
                ], 404);
            }

            // 2. Fetch Master Data
            $poinPotensi = PoinPotensiAK07::all();
            $persyaratanModifikasi = PersyaratanModifikasiAK07::with('catatanKeterangan')->get();

            // 3. Structure the response
            return response()->json([
                'status' => 'success',
                'data' => [
                    'sertifikasi' => $dataSertifikasi,
                    'master_potensi' => $poinPotensi,
                    'master_persyaratan' => $persyaratanModifikasi,
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error fetching data: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Submit FR.AK.07 Form Data
     * URL: POST /api/fr-ak-07/{id_sertifikasi}
     */
    public function store(Request $request, $id)
    {
        // 1. Validation
        $validator = Validator::make($request->all(), [
            'respon_potensi' => 'array',
            'respon_potensi.*.id_poin_potensi_AK07' => 'required|exists:poin_potensi_AK07,id_poin_potensi_AK07',
            'respon_potensi.*.respon_asesor' => 'nullable|string',

            'respon_penyesuaian' => 'array',
            'respon_penyesuaian.*.id_persyaratan_modifikasi_AK07' => 'required|exists:persyaratan_modifikasi_AK07,id_persyaratan_modifikasi_AK07',
            'respon_penyesuaian.*.id_catatan_keterangan_AK07' => 'nullable|exists:catatan_keterangan_AK07,id_catatan_keterangan_AK07',
            'respon_penyesuaian.*.respon_penyesuaian' => 'required|in:Ya,Tidak',
            'respon_penyesuaian.*.respon_catatan_keterangan' => 'nullable|string',

            'hasil_penyesuaian' => 'array',
            'hasil_penyesuaian.Acuan_Pembanding_Asesmen' => 'nullable|string',
            'hasil_penyesuaian.Metode_Asesmen' => 'nullable|string',
            'hasil_penyesuaian.Instrumen_Asesmen' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        DB::beginTransaction();
        try {
            // 2. Save Respon Potensi
            if ($request->has('respon_potensi')) {
                foreach ($request->respon_potensi as $potensi) {
                    ResponPotensiAK07::updateOrCreate(
                        [
                            'id_data_sertifikasi_asesi' => $id,
                            'id_poin_potensi_AK07' => $potensi['id_poin_potensi_AK07']
                        ],
                        [
                            'respon_asesor' => $potensi['respon_asesor']
                        ]
                    );
                }
            }

            // 3. Save Respon Penyesuaian
            if ($request->has('respon_penyesuaian')) {
                foreach ($request->respon_penyesuaian as $penyesuaian) {
                    ResponDiperlukanPenyesuaianAK07::updateOrCreate(
                        [
                            'id_data_sertifikasi_asesi' => $id,
                            'id_persyaratan_modifikasi_AK07' => $penyesuaian['id_persyaratan_modifikasi_AK07']
                        ],
                        [
                            'id_catatan_keterangan_AK07' => $penyesuaian['id_catatan_keterangan_AK07'] ?? null,
                            'respon_penyesuaian' => $penyesuaian['respon_penyesuaian'],
                            'respon_catatan_keterangan' => $penyesuaian['respon_catatan_keterangan'] ?? null
                        ]
                    );
                }
            }

            // 4. Save Hasil Penyesuaian
            if ($request->has('hasil_penyesuaian')) {
                HasilPenyesuaianAK07::updateOrCreate(
                    ['id_data_sertifikasi_asesi' => $id],
                    [
                        'Acuan_Pembanding_Asesmen' => $request->hasil_penyesuaian['Acuan_Pembanding_Asesmen'] ?? '',
                        'Metode_Asesmen' => $request->hasil_penyesuaian['Metode_Asesmen'] ?? '',
                        'Instrumen_Asesmen' => $request->hasil_penyesuaian['Instrumen_Asesmen'] ?? ''
                    ]
                );
            }

            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => 'Data FR.AK.07 saved successfully'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Error saving data: ' . $e->getMessage()
            ], 500);
        }
    }
}

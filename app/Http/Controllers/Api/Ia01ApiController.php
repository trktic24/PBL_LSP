<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\DataSertifikasiAsesi;
use App\Models\ResponApl02Ia01;
use Illuminate\Validation\Rule;

class Ia01ApiController extends Controller
{
    /**
     * Get IA_01 Form Data (Skema, Units, KUKs, Existing Responses)
     * URL: GET /api/ia-01/{id_sertifikasi}
     */
    public function show($id)
    {
        try {
            // 1. Ambil Data Sertifikasi
            $sertifikasi = DataSertifikasiAsesi::with([
                'asesi',
                'jadwal.skema.kelompokPekerjaans.unitKompetensis.elemens.kriteriaUnjukKerja',
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

            // 3. Ambil Existing Responses
            $existingResponses = ResponApl02Ia01::where('id_data_sertifikasi_asesi', $id)
                ->get()
                ->keyBy('id_kriteria');

            // 4. Strukturkan Data untuk Response yang lebih rapi
            $skema = $sertifikasi->skema;
            $units = [];

            foreach ($skema->kelompokPekerjaans as $kelompok) {
                foreach ($kelompok->unitKompetensis as $unit) {
                    $elements = [];
                    foreach ($unit->elemens as $elemen) {
                        $kuks = [];
                        foreach ($elemen->kriteriaUnjukKerja as $kuk) {
                            $response = $existingResponses[$kuk->id_kriteria] ?? null;
                            $kuks[] = [
                                'id_kriteria' => $kuk->id_kriteria,
                                'kriteria' => $kuk->kriteria,
                                'pertanyaan' => $kuk->pertanyaan_ia01, // Asumsi ada kolom ini
                                'response' => $response ? [
                                    'pencapaian' => $response->pencapaian_ia01 == 1 ? 'kompeten' : 'belum_kompeten',
                                    'standar_industri' => $response->standar_industri_ia01,
                                    'penilaian_lanjut' => $response->penilaian_lanjut_ia01
                                ] : null
                            ];
                        }
                        $elements[] = [
                            'id_elemen' => $elemen->id_elemen,
                            'nama_elemen' => $elemen->nama_elemen,
                            'kriteria' => $kuks
                        ];
                    }
                    $units[] = [
                        'id_unit' => $unit->id_unit,
                        'kode_unit' => $unit->kode_unit,
                        'judul_unit' => $unit->judul_unit,
                        'elemen' => $elements
                    ];
                }
            }

            return response()->json([
                'status' => 'success',
                'data' => [
                    'sertifikasi' => [
                        'id' => $sertifikasi->id_data_sertifikasi_asesi,
                        'asesi' => $sertifikasi->asesi->nama_asesi,
                        'asesor' => $sertifikasi->jadwal->asesor->nama_lengkap ?? null,
                        'skema' => $skema->nama_skema
                    ],
                    'units' => $units
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
     * Submit IA_01 Form Data
     * URL: POST /api/ia-01/{id_sertifikasi}
     */
    public function store(Request $request, $id_sertifikasi)
    {
        // Validasi Input
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'hasil' => 'required|array',
            'hasil.*' => ['required', Rule::in(['kompeten', 'belum_kompeten'])],
            'standar_industri' => 'nullable|array',
            'penilaian_lanjut' => 'nullable|array',
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
            foreach ($request->hasil as $kukId => $status) {
                $isKompeten = ($status === 'kompeten') ? 1 : 0;

                ResponApl02Ia01::updateOrCreate(
                    [
                        'id_data_sertifikasi_asesi' => $id_sertifikasi,
                        'id_kriteria' => $kukId
                    ],
                    [
                        'pencapaian_ia01' => $isKompeten,
                        'standar_industri_ia01' => $request->standar_industri[$kukId] ?? null,
                        'penilaian_lanjut_ia01' => $request->penilaian_lanjut[$kukId] ?? null,
                    ]
                );
            }

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Formulir IA.01 berhasil disimpan!'
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

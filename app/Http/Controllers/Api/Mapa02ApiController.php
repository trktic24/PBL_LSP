<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DataSertifikasiAsesi;
use App\Models\Mapa02;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class Mapa02ApiController extends Controller
{
    /**
     * Get FR.MAPA.02 Form Data
     * URL: GET /api/mapa-02/{id_sertifikasi}
     */
    public function show($id)
    {
        try {
            // 1. Fetch Data Sertifikasi with relations
            $sertifikasi = DataSertifikasiAsesi::with([
                'asesi',
                'jadwal.tuk',
                'jadwal.skema',
                'jadwal.skema.asesor',
                'jadwal.skema.kelompokPekerjaans.unitKompetensis'
            ])->find($id);

            if (!$sertifikasi) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Data Sertifikasi not found.'
                ], 404);
            }

            // 2. Fetch Existing Responses
            $mapa02Collection = Mapa02::where('id_data_sertifikasi_asesi', $id)->get();

            // Map: [ id_kelompok_pekerjaan => [ 'Nama Instrumen' => 'Nilai Potensi' ] ]
            $mapa02Map = [];
            foreach ($mapa02Collection as $item) {
                $mapa02Map[$item->id_kelompok_pekerjaan][$item->instrumen_asesmen] = $item->potensi_asesi;
            }

            // 3. Structure the response
            return response()->json([
                'status' => 'success',
                'data' => [
                    'sertifikasi' => $sertifikasi,
                    'existing_responses' => $mapa02Map,
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
     * Submit FR.MAPA.02 Form Data
     * URL: POST /api/mapa-02/{id_sertifikasi}
     */
    public function store(Request $request, $id)
    {
        // 1. Validation
        $validator = Validator::make($request->all(), [
            'potensi'       => 'required|array',
            'potensi.*'     => 'required|array', // id_kp => array of instruments
            'potensi.*.*'   => 'required|in:1,2,3,4,5', // instrument => value
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
            // Loop setiap Kelompok Pekerjaan
            foreach ($request->potensi as $id_kp => $instruments) {
                // Loop setiap Instrumen dalam KP tersebut
                foreach ($instruments as $instrumen => $nilai) {
                    Mapa02::updateOrCreate(
                        [
                            'id_data_sertifikasi_asesi' => $id,
                            'id_kelompok_pekerjaan'     => $id_kp,
                            'instrumen_asesmen'         => $instrumen,
                        ],
                        [
                            'potensi_asesi' => $nilai,
                        ]
                    );
                }
            }

            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => 'Data FR.MAPA.02 saved successfully'
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

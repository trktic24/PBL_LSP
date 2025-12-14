<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DataSertifikasiAsesi;
use App\Models\IA02;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class Ia02ApiController extends Controller
{
    /**
     * Get FR.IA.02 Form Data
     * URL: GET /api/ia-02/{id_sertifikasi}
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
                'jadwal.skema.kelompokPekerjaans.UnitKompetensis'
            ])->find($id);

            if (!$sertifikasi) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Data Sertifikasi not found.'
                ], 404);
            }

            // 2. Fetch Existing Data
            $ia02 = IA02::where('id_data_sertifikasi_asesi', $id)->first();

            // 3. Structure the response
            return response()->json([
                'status' => 'success',
                'data' => [
                    'sertifikasi' => $sertifikasi,
                    'ia02' => $ia02,
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
     * Submit FR.IA.02 Form Data
     * URL: POST /api/ia-02/{id_sertifikasi}
     */
    public function store(Request $request, $id)
    {
        // 1. Validation
        $validator = Validator::make($request->all(), [
            'skenario'  => 'required|string',
            'peralatan' => 'required|string',
            'waktu'     => 'required|string|max:100',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // 2. Save Data
            IA02::updateOrCreate(
                ['id_data_sertifikasi_asesi' => $id],
                [
                    'skenario'  => $request->skenario,
                    'peralatan' => $request->peralatan,
                    'waktu'     => $request->waktu,
                ]
            );

            return response()->json([
                'status' => 'success',
                'message' => 'Data FR.IA.02 saved successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error saving data: ' . $e->getMessage()
            ], 500);
        }
    }
}

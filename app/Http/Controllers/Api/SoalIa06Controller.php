<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SoalIa06;
use App\Models\UmpanBalikIa06;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SoalIa06Controller extends Controller
{
    // =================================================================
    // BAGIAN 1: CRUD SOAL (Wajib ada jika pakai apiResource)
    // =================================================================

    /**
     * 1. GET /api/soal-ia06
     * Menampilkan semua daftar soal
     */
    public function index()
    {
        // Ambil semua data soal
        $data = SoalIa06::all();

        return response()->json([
            'success' => true,
            'message' => 'List Data Soal IA06',
            'data'    => $data
        ], 200);
    }

    /**
     * 2. POST /api/soal-ia06
     * Membuat soal baru
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'soal_ia06'          => 'required|string',
            'isi_jawaban_ia06'   => 'nullable|string', // Opsional
            'pencapaian'         => 'nullable|boolean',
            'kunci_jawaban_ia06' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $soal = SoalIa06::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Soal berhasil dibuat',
            'data'    => $soal
        ], 201);
    }

    /**
     * 3. GET /api/soal-ia06/{id}
     * Menampilkan detail 1 soal
     */
    public function show($id)
    {
        $soal = SoalIa06::find($id);

        if (!$soal) {
            return response()->json(['success' => false, 'message' => 'Soal tidak ditemukan'], 404);
        }

        return response()->json([
            'success' => true,
            'data'    => $soal
        ], 200);
    }

    /**
     * 4. PUT /api/soal-ia06/{id}
     * Mengupdate soal
     */
    public function update(Request $request, $id)
    {
        $soal = SoalIa06::find($id);

        if (!$soal) {
            return response()->json(['success' => false, 'message' => 'Soal tidak ditemukan'], 404);
        }

        $soal->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Soal berhasil diupdate',
            'data'    => $soal
        ], 200);
    }

    /**
     * 5. DELETE /api/soal-ia06/{id}
     * Menghapus soal
     */
    public function destroy($id)
    {
        $soal = SoalIa06::find($id);

        if (!$soal) {
            return response()->json(['success' => false, 'message' => 'Soal tidak ditemukan'], 404);
        }

        $soal->delete();

        return response()->json([
            'success' => true,
            'message' => 'Soal berhasil dihapus',
        ], 200);
    }

    // =================================================================
    // BAGIAN 2: FITUR UMPAN BALIK (Custom Route)
    // =================================================================

    /**
     * POST /api/soal-ia06/umpan-balik
     * Menyimpan umpan balik untuk Asesi
     */
    public function storeUmpanBalikAsesi(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_data_sertifikasi_asesi' => 'required|exists:data_sertifikasi_asesi,id_data_sertifikasi_asesi',
            'umpan_balik'               => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        // Cek apakah sudah ada feedback sebelumnya (Update if exists)
        $cekExisting = UmpanBalikIa06::where('id_data_sertifikasi_asesi', $request->id_data_sertifikasi_asesi)->first();

        if ($cekExisting) {
            $cekExisting->update(['umpan_balik' => $request->umpan_balik]);
            $data = $cekExisting;
            $msg = 'Umpan balik diperbarui';
        } else {
            $data = UmpanBalikIa06::create([
                'id_data_sertifikasi_asesi' => $request->id_data_sertifikasi_asesi,
                'umpan_balik'               => $request->umpan_balik
            ]);
            $msg = 'Umpan balik disimpan';
        }

        return response()->json([
            'success' => true,
            'message' => $msg,
            'data'    => $data
        ], 201);
    }

    /**
     * GET /api/soal-ia06/umpan-balik/{id_asesi}
     * Melihat umpan balik milik asesi tertentu
     */
    public function getUmpanBalikAsesi($id_asesi)
    {
        $data = UmpanBalikIa06::where('id_data_sertifikasi_asesi', $id_asesi)->first();

        if (!$data) {
            return response()->json(['success' => false, 'message' => 'Belum ada umpan balik'], 404);
        }

        return response()->json([
            'success' => true,
            'data'    => $data
        ], 200);
    }
}
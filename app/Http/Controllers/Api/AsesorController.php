<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Asesor;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class AsesorController extends Controller
{
    /**
     * GET /api/asesor
     */
    public function index()
    {
        try {
            $asesors = Asesor::with(['user:id_user,email'])->get();

            return response()->json([
                'status' => 'success',
                'message' => 'Data asesor berhasil diambil',
                'data' => $asesors
            ], 200);

        } catch (\Exception $e) {
            Log::error('API Error (Asesor Index): ' . $e->getMessage());

            return response()->json([
                'status' => 'error',
                'message' => 'Gagal mengambil data asesor.'
            ], 500);
        }
    }

    /**
     * POST /api/asesor
     */
    public function store(Request $request)
    {
        $validator2 = Validator::make($request->all(), [
            'user_id'           => 'required|exists:users,id_user',
            'nomor_regis'       => 'required|unique:asesor,nomor_regis',
            'nama_lengkap'      => 'required|string|max:255',
            'nik'               => 'required|size:16|unique:asesor,nik',
            'tempat_lahir'      => 'required',
            'tanggal_lahir'     => 'required|date',
            'jenis_kelamin'     => 'required|in:Laki-laki,Perempuan',
            'kebangsaan'        => 'required',
            'pekerjaan'         => 'required',
            'alamat_rumah'      => 'required',
            'kode_pos'          => 'required',
            'kabupaten_kota'    => 'required',
            'provinsi'          => 'required',
            'nomor_hp'          => 'required',
            'NPWP'              => 'required',
            'nama_bank'         => 'required',
            'norek'             => 'required',
            'ktp'                   => 'required|file',
            'pas_foto'              => 'required|file',
            'NPWP_foto'             => 'required|file',
            'rekening_foto'         => 'required|file',
            'CV'                    => 'required|file',
            'ijazah'                => 'required|file',
            'sertifikat_asesor'     => 'required|file',
            'sertifikasi_kompetensi'=> 'required|file',
            'tanda_tangan'          => 'required|file',
        ]);

        if ($validator2->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validasi gagal',
                'errors' => $validator2->errors()
            ], 422);
        }

        try {
            $fileFields = [
                'ktp', 'pas_foto', 'NPWP_foto', 'rekening_foto',
                'CV', 'ijazah', 'sertifikat_asesor',
                'sertifikasi_kompetensi', 'tanda_tangan'
            ];

            $data = $request->all();

            foreach ($fileFields as $field) {
                if ($request->hasFile($field)) {
                    $data[$field] = $request->file($field)->store('asesor', 'public');
                }
            }

            $asesor = Asesor::create($data);

            return response()->json([
                'status' => 'success',
                'message' => 'Data asesor berhasil ditambahkan',
                'data' => $asesor
            ], 201);

        } catch (\Exception $e) {
            Log::error('API Error (Asesor Store): ' . $e->getMessage());

            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menambahkan asesor.'
            ], 500);
        }
    }

    /**
     * GET /api/asesor/{id}
     */
    public function show($id)
    {
        $asesor = Asesor::with(['user:id_user,email'])->find($id);

        if (!$asesor) {
            return response()->json([
                'status' => 'error',
                'message' => 'Asesor tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Detail asesor diambil',
            'data' => $asesor
        ], 200);
    }

    /**
     * PUT /api/asesor/{id}
     */
    public function update(Request $request, $id)
    {
        $asesor = Asesor::find($id);

        if (!$asesor) {
            return response()->json([
                'status' => 'error',
                'message' => 'Asesor tidak ditemukan'
            ], 404);
        }

        $validator2 = Validator::make($request->all(), [
            'nama_lengkap'      => 'sometimes|string',
            'user_id'           => 'sometimes|exists:users,id_user',
            'nik'               => 'sometimes|size:16|unique:asesor,nik,' . $id . ',id_asesor',
            'nomor_regis'       => 'sometimes|unique:asesor,nomor_regis,' . $id . ',id_asesor',
            'ktp'                   => 'sometimes|file',
            'pas_foto'              => 'sometimes|file',
            'NPWP_foto'             => 'sometimes|file',
            'rekening_foto'         => 'sometimes|file',
            'CV'                    => 'sometimes|file',
            'ijazah'                => 'sometimes|file',
            'sertifikat_asesor'     => 'sometimes|file',
            'sertifikasi_kompetensi'=> 'sometimes|file',
            'tanda_tangan'          => 'sometimes|file',
        ]);

        if ($validator2->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validasi gagal',
                'errors' => $validator2->errors()
            ], 422);
        }

        try {
            // 🔥 *KODE BARU* — hanya update field yang dikirim
            $data = $request->only([
                "nomor_regis",
                "user_id",
                "nama_lengkap",
                "nik",
                "tempat_lahir",
                "tanggal_lahir",
                "jenis_kelamin",
                "kebangsaan",
                "pekerjaan",
                "alamat_rumah",
                "kode_pos",
                "kabupaten_kota",
                "provinsi",
                "nomor_hp",
                "NPWP",
                "nama_bank",
                "norek"
            ]);

            // buang field NULL (tidak dikirim oleh Postman)
            $data = array_filter($data, fn($value) => !is_null($value));

            // FILE UPDATES
            $fileFields = [
                'ktp', 'pas_foto', 'NPWP_foto', 'rekening_foto',
                'CV', 'ijazah', 'sertifikat_asesor',
                'sertifikasi_kompetensi', 'tanda_tangan'
            ];

            foreach ($fileFields as $field) {
                if ($request->hasFile($field)) {

                    if ($asesor->$field && Storage::disk('public')->exists($asesor->$field)) {
                        Storage::disk('public')->delete($asesor->$field);
                    }

                    $data[$field] = $request->file($field)->store('asesor', 'public');
                }
            }

            $asesor->update($data);

            return response()->json([
                'status' => 'success',
                'message' => 'Data asesor berhasil diperbarui',
                'data' => $asesor
            ], 200);

        } catch (\Exception $e) {
            Log::error('API Error (Asesor Update): ' . $e->getMessage());

            return response()->json([
                'status' => 'error',
                'message' => 'Gagal memperbarui asesor.'
            ], 500);
        }
    }

    /**
     * DELETE /api/asesor/{id}
     */
    public function destroy($id)
    {
        $asesor = Asesor::find($id);

        if (!$asesor) {
            return response()->json([
                'status' => 'error',
                'message' => 'Asesor tidak ditemukan'
            ], 404);
        }

        try {
            $fileFields = [
                'ktp','pas_foto','NPWP_foto','rekening_foto',
                'CV','ijazah','sertifikat_asesor',
                'sertifikasi_kompetensi','tanda_tangan'
            ];

            foreach ($fileFields as $field) {
                if ($asesor->$field && Storage::disk('public')->exists($asesor->$field)) {
                    Storage::disk('public')->delete($asesor->$field);
                }
            }

            $asesor->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Asesor berhasil dihapus'
            ], 200);

        } catch (\Exception $e) {
            Log::error('API Error (Asesor Destroy): ' . $e->getMessage());

            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menghapus asesor.'
            ], 500);
        }
    }
}

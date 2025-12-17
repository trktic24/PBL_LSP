<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Asesi;

class AsesiController extends Controller
{
    /**
     * GET /api/v1/asesi
     * Ambil semua data asesi
     */
    public function index()
    {
        $asesi = Asesi::with(['user', 'dataPekerjaan'])->get();

        return response()->json([
            'status' => 'success',
            'message' => 'Data asesi berhasil diambil',
            'data' => $asesi
        ], 200);
    }

    /**
     * GET /api/v1/asesi/{id}
     * Ambil satu asesi
     */
    public function show($id)
    {
        $asesi = Asesi::with(['user', 'dataPekerjaan'])->find($id);

        if (!$asesi) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data asesi tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Data asesi berhasil diambil',
            'data' => $asesi
        ], 200);
    }

    /**
     * POST /api/v1/asesi
     * Tambah asesi baru
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_user' => 'required|exists:users,id_user',
            'nama_lengkap' => 'required',
            'nik' => 'required|max:16|unique:asesi,nik',
            'tempat_lahir' => 'required',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'kebangsaan' => 'nullable',
            'pendidikan' => 'required',
            'pekerjaan' => 'required',

            'alamat_rumah' => 'required',
            'kode_pos' => 'nullable',
            'kabupaten_kota' => 'required',
            'provinsi' => 'required',
            'nomor_hp' => 'required',

            'tanda_tangan' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // Upload tanda tangan
        if ($request->hasFile('tanda_tangan')) {

            $file = $request->file('tanda_tangan');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

            // Simpan ke public/images/asesi/tanda_tangan
            $file->move(public_path('images/asesi/tanda_tangan'), $filename);

            $validated['tanda_tangan'] = $filename;
        }

        $asesi = Asesi::create($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Data asesi berhasil ditambahkan',
            'data' => $asesi
        ], 201);
    }

    /**
     * PUT /api/v1/asesi/{id}
     * Update data asesi
     */
    public function update(Request $request, $id)
    {
        $asesi = Asesi::find($id);

        if (!$asesi) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data asesi tidak ditemukan'
            ], 404);
        }

        $validated = $request->validate([
            'nama_lengkap' => 'sometimes|required',
            'nik' => 'sometimes|required|max:16|unique:asesi,nik,' . $id . ',id_asesi',
            'tempat_lahir' => 'sometimes|required',
            'tanggal_lahir' => 'sometimes|required|date',
            'jenis_kelamin' => 'sometimes|required|in:Laki-laki,Perempuan',
            'kebangsaan' => 'nullable',
            'pendidikan' => 'sometimes|required',
            'pekerjaan' => 'sometimes|required',

            'alamat_rumah' => 'sometimes|required',
            'kode_pos' => 'nullable',
            'kabupaten_kota' => 'sometimes|required',
            'provinsi' => 'sometimes|required',
            'nomor_hp' => 'sometimes|required',

            'tanda_tangan' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // Ganti file tanda tangan
        if ($request->hasFile('tanda_tangan')) {

            // Hapus file lama jika ada
            if ($asesi->tanda_tangan && file_exists(public_path('images/asesi/tanda_tangan/' . $asesi->tanda_tangan))) {
                unlink(public_path('images/asesi/tanda_tangan/' . $asesi->tanda_tangan));
            }

            $file = $request->file('tanda_tangan');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

            $file->move(public_path('images/asesi/tanda_tangan'), $filename);

            $validated['tanda_tangan'] = $filename;
        }

        $asesi->update($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Data asesi berhasil diperbarui',
            'data' => $asesi
        ], 200);
    }

    /**
     * DELETE /api/v1/asesi/{id}
     * Hapus asesi
     */
    public function destroy($id)
    {
        $asesi = Asesi::find($id);

        if (!$asesi) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data asesi tidak ditemukan'
            ], 404);
        }

        // Hapus foto tanda tangan jika ada
        if ($asesi->tanda_tangan && file_exists(public_path('images/asesi/tanda_tangan/' . $asesi->tanda_tangan))) {
            unlink(public_path('images/asesi/tanda_tangan/' . $asesi->tanda_tangan));
        }

        $asesi->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Data asesi berhasil dihapus'
        ], 200);
    }
}


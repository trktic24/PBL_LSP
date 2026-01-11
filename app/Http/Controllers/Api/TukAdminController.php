<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MasterTUK;
use Illuminate\Support\Facades\Storage;

class TukAdminController extends Controller
{
    /**
     * GET /api/v1/tuk
     * Mengambil semua data TUK
     */
    public function index()
    {
        $tuks = MasterTUK::all();

        return response()->json([
            'status' => 'success',
            'message' => 'Data TUK berhasil diambil',
            'data' => $tuks
        ], 200);
    }

    /**
     * POST /api/v1/tuk
     * Menambah data TUK baru
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_lokasi' => 'required',
            'alamat_tuk' => 'required',
            'kontak_tuk' => 'required',
            'foto_tuk' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'link_gmap' => 'nullable',
        ]);

        // -----------------------------------
        // UPLOAD FOTO (REFACORTED)
        // -----------------------------------
        if ($request->hasFile('foto_tuk')) {
            $path = $request->file('foto_tuk')->store('tuk', 'public');
            $validated['foto_tuk'] = $path;
        }

        $tuk = MasterTUK::create($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Data TUK berhasil ditambahkan',
            'data' => $tuk
        ], 201);
    }

    /**
     * GET /api/v1/tuk/{id}
     * Detail TUK
     */
    public function show($id)
    {
        $tuk = MasterTUK::find($id);

        if (!$tuk) {
            return response()->json([
                'status' => 'error',
                'message' => 'TUK tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $tuk
        ]);
    }

    /**
     * PUT /api/v1/tuk/{id}
     * Update TUK (termasuk ganti foto)
     */
    public function update(Request $request, $id)
    {
        $tuk = MasterTUK::find($id);

        if (!$tuk) {
            return response()->json([
                'status' => 'error',
                'message' => 'TUK tidak ditemukan'
            ], 404);
        }

        $validated = $request->validate([
            'nama_lokasi' => 'sometimes',
            'alamat_tuk' => 'sometimes',
            'kontak_tuk' => 'sometimes',
            'link_gmap' => 'sometimes',
            'foto_tuk' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // -----------------------------------
        // UPDATE FOTO (hapus lama + simpan baru)
        // -----------------------------------
        if ($request->hasFile('foto_tuk')) {

            // Hapus file lama jika ada
            if ($tuk->foto_tuk) {
                Storage::disk('public')->delete($tuk->foto_tuk);
            }

            // Upload file baru
            $path = $request->file('foto_tuk')->store('tuk', 'public');
            $validated['foto_tuk'] = $path;
        }

        $tuk->update($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Data TUK berhasil diupdate',
            'data' => $tuk
        ]);
    }

    /**
     * DELETE /api/v1/tuk/{id}
     * Delete TUK (hapus foto juga)
     */
    public function destroy($id)
    {
        $tuk = MasterTUK::find($id);

        if (!$tuk) {
            return response()->json([
                'status' => 'error',
                'message' => 'TUK tidak ditemukan'
            ], 404);
        }

        // Hapus foto jika ada
        if ($tuk->foto_tuk) {
            Storage::disk('public')->delete($tuk->foto_tuk);
        }

        $tuk->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Data TUK berhasil dihapus'
        ]);
    }
}

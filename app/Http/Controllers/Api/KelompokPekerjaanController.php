<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\KelompokPekerjaan;

class KelompokPekerjaanController extends Controller
{
    // GET: /api/v1/kelompokpekerjaan
    public function index()
    {
        // Mengambil semua kelompok beserta nama skemanya
        $kelompok = KelompokPekerjaan::with('skema')->get();

        return response()->json([
            'status' => 'success',
            'data' => $kelompok
        ]);
    }

    // POST: /api/v1/kelompokpekerjaan
    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_skema' => 'required|exists:skema,id_skema',
            'nama_kelompok_pekerjaan' => 'required|string|max:255',
        ]);

        $kelompok = KelompokPekerjaan::create($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Kelompok Pekerjaan berhasil ditambahkan',
            'data' => $kelompok
        ], 201);
    }

    // GET: /api/v1/kelompokpekerjaan/{id}
    public function show($id)
    {
        // Menampilkan detail kelompok beserta unit kompetensi di dalamnya
        $kelompok = KelompokPekerjaan::with(['skema', 'unitKompetensi'])->findOrFail($id);

        return response()->json([
            'status' => 'success',
            'data' => $kelompok
        ]);
    }

    // PUT: /api/v1/kelompokpekerjaan/{id}
    public function update(Request $request, $id)
    {
        $kelompok = KelompokPekerjaan::findOrFail($id);

        $validated = $request->validate([
            'id_skema' => 'required|exists:skema,id_skema',
            'nama_kelompok_pekerjaan' => 'required|string|max:255',
        ]);

        $kelompok->update($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Kelompok Pekerjaan berhasil diperbarui',
            'data' => $kelompok
        ]);
    }

    // DELETE: /api/v1/kelompokpekerjaan/{id}
    public function destroy($id)
    {
        $kelompok = KelompokPekerjaan::findOrFail($id);
        $kelompok->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Kelompok Pekerjaan berhasil dihapus'
        ]);
    }
}
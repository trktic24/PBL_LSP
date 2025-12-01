<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UnitKompetensi;

class UnitKompetensiController extends Controller
{
    // GET: /api/v1/unitkompetensi
    public function index()
    {
        $units = UnitKompetensi::with('kelompokPekerjaan')->get();

        return response()->json([
            'status' => 'success',
            'data' => $units
        ]);
    }

    // POST: /api/v1/unitkompetensi
    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_kelompok_pekerjaan' => 'required|exists:kelompok_pekerjaan,id_kelompok_pekerjaan',
            'kode_unit' => 'required|string|unique:unit_kompetensi,kode_unit|max:50',
            'judul_unit' => 'required|string|max:255',
            'urutan' => 'nullable|integer',
        ]);

        $unit = UnitKompetensi::create($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Unit Kompetensi berhasil ditambahkan',
            'data' => $unit
        ], 201);
    }

    // GET: /api/v1/unitkompetensi/{id}
    public function show($id)
    {
        $unit = UnitKompetensi::with('kelompokPekerjaan.skema')->findOrFail($id);

        return response()->json([
            'status' => 'success',
            'data' => $unit
        ]);
    }

    // PUT: /api/v1/unitkompetensi/{id}
    public function update(Request $request, $id)
    {
        $unit = UnitKompetensi::findOrFail($id);

        $validated = $request->validate([
            'id_kelompok_pekerjaan' => 'required|exists:kelompok_pekerjaan,id_kelompok_pekerjaan',
            // Unique validation ignoring current ID
            'kode_unit' => 'required|string|max:50|unique:unit_kompetensi,kode_unit,' . $id . ',id_unit_kompetensi',
            'judul_unit' => 'required|string|max:255',
            'urutan' => 'nullable|integer',
        ]);

        $unit->update($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Unit Kompetensi berhasil diperbarui',
            'data' => $unit
        ]);
    }

    // DELETE: /api/v1/unitkompetensi/{id}
    public function destroy($id)
    {
        $unit = UnitKompetensi::findOrFail($id);
        $unit->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Unit Kompetensi berhasil dihapus'
        ]);
    }
}
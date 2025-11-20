<?php

namespace App\Http\Controllers;

use App\Models\UmpanBalik;
use Illuminate\Http\Request;

class UmpanBalikController extends Controller
{
    public function index()
    {
        return response()->json(
            UmpanBalik::with('dataSertifikasiAsesi')->get()
        );
    }

    public function show($id)
    {
        return response()->json(
            UmpanBalik::with('dataSertifikasiAsesi')->findOrFail($id)
        );
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_data_sertifikasi_asesi' => 'required|exists:data_sertifikasi_asesi,id_data_sertifikasi_asesi',
            'penjelasan_banding'        => 'nullable|boolean',
            'diskusi_dengan_asesor'     => 'nullable|boolean',
            'melibatkan_orang_lain'     => 'nullable|boolean',
            'alasan_banding'            => 'nullable|string',
        ]);

        $item = UmpanBalik::create($validated);

        return response()->json([
            'message' => 'Umpan Balik berhasil dibuat',
            'data' => $item
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $item = UmpanBalik::findOrFail($id);

        $validated = $request->validate([
            'id_data_sertifikasi_asesi' => 'nullable|exists:data_sertifikasi_asesi,id_data_sertifikasi_asesi',
            'penjelasan_banding'        => 'nullable|boolean',
            'diskusi_dengan_asesor'     => 'nullable|boolean',
            'melibatkan_orang_lain'     => 'nullable|boolean',
            'alasan_banding'            => 'nullable|string',
        ]);

        $item->update($validated);

        return response()->json([
            'message' => 'Umpan Balik berhasil diperbarui',
            'data' => $item
        ]);
    }

    public function destroy($id)
    {
        $item = UmpanBalik::findOrFail($id);
        $item->delete();

        return response()->json(['message' => 'Umpan Balik berhasil dihapus']);
    }
}
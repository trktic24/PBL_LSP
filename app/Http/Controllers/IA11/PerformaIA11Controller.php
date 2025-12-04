<?php

namespace App\Http\Controllers\IA11;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\IA11\PerformaIA11;
use Illuminate\Validation\Rule;

class PerformaIA11Controller extends Controller
{
    /**
     * Menampilkan semua daftar item performa (INDEX).
     */
    public function index()
    {
        $performa = PerformaIA11::all();
        return response()->json($performa);
    }

    /**
     * Menyimpan item performa baru (STORE).
     */
    public function store(Request $request)
    {
        $request->validate([
            'deskripsi_performa' => 'required|string|max:500|unique:performa_ia11,deskripsi_performa',
        ]);

        $performa = PerformaIA11::create($request->all());

        return response()->json(['message' => 'Item performa berhasil ditambahkan.', 'data' => $performa], 201);
    }

    /**
     * Menampilkan detail satu item performa (SHOW).
     */
    public function show($id)
    {
        $performa = PerformaIA11::find($id);

        if (!$performa) {
            return response()->json(['message' => 'Item performa tidak ditemukan.'], 404);
        }

        return response()->json($performa);
    }

    /**
     * Memperbarui item performa yang ada (UPDATE).
     */
    public function update(Request $request, $id)
    {
        $performa = PerformaIA11::find($id);

        if (!$performa) {
            return response()->json(['message' => 'Item performa tidak ditemukan.'], 404);
        }

        $request->validate([
            'deskripsi_performa' => ['required', 'string', 'max:500', Rule::unique('performa_ia11')->ignore($performa->id_performa_ia11, 'id_performa_ia11')],
        ]);

        $performa->update($request->all());

        return response()->json(['message' => 'Item performa berhasil diperbarui.', 'data' => $performa]);
    }

    /**
     * Menghapus item performa (DESTROY).
     */
    public function destroy($id)
    {
        $performa = PerformaIA11::find($id);

        if (!$performa) {
            return response()->json(['message' => 'Item performa tidak ditemukan.'], 404);
        }

        // Catatan: Pastikan database tidak menolak penghapusan karena Foreign Key yang aktif!
        $performa->delete();

        return response()->json(['message' => 'Item performa berhasil dihapus.'], 200);
    }
}
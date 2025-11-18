<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\StrukturOrganisasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class StrukturOrganisasiController extends Controller
{
    public function index()
    {
        return response()->json(StrukturOrganisasi::all());
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama'    => 'required|string|max:255',
            'jabatan' => 'required|string|max:255',
            'gambar'  => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $data = $request->only(['nama', 'jabatan']);

        if ($request->hasFile('gambar')) {
            $path = $request->file('gambar')->store('public/struktur');
            $data['gambar'] = basename($path);
        }

        $struktur = StrukturOrganisasi::create($data);

        return response()->json(['message' => 'Berhasil', 'data' => $struktur], 201);
    }

    public function update(Request $request, $id)
    {
        $struktur = StrukturOrganisasi::find($id);
        if (!$struktur) return response()->json(['message' => 'Not found'], 404);

        // Validasi (Tidak perlu validasi kode unik lagi)
        $validator = Validator::make($request->all(), [
            'nama'    => 'sometimes|required|string',
            'jabatan' => 'sometimes|required|string',
            'gambar'  => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($validator->fails()) return response()->json($validator->errors(), 422);

        $data = $request->only(['nama', 'jabatan']);

        if ($request->hasFile('gambar')) {
            if ($struktur->gambar) Storage::delete('public/struktur/' . $struktur->gambar);
            $path = $request->file('gambar')->store('public/struktur');
            $data['gambar'] = basename($path);
        }

        $struktur->update($data);
        return response()->json(['message' => 'Updated', 'data' => $struktur]);
    }

    // DELETE: Hapus data
    public function destroy($id)
    {
        $struktur = StrukturOrganisasi::find($id);
        if (!$struktur) return response()->json(['message' => 'Data tidak ditemukan'], 404);

        if ($struktur->gambar) {
            Storage::delete('public/struktur/' . $struktur->gambar);
        }

        $struktur->delete();
        return response()->json(['message' => 'Data berhasil dihapus']);
    }
}
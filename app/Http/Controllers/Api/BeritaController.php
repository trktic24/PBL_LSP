<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Berita;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage; // Penting untuk file upload

class BeritaController extends Controller
{
    /**
     * READ (Semua): Mengambil semua data berita.
     * [GET] /api/berita
     */
    public function index()
    {
        // Ambil berita terbaru di urutan pertama
        $beritas = Berita::latest()->get();
        return response()->json($beritas);
    }

    /**
     * CREATE: Menyimpan data berita baru.
     * [POST] /api/berita
     */
    public function store(Request $request)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'judul' => 'required|string|max:255',
            'isi' => 'required|string',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048' // 2MB Max
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422); // 422 Unprocessable Entity
        }

        $data = $request->only(['judul', 'isi']);

        // Cek jika ada file gambar di-upload
        if ($request->hasFile('gambar')) {
            // Simpan gambar di 'storage/app/public/berita'
            // 'berita' adalah nama folder di dalam 'public'
            $path = $request->file('gambar')->store('public/berita');
            
            // Simpan hanya nama filenya (setelah 'public/')
            $data['gambar'] = basename($path);
        }

        $berita = Berita::create($data);

        return response()->json($berita, 201); // 201 Created
    }

    /**
     * READ (Spesifik): Mengambil satu data berita.
     * [GET] /api/berita/{id}
     */
    public function show($id)
    {
        $berita = Berita::find($id);

        if (!$berita) {
            return response()->json(['message' => 'Berita tidak ditemukan'], 404);
        }

        return response()->json($berita);
    }

    /**
     * UPDATE: Memperbarui data berita.
     * [POST] /api/berita/{id}  (dengan _method: 'PUT' atau 'PATCH')
     */
    public function update(Request $request, $id)
    {
        $berita = Berita::find($id);

        if (!$berita) {
            return response()->json(['message' => 'Berita tidak ditemukan'], 404);
        }

        // Validasi
        $validator = Validator::make($request->all(), [
            'judul' => 'sometimes|required|string|max:255',
            'isi' => 'sometimes|required|string',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $data = $request->only(['judul', 'isi']);

        // Cek jika ada gambar baru
        if ($request->hasFile('gambar')) {
            // Hapus gambar lama (jika ada)
            if ($berita->gambar) {
                Storage::delete('public/berita/' . $berita->gambar);
            }

            // Simpan gambar baru
            $path = $request->file('gambar')->store('public/berita');
            $data['gambar'] = basename($path);
        }

        $berita->update($data);

        return response()->json($berita);
    }

    /**
     * DELETE: Menghapus data berita.
     * [DELETE] /api/berita/{id}
     */
    public function destroy($id)
    {
        $berita = Berita::find($id);

        if (!$berita) {
            return response()->json(['message' => 'Berita tidak ditemukan'], 404);
        }

        // Hapus gambar dari storage
        if ($berita->gambar) {
            Storage::delete('public/berita/' . $berita->gambar);
        }

        // Hapus data dari database
        $berita->delete();

        return response()->json(null, 204); // 204 No Content
    }
}
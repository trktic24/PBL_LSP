<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Berita;

class BeritaController extends Controller
{
    /**
     * GET /api/v1/berita
     * Ambil semua data berita
     */
    public function index()
    {
        $berita = Berita::latest()->get();

        return response()->json([
            'status' => 'success',
            'message' => 'Data berita berhasil diambil',
            'data' => $berita
        ], 200);
    }

    /**
     * POST /api/v1/berita
     * Tambah berita baru
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'isi' => 'required|string',
            'gambar' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        // Upload gambar
        if ($request->hasFile('gambar')) {
            $file = $request->file('gambar');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('images/berita'), $filename);

            $validated['gambar'] = $filename;
        }

        $berita = Berita::create($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Berita berhasil ditambahkan',
            'data' => $berita
        ], 201);
    }

    /**
     * GET /api/v1/berita/{id}
     * Detail berita
     */
    public function show($id)
    {
        $berita = Berita::find($id);

        if (!$berita) {
            return response()->json([
                'status' => 'error',
                'message' => 'Berita tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $berita
        ]);
    }

    /**
     * PUT /api/v1/berita/{id}
     * Update berita (termasuk ganti gambar)
     */
    public function update(Request $request, $id)
    {
        $berita = Berita::find($id);

        if (!$berita) {
            return response()->json([
                'status' => 'error',
                'message' => 'Berita tidak ditemukan'
            ], 404);
        }

        $validated = $request->validate([
            'judul' => 'sometimes|string|max:255',
            'isi' => 'sometimes|string',
            'gambar' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // Update gambar
        if ($request->hasFile('gambar')) {

            // Hapus gambar lama
            if ($berita->gambar && file_exists(public_path('images/berita/' . $berita->gambar))) {
                unlink(public_path('images/berita/' . $berita->gambar));
            }

            // Upload gambar baru
            $file = $request->file('gambar');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('images/berita'), $filename);

            $validated['gambar'] = $filename;
        }

        $berita->update($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Berita berhasil diupdate',
            'data' => $berita
        ]);
    }

    /**
     * DELETE /api/v1/berita/{id}
     * Hapus berita
     */
    public function destroy($id)
    {
        $berita = Berita::find($id);

        if (!$berita) {
            return response()->json([
                'status' => 'error',
                'message' => 'Berita tidak ditemukan'
            ], 404);
        }

        // Hapus gambar
        if ($berita->gambar && file_exists(public_path('images/berita/' . $berita->gambar))) {
            unlink(public_path('images/berita/' . $berita->gambar));
        }

        $berita->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Berita berhasil dihapus'
        ]);
    }
}

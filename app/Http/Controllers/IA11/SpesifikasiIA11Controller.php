<?php

namespace App\Http\Controllers\IA11;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\IA11\SpesifikasiIA11;
use Illuminate\Validation\Rule;

class SpesifikasiIA11Controller extends Controller
{
    /**
     * Menampilkan semua daftar item spesifikasi (INDEX).
     */
    public function index()
    {
        $spesifikasi = SpesifikasiIA11::all();
        return response()->json($spesifikasi);
    }

    /**
     * Menyimpan item spesifikasi baru (STORE).
     */
    public function store(Request $request)
    {
        $request->validate([
            'deskripsi_spesifikasi' => 'required|string|max:500|unique:spesifikasi_ia11,deskripsi_spesifikasi',
        ]);

        $spesifikasi = SpesifikasiIA11::create($request->all());

        return response()->json(['message' => 'Item spesifikasi berhasil ditambahkan.', 'data' => $spesifikasi], 201);
    }

    /**
     * Menampilkan detail satu item spesifikasi (SHOW).
     */
    public function show($id)
    {
        $spesifikasi = SpesifikasiIA11::find($id);

        if (!$spesifikasi) {
            return response()->json(['message' => 'Item spesifikasi tidak ditemukan.'], 404);
        }

        return response()->json($spesifikasi);
    }

    /**
     * Memperbarui item spesifikasi yang ada (UPDATE).
     */
    public function update(Request $request, $id)
    {
        $spesifikasi = SpesifikasiIA11::find($id);

        if (!$spesifikasi) {
            return response()->json(['message' => 'Item spesifikasi tidak ditemukan.'], 404);
        }

        $request->validate([
            // Rule::unique di sini memungkinkan item saat ini diabaikan dari pengecekan unique
            'deskripsi_spesifikasi' => ['required', 'string', 'max:500', Rule::unique('spesifikasi_ia11')->ignore($spesifikasi->id_spesifikasi_ia11, 'id_spesifikasi_ia11')],
        ]);

        $spesifikasi->update($request->all());

        return response()->json(['message' => 'Item spesifikasi berhasil diperbarui.', 'data' => $spesifikasi]);
    }

    /**
     * Menghapus item spesifikasi (DESTROY).
     */
    public function destroy($id)
    {
        $spesifikasi = SpesifikasiIA11::find($id);

        if (!$spesifikasi) {
            return response()->json(['message' => 'Item spesifikasi tidak ditemukan.'], 404);
        }

        // Catatan: Pastikan database tidak menolak penghapusan karena Foreign Key yang aktif!
        // Jika ada IA11 yang sudah menggunakan spesifikasi ini, Anda harus menghapus relasi pivotnya terlebih dahulu.
        $spesifikasi->delete();

        return response()->json(['message' => 'Item spesifikasi berhasil dihapus.'], 200);
    }
}
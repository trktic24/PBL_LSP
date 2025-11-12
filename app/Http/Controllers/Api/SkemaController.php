<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Skema;
use Illuminate\Support\Facades\Storage;

class SkemaController extends Controller
{
    // Menampilkan semua skema
    public function index()
    {
        $skema = Skema::with('unitKompetensi')->get();
        return response()->json([
            'status' => 'success',
            'data' => $skema
        ]);
    }

    // Menyimpan skema baru
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_skema' => 'required|string|max:255',
            'deskripsi_skema' => 'required|string',
            'kode_unit' => 'required|string|max:255',
            'SKKNI' => 'required|file|mimes:pdf,doc,docx|max:2048',
            'gambar' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'units' => 'required|array|min:1',
            'units.*.code' => 'required|string',
        ]);

        $validated['SKKNI'] = $request->file('SKKNI')->store('public/skkni');
        $validated['gambar'] = $request->file('gambar')->store('public/gambar');

        $skema = Skema::create([
            'nama_skema' => $validated['nama_skema'],
            'deskripsi_skema' => $validated['deskripsi_skema'],
            'kode_unit' => $validated['kode_unit'],
            'SKKNI' => $validated['SKKNI'],
            'gambar' => $validated['gambar'],
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Data skema berhasil ditambahkan',
            'data' => $skema
        ], 201);
    }

    // Menampilkan detail skema
    public function show($id)
    {
        $skema = Skema::with('unitKompetensi')->findOrFail($id);

        return response()->json([
            'status' => 'success',
            'data' => $skema
        ]);
    }

    // Mengupdate data skema
    public function update(Request $request, $id)
    {
        $skema = Skema::findOrFail($id);

        $validated = $request->validate([
            'nama_skema' => 'required|string|max:255',
            'deskripsi_skema' => 'required|string',
            'kode_unit' => 'required|string|max:255',
            'SKKNI' => 'required|file|mimes:pdf,doc,docx|max:2048',
            'gambar' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'units' => 'required|array|min:1',
            'units.*.code' => 'required|string',
        ]);

        if ($skema->SKKNI) {
            Storage::delete($skema->SKKNI);
        }
        if ($skema->gambar) {
            Storage::delete($skema->gambar);
        }

        $validated['SKKNI'] = $request->file('SKKNI')->store('public/skkni');
        $validated['gambar'] = $request->file('gambar')->store('public/gambar');

        $skema->update([
            'nama_skema' => $validated['nama_skema'],
            'deskripsi_skema' => $validated['deskripsi_skema'],
            'kode_unit' => $validated['kode_unit'],
            'SKKNI' => $validated['SKKNI'],
            'gambar' => $validated['gambar'],
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Data skema berhasil diperbarui',
            'data' => $skema
        ]);
    }

    // Menghapus data skema
    public function destroy($id)
    {
        $skema = Skema::findOrFail($id);

        if ($skema->SKKNI) {
            Storage::delete($skema->SKKNI);
        }

        if ($skema->gambar) {
            Storage::delete($skema->gambar);
        }

        $skema->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Data skema berhasil dihapus'
        ]);
    }
}

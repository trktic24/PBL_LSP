<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Skema;
use Illuminate\Support\Facades\Storage;

class SkemaController extends Controller
{
    // GET semua skema
    public function index()
    {
        $skema = Skema::with('category')->get();

        return response()->json([
            'status' => 'success',
            'data' => $skema
        ]);
    }

    // POST simpan skema baru
    public function store(Request $request)
    {
        $validated = $request->validate([
            'categorie_id' => 'required|exists:categories,id',
            'nomor_skema' => 'required|string|unique:skema,nomor_skema',
            'nama_skema' => 'required|string|max:255',
            'deskripsi_skema' => 'required|string',
            'harga' => 'nullable|numeric',
            'SKKNI' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($request->hasFile('SKKNI')) {
            $validated['SKKNI'] = $request->file('SKKNI')->store('public/skkni');
        }

        if ($request->hasFile('gambar')) {
            $validated['gambar'] = $request->file('gambar')->store('public/gambar');
        }

        $skema = Skema::create($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Data skema berhasil ditambahkan',
            'data' => $skema
        ], 201);
    }

    // GET detail skema
    public function show($id)
    {
        $skema = Skema::with('category')->findOrFail($id);

        return response()->json([
            'status' => 'success',
            'data' => $skema
        ]);
    }

    // PUT update skema
    public function update(Request $request, $id)
    {
        $skema = Skema::findOrFail($id);

        $validated = $request->validate([
            'categorie_id' => 'required|exists:categories,id',
            'nomor_skema' => 'required|string|unique:skema,nomor_skema,' . $id . ',id_skema',
            'nama_skema' => 'required|string|max:255',
            'deskripsi_skema' => 'required|string',
            'harga' => 'nullable|numeric',
            'SKKNI' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Replace files
        if ($request->hasFile('SKKNI')) {
            if ($skema->SKKNI) Storage::delete($skema->SKKNI);
            $validated['SKKNI'] = $request->file('SKKNI')->store('public/skkni');
        }

        if ($request->hasFile('gambar')) {
            if ($skema->gambar) Storage::delete($skema->gambar);
            $validated['gambar'] = $request->file('gambar')->store('public/gambar');
        }

        $skema->update($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Data skema berhasil diperbarui',
            'data' => $skema
        ]);
    }

    // DELETE skema
    public function destroy($id)
    {
        $skema = Skema::findOrFail($id);

        if ($skema->SKKNI) Storage::delete($skema->SKKNI);
        if ($skema->gambar) Storage::delete($skema->gambar);

        $skema->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Data skema berhasil dihapus'
        ]);
    }
}

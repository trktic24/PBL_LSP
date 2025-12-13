<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Mitra;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class MitraController extends Controller
{
    // 1. GET (READ ALL) - TAMPILAN WEB
    public function index()
    {
        $mitras = Mitra::latest()->get();
        return view('landing_page.page_profil.mitra', compact('mitras'));
    }

    // 2. POST (CREATE)
    public function store(Request $request)
    {
        // Validasi
        $validator = Validator::make($request->all(), [
            'nama_mitra' => 'required|string|max:255',
            'url'        => 'required|url', // Validasi format URL (http/https)
            'logo'       => 'nullable|image|mimes:jpeg,png,jpg|max:2048', 
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false, 
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        // Proses Upload Logo
        $logoPath = null;
        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('mitra-logos', 'public');
        }

        // Simpan ke Database (Hanya Nama, URL, Logo)
        $mitra = Mitra::create([
            'nama_mitra' => $request->nama_mitra,
            'url'        => $request->url,
            'logo'       => $logoPath,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Mitra berhasil ditambahkan',
            'data' => $mitra
        ], 201);
    }

    // 3. GET (READ ONE)
    public function show($id)
    {
        $mitra = Mitra::find($id);
        if (!$mitra) return response()->json(['status' => false, 'message' => 'Data tidak ditemukan'], 404);
        return response()->json(['status' => true, 'data' => $mitra], 200);
    }

    // 4. UPDATE
    public function update(Request $request, $id)
    {
        $mitra = Mitra::find($id);
        if (!$mitra) return response()->json(['status' => false, 'message' => 'Data tidak ditemukan'], 404);

        $validator = Validator::make($request->all(), [
            'nama_mitra' => 'string|max:255',
            'url'        => 'url',
            'logo'       => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()], 422);
        }

        $data = $request->only(['nama_mitra', 'url']); // Ambil field yang relevan saja

        // Cek Logo Baru
        if ($request->hasFile('logo')) {
            if ($mitra->logo && Storage::disk('public')->exists($mitra->logo)) {
                Storage::disk('public')->delete($mitra->logo);
            }
            $data['logo'] = $request->file('logo')->store('mitra-logos', 'public');
        }

        $mitra->update($data);

        return response()->json([
            'status' => true,
            'message' => 'Data berhasil diupdate',
            'data' => $mitra
        ], 200);
    }

    // 5. DELETE
    public function destroy($id)
    {
        $mitra = Mitra::find($id);
        if (!$mitra) return response()->json(['status' => false, 'message' => 'Data tidak ditemukan'], 404);

        if ($mitra->logo && Storage::disk('public')->exists($mitra->logo)) {
            Storage::disk('public')->delete($mitra->logo);
        }

        $mitra->delete();
        return response()->json(['status' => true, 'message' => 'Data berhasil dihapus'], 200);
    }
}
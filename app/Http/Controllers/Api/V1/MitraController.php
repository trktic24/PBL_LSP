<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Mitra;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage; // Wajib ada untuk hapus/upload file

class MitraController extends Controller
{
    // 1. GET (READ ALL) - TAMPILAN WEB
    public function index()
    {
        // Ambil data terbaru
        $mitras = Mitra::latest()->get();
        
        // Return ke View (Halaman Web)
        return view('landing_page.page_profil.mitra', compact('mitras'));
    }

    // 2. POST (CREATE) - SIMPAN DATA + UPLOAD GAMBAR
    public function store(Request $request)
    {
        // Validasi
        $validator = Validator::make($request->all(), [
            'nama_mitra' => 'required|string|max:255',
            'alamat'     => 'required|string',
            'no_telp'    => 'required|numeric',
            'email'      => 'nullable|email',
            // Validasi gambar: wajib format gambar, maks 2MB
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
            // Simpan file di folder: storage/app/public/mitra-logos
            $logoPath = $request->file('logo')->store('mitra-logos', 'public');
        }

        // Simpan ke Database
        $mitra = Mitra::create([
            'nama_mitra' => $request->nama_mitra,
            'alamat'     => $request->alamat,
            'no_telp'    => $request->no_telp,
            'email'      => $request->email,
            'logo'       => $logoPath, // Path gambar disimpan di sini
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Data dan Logo berhasil ditambahkan',
            'data' => $mitra
        ], 201);
    }

    // 3. GET (READ ONE) - DETAIL JSON
    public function show($id)
    {
        $mitra = Mitra::find($id);

        if (!$mitra) {
            return response()->json(['status' => false, 'message' => 'Data tidak ditemukan'], 404);
        }

        return response()->json(['status' => true, 'data' => $mitra], 200);
    }

    // 4. PUT/POST (UPDATE) - UPDATE DATA + GANTI GAMBAR
    // PENTING: Di Postman, gunakan Method POST dan tambahkan key "_method" = "PUT" di Body
    public function update(Request $request, $id)
    {
        $mitra = Mitra::find($id);

        if (!$mitra) {
            return response()->json(['status' => false, 'message' => 'Data tidak ditemukan'], 404);
        }

        // Validasi
        $validator = Validator::make($request->all(), [
            'nama_mitra' => 'string|max:255',
            'alamat'     => 'string',
            'no_telp'    => 'numeric',
            'logo'       => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()], 422);
        }

        // Ambil semua data input
        $data = $request->all();

        // Cek apakah user upload logo baru?
        if ($request->hasFile('logo')) {
            // A. Hapus logo lama dari penyimpanan (jika ada)
            if ($mitra->logo && Storage::disk('public')->exists($mitra->logo)) {
                Storage::disk('public')->delete($mitra->logo);
            }
            
            // B. Upload logo baru
            $data['logo'] = $request->file('logo')->store('mitra-logos', 'public');
        }

        // Update database
        $mitra->update($data);

        return response()->json([
            'status' => true,
            'message' => 'Data berhasil diupdate',
            'data' => $mitra
        ], 200);
    }

    // 5. DELETE (HAPUS) - HAPUS DATA + FILE GAMBAR
    public function destroy($id)
    {
        $mitra = Mitra::find($id);

        if (!$mitra) {
            return response()->json(['status' => false, 'message' => 'Data tidak ditemukan'], 404);
        }

        // Hapus file gambar dari penyimpanan jika ada
        if ($mitra->logo && Storage::disk('public')->exists($mitra->logo)) {
            Storage::disk('public')->delete($mitra->logo);
        }

        // Hapus data dari database
        $mitra->delete();

        return response()->json(['status' => true, 'message' => 'Data berhasil dihapus'], 200);
    }
}
<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Skema;
use App\Models\UnitKompetensi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class SkemaController extends Controller
{
    // --- List Semua Skema ---
    public function index()
    {
        try {
            $skemas = Skema::with('unitKompetensi')->get();

            return response()->json([
                'status' => 'success',
                'message' => 'Data skema berhasil diambil',
                'data' => $skemas
            ], 200);

        } catch (\Exception $e) {
            Log::error('API Skema Index Error: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal mengambil data skema'
            ], 500);
        }
    }

    // --- Tampilkan Skema Berdasarkan ID ---
    public function show($id)
    {
        $skema = Skema::with('unitKompetensi')->find($id);

        if (!$skema) {
            return response()->json([
                'status' => 'error',
                'message' => 'Skema tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $skema
        ]);
    }

    // --- Simpan Skema Baru ---
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_skema' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'units' => 'required|array|min:1',
            'units.*.code' => 'required|string',
            'gambar_skema' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'file_skkni' => 'nullable|file|mimes:pdf|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        $validated = $validator->validated();

        $gambarPath = $request->hasFile('gambar_skema') ? $request->file('gambar_skema')->store('public/skema_images') : null;
        $skkniPath = $request->hasFile('file_skkni') ? $request->file('file_skkni')->store('public/skkni_files') : null;

        try {
            $skema = Skema::create([
                'nama_skema' => $validated['nama_skema'],
                'deskripsi_skema' => $validated['deskripsi'],
                'gambar' => $gambarPath,
                'SKKNI' => $skkniPath,
                'kode_unit' => $validated['units'][0]['code'],
            ]);

            foreach ($validated['units'] as $unit) {
                UnitKompetensi::create([
                    'id_skema' => $skema->id_skema,
                    'kode_unit' => $unit['code'],
                ]);
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Skema baru berhasil ditambahkan!',
                'data' => $skema->load('unitKompetensi')
            ], 201);

        } catch (\Exception $e) {
            Log::error('API Skema Store Error: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat menyimpan skema'
            ], 500);
        }
    }

    // --- Update Skema ---
    public function update(Request $request, $id)
    {
        $skema = Skema::find($id);
        if (!$skema) {
            return response()->json([
                'status' => 'error',
                'message' => 'Skema tidak ditemukan'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'nama_skema' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'units' => 'nullable|array',
            'units.*.code' => 'required|string',
            'gambar_skema' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'file_skkni' => 'nullable|file|mimes:pdf|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        $validated = $validator->validated();

        if ($request->hasFile('gambar_skema')) {
            if ($skema->gambar) Storage::delete(str_replace('public/', '', $skema->gambar));
            $skema->gambar = $request->file('gambar_skema')->store('public/skema_images');
        }

        if ($request->hasFile('file_skkni')) {
            if ($skema->SKKNI) Storage::delete(str_replace('public/', '', $skema->SKKNI));
            $skema->SKKNI = $request->file('file_skkni')->store('public/skkni_files');
        }

        $skema->nama_skema = $validated['nama_skema'];
        $skema->deskripsi_skema = $validated['deskripsi'];
        if(isset($validated['units'][0]['code'])) {
            $skema->kode_unit = $validated['units'][0]['code'];
        }

        $skema->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Skema berhasil diperbarui!',
            'data' => $skema->load('unitKompetensi')
        ]);
    }

    // --- Hapus Skema ---
    public function destroy($id)
    {
        $skema = Skema::find($id);
        if (!$skema) {
            return response()->json([
                'status' => 'error',
                'message' => 'Skema tidak ditemukan'
            ], 404);
        }

        if ($skema->gambar) Storage::delete(str_replace('public/', '', $skema->gambar));
        if ($skema->SKKNI) Storage::delete(str_replace('public/', '', $skema->SKKNI));
        $skema->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Skema berhasil dihapus'
        ]);
    }
}

<?php

namespace App\Http\Controllers\Api; // <-- Namespace-nya sekarang 'Api'

use App\Http\Controllers\Controller;
use App\Models\Skema;
use App\Models\UnitKompetensi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator; // Menggunakan Validator untuk API

class SkemaController extends Controller
{
    /**
     * API: Mengembalikan SEMUA data skema.
     * (Endpoint: GET /api/v1/skema)
     */
    public function index()
    {
        try {
            // 'with('unitKompetensi')' akan mengambil skema BESERTA unit-unitnya
            // (Asumsi Anda punya relasi 'unitKompetensi' di model Skema)
            $skemas = Skema::with('unitKompetensi')->get();
            
            return response()->json([
                'status' => 'success',
                'message' => 'Data skema berhasil diambil',
                'data' => $skemas
            ], 200); // 200 = OK

        } catch (\Exception $e) {
            Log::error('API Error (Skema Index): ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal mengambil data'
            ], 500);
        }
    }

    /**
     * API: Menyimpan skema baru.
     * (Endpoint: POST /api/v1/skema)
     */
    public function store(Request $request)
    {
        // 1. Validasi (Sama seperti web controller Anda, tanpa 'tanggal' & 'asesor')
        $validator = Validator::make($request->all(), [
            'nama_skema' => 'required|string|max:255',
            'gambar_skema' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'file_skkni' => 'nullable|file|mimes:pdf|max:2048',
            'deskripsi' => 'required|string',
            'units' => 'required|array|min:1',
            'units.*.code' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422); // 422 = Unprocessable Entity
        }

        $validated = $validator->validated();

        // 2. Handle Upload File
        $gambarPath = null;
        if ($request->hasFile('gambar_skema')) {
            $gambarPath = $request->file('gambar_skema')->store('public/skema_images');
        }
        $skkniPath = null;
        if ($request->hasFile('file_skkni')) {
            $skkniPath = $request->file('file_skkni')->store('public/skkni_files');
        }

        try {
            // 3. Buat Skema Utama
            $skema = Skema::create([
                'nama_skema' => $validated['nama_skema'],
                'deskripsi_skema' => $validated['deskripsi'],
                'gambar' => $gambarPath,
                'SKKNI' => $skkniPath,
                'kode_unit' => $validated['units'][0]['code'], 
            ]);

            // 4. Simpan Unit Kompetensi
            foreach ($validated['units'] as $unit) {
                UnitKompetensi::create([
                    'id_skema' => $skema->id_skema,
                    'kode_unit' => $unit['code'],
                ]);
            }
            
            // Muat relasi 'unitKompetensi' agar data baliknya lengkap
            $skema->load('unitKompetensi');

            // 5. Kembalikan data skema yang baru dibuat sebagai JSON
            return response()->json([
                'status' => 'success',
                'message' => 'Skema baru berhasil ditambahkan!',
                'data' => $skema
            ], 201); // 201 = Created

        } catch (\Exception $e) {
            Log::error('API Error (Skema Store): ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat menyimpan data.'
            ], 500);
        }
    }
}
<?php

namespace App\Http\Controllers\Api; // Pastikan namespace ini sudah benar!

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Asesor;
use Illuminate\Support\Facades\Log;

class AsesorController extends Controller
{
    /**
     * Ambil semua data asesor (API Endpoint: GET /api/asesors)
     */
    public function index()
    {
        try {
            // Ambil semua asesor beserta relasi user (untuk email) dan skema
            $asesors = Asesor::with(['user:id,email', 'skema:id,nama_skema'])->get();
            
            return response()->json([
                'status' => 'success',
                'message' => 'Data asesor berhasil diambil',
                'data' => $asesors
            ], 201);

        } catch (\Exception $e) {
            // Log error untuk debugging
            Log::error('API Error (Asesor Index): ' . $e->getMessage());
            
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal mengambil data asesor.',
                // Catatan: Biasanya detail error tidak ditampilkan di production
            ], 500);
        }
    }

    // Fungsi 'show($id)', 'store', 'update', 'destroy' bisa ditambahkan di sini
}
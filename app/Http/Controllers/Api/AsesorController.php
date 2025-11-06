<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Asesor; // Panggil model Asesor
use Illuminate\Support\Facades\Log;

class AsesorController extends Controller
{
    /**
     * API: Mengembalikan SEMUA data asesor.
     * (Endpoint: GET /api/v1/asesor)
     */
    public function index()
    {
        try {
            // Ambil semua asesor beserta relasi user (untuk email) dan skema
            $asesors = Asesor::with(['user', 'skema'])->get();
            
            return response()->json([
                'status' => 'success',
                'message' => 'Data asesor berhasil diambil',
                'data' => $asesors
            ], 200);

        } catch (\Exception $e) {
            Log::error('API Error (Asesor Index): ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal mengambil data'
            ], 500);
        }
    }

    // Anda bisa tambahkan fungsi 'show($id)', 'store', 'update' di sini nanti
}
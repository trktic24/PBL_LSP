<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tuk; // Panggil model TUK
use Illuminate\Support\Facades\Log;

class TukController extends Controller
{
    /**
     * API: Mengembalikan SEMUA data TUK.
     * (Endpoint: GET /api/v1/tuk)
     */
    public function index()
    {
        try {
            $tuks = Tuk::all();
            
            return response()->json([
                'status' => 'success',
                'message' => 'Data TUK berhasil diambil',
                'data' => $tuks
            ], 200);

        } catch (\Exception $e) {
            Log::error('API Error (TUK Index): ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal mengambil data'
            ], 500);
        }
    }
}
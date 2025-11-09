<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Asesor;
use Illuminate\Http\Request;

class AsesorTableApiController extends Controller
{
    /**
     * Endpoint: GET /api/asesor
     * Mengambil seluruh data asesor.
     */
    public function index()
    {
        $data = Asesor::all();

        return response()->json([
            'status' => 'success',
            'count' => $data->count(),
            'data' => $data
        ]);
    }

    /**
     * Endpoint: GET /api/asesor/search?q=
     * Pencarian berdasarkan nama asesor atau nomor registrasi.
     */
    public function search(Request $request)
    {
        $q = $request->query('q');

        // Jika query kosong, kembalikan hasil kosong
        if (!$q) {
            return response()->json([
                'status' => 'success',
                'message' => 'Parameter pencarian tidak diberikan.',
                'data' => []
            ]);
        }

        $data = Asesor::where('nama', 'LIKE', "%$q%")
                      ->orWhere('no_reg', 'LIKE', "%$q%")
                      ->get();

        return response()->json([
            'status' => 'success',
            'query' => $q,
            'count' => $data->count(),
            'data' => $data
        ]);
    }
}

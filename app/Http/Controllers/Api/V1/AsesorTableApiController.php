<?php

namespace App\Http\Controllers\Api\V1;

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
}

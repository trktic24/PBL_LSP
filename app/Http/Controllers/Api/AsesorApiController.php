<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Asesor;
use Illuminate\Http\Request;

class AsesorApiController extends Controller
{
    public function show($id)
    {
        $asesor = Asesor::with('user', 'skema')->find($id);

        if (!$asesor) {
            return response()->json([
                'success' => false,
                'message' => 'Asesor tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $asesor
        ]);
    }
}

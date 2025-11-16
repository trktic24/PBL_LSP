<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tuk;

class TukController extends Controller
{
    /**
     * GET /api/v1/tuk
     * Mengambil semua data TUK
     */
    public function index()
    {
        $tuks = Tuk::all();

        return response()->json([
            'status' => 'success',
            'message' => 'Data TUK berhasil diambil',
            'data' => $tuks
        ], 200);
    }

    /**
     * POST /api/v1/tuk
     * Menambah data TUK baru
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_lokasi' => 'required',
            'alamat_tuk' => 'required',
            'kontak_tuk' => 'required',
            'foto_tuk' => 'nullable',
            'link_gmap' => 'nullable',
        ]);

        $tuk = Tuk::create($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Data TUK berhasil ditambahkan',
            'data' => $tuk
        ], 201);
    }
}

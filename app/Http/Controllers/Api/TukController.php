<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MasterTUK; // Import Model MasterTuk Anda
use Illuminate\Http\Request;

class TukController extends Controller
{
    /**
     * Display a listing of the resource.
     * Mengambil daftar semua TUK.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        // Mengambil semua data MasterTuk
        $tuks = MasterTuk::all();

        // Mengembalikan data dalam format JSON
        return response()->json([
            'status' => 'success',
            'message' => 'Daftar Tempat Uji Kompetensi berhasil diambil.',
            'data' => $tuks,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        // Logika untuk validasi dan penyimpanan TUK baru
        // ...
        return response()->json(['message' => 'Not yet implemented'], 501);
    }

    /**
     * Display the specified resource.
     * Mengambil detail satu TUK berdasarkan ID, memuat relasi jadwal.
     *
     * @param  string  $id (id_tuk)
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(string $id)
    {
        // Mengambil data TUK berdasarkan primary key (id_tuk)
        // Menggunakan with('jadwal') untuk memuat relasi jadwal
        $tuk = MasterTuk::with('jadwal')->find($id);

        if (!$tuk) {
            return response()->json([
                'status' => 'error',
                'message' => 'Tempat Uji Kompetensi tidak ditemukan.',
                'data' => null,
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Detail Tempat Uji Kompetensi berhasil diambil.',
            'data' => $tuk,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, string $id)
    {
        // Logika update akan ditulis di sini
        // ...
        return response()->json(['message' => 'Not yet implemented'], 501);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  string  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(string $id)
    {
        // Logika delete akan ditulis di sini
        // ...
        return response()->json(['message' => 'Not yet implemented'], 501);
    }
}
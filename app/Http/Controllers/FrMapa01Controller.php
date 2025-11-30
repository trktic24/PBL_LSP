<?php

namespace App\Http\Controllers;

use App\Models\FrMapa01;
use Illuminate\Http\Request;

class FrMapa01Controller extends Controller
{
    public function index()
    {
        return view('frontend.FR_MAPA_01');
    }

    public function store(Request $request)
    {
        // 1. Ambil semua data input
        // Kita buang _token dan _method karena tidak perlu disimpan di DB
        $data = $request->except(['_token', '_method']);

        // 2. Simpan ke database
        // Pastikan Model sudah di-set $guarded = ['id'] atau $fillable lengkap
        FrMapa01::create($data);

        // 3. Kembali ke halaman form dengan pesan sukses
        return redirect()->route('mapa01.index')->with('success', 'Data FR.MAPA.01 berhasil disimpan!');
    }
}
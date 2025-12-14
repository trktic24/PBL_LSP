<?php

namespace App\Http\Controllers;

use App\Models\FrAk06;
use Illuminate\Http\Request;

class FrAk06Controller extends Controller
{
    public function index()
    {
        // Ganti 'frontend.fr_ak_06' sesuai nama file blade Anda nanti
        return view('frontend.fr_ak_06'); 
    }

    public function store(Request $request)
    {
        // 1. Ambil data kecuali token
        $data = $request->except(['_token', '_method']);

        // 2. Simpan ke database
        FrAk06::create($data);

        // 3. Redirect dengan pesan sukses
        return redirect()->back()->with('success', 'Formulir FR.AK.06 berhasil disimpan!');
    }
}
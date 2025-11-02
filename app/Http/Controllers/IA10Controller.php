<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ia10;

class IA10Controller extends Controller
{
    // Fungsi untuk menampilkan form
    public function create()
    {
        return view('frontend.FR_IA_10');
    }

    // Fungsi untuk MENYIMPAN data
    public function store(Request $request)
    {
        // Validasi (Contoh: mewajibkan nama supervisor)
        $request->validate([
            'supervisor_name' => 'required|string|max:255',
            'relation' => 'required|string',
        ]);

        // Ambil semua data dari formulir
        $data = $request->all();

        // Ambil nama asesi & asesor dari form
        // (Kita buat 'nama_asesi' dari field 'asesi' di form, dst.)
        $data['nama_asesi'] = $request->asesi;
        $data['nama_asesor'] = $request->asesor;

        // Simpan data ke database
        VerifikasiPihakKetiga::create($data);

        // Kembalikan ke halaman form dengan pesan sukses
        return redirect()->back()->with('success', 'Formulir FR.IA.10 berhasil dikirim!');
    }
}

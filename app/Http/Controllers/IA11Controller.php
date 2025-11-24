<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ia11;
use Carbon\Carbon;

class Ia11Controller extends Controller
{
    public function create()
    {
        $data = [
            'judul_skema' => 'Web Developer Profesional',
            'nomor_skema' => 'SKM-WD-01',
            'nama_asesor' => 'Budi Santoso',
            'nama_asesi' => 'Siti Aminah',
            'tanggal_sekarang' => Carbon::now()->toDateString(),
        ];

        return view('frontend.FR_IA_11', $data);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_data_sertifikasi_asesi' => 'nullable|integer',
            'id_spesifikasi_produk_ia11' => 'nullable|integer',

            'rancangan_produk' => 'nullable|string',
            'nama_produk' => 'required|string',

            // ENUM harus begini
            'standar_industri' => 'nullable|in:memenuhi,tidak memenuhi',

            'tanggal_pengoperasian' => 'nullable|date',

            'gambar_produk' => 'nullable|string'
        ]);

        Ia11::create([
            'id_data_sertifikasi_asesi' => $validated['id_data_sertifikasi_asesi'] ?? null,
            'id_spesifikasi_produk_ia11' => $validated['id_spesifikasi_produk_ia11'] ?? null,
            'rancangan_produk' => $validated['rancangan_produk'] ?? $validated['nama_produk'],
            'nama_produk' => $validated['nama_produk'],
            'standar_industri' => $validated['standar_industri'] ?? null,
            'tanggal_pengoperasian' => $validated['tanggal_pengoperasian'] ?? null,
            'gambar_produk' => $validated['gambar_produk'] ?? null
        ]);

        return back()->with('success', 'FR.IA.11 berhasil disimpan!');
    }
}

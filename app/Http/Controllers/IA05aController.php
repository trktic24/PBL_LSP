<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\IA05a; // Kita akan buat model ini
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class IA05aController extends Controller
{
    /**
     * Menampilkan halaman form.
     * Asumsi file Blade Anda ada di: resources/views/asesmen/fr-ia-05a.blade.php
     */
    public function show()
    {
        // Ganti 'asesmen.fr-ia-05a' sesuai lokasi file Blade Anda
        return view('frontend.FR_IA_05_A'); 
    }

    /**
     * Menyimpan data dari form.
     */
    public function store(Request $request)
    {
        // 1. Validasi Input
        $validator = Validator::make($request->all(), [
            'q1' => 'required|string',
            'q1a' => 'required|string',
            'q1b' => 'required|string',
            'q1c' => 'required|string',
            'q1d' => 'required|string',
            'q2' => 'required|string',
            'q2a' => 'required|string',
            'q2b' => 'required|string',
            'q2c' => 'required|string',
            'q2d' => 'required|string',
            'q3' => 'required|string',
            'q3a' => 'required|string',
            'q3b' => 'required|string',
            'q3c' => 'required|string',
            'q3d' => 'required|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                        ->withErrors($validator)
                        ->withInput();
        }

        // 2. Simpan Data ke Database
        // Ini adalah contoh sederhana. Anda bisa membuatnya lebih dinamis.
        try {
            Pertanyaan::create([
                'pertanyaan' => $request->q1,
                'opsi_a' => $request->q1a,
                'opsi_b' => $request->q1b,
                'opsi_c' => $request->q1c,
                'opsi_d' => $request->q1d,
            ]);

            Pertanyaan::create([
                'pertanyaan' => $request->q2,
                'opsi_a' => $request->q2a,
                'opsi_b' => $request->q2b,
                'opsi_c' => $request->q2c,
                'opsi_d' => $request->q2d,
            ]);

            Pertanyaan::create([
                'pertanyaan' => $request->q3,
                'opsi_a' => $request->q3a,
                'opsi_b' => $request->q3b,
                'opsi_c' => $request->q3c,
                'opsi_d' => $request->q3d,
            ]);

        } catch (\Exception $e) {
            // Catat error jika terjadi
            Log::error('Gagal menyimpan pertanyaan: ' . $e->getMessage());
            return redirect()->back()
                         ->withInput()
                         ->withErrors(['error' => 'Terjadi kesalahan saat menyimpan data.']);
        }

        // 3. Redirect kembali dengan pesan sukses
        // Ini akan memicu @if (session('success')) di Blade Anda
        return redirect()->back()->with('success', 'Data pertanyaan berhasil disimpan!');
    }
}
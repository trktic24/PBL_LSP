<?php

namespace App\Http\Controllers;

use App\Models\Skema;
use App\Models\UnitKompetensi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log; 

class SkemaController extends Controller
{
    /**
     * Menyimpan skema baru ke database.
     */
    public function store(Request $request)
    {
        // 1. Validasi input (Sesuai dengan form Anda)
        $validated = $request->validate([
            'nama_skema' => 'required|string|max:255',
            'gambar_skema' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'file_skkni' => 'nullable|file|mimes:pdf|max:2048',
            'deskripsi' => 'required|string',
            'units' => 'required|array|min:1',
            'units.*.code' => 'required|string',
        ]);

        // 2. Handle Upload File
        $gambarPath = null;
        if ($request->hasFile('gambar_skema')) {
            $gambarPath = $request->file('gambar_skema')->store('public/skema_images');
        }

        $skkniPath = null;
        if ($request->hasFile('file_skkni')) {
            $skkniPath = $request->file('file_skkni')->store('public/skkni_files');
        }

        try {
            // 3. Buat Skema Utama (Sesuaikan dengan kolom migrasi 'skema')
            $skema = Skema::create([
                'nama_skema' => $validated['nama_skema'],
                'deskripsi_skema' => $validated['deskripsi'], // Form 'deskripsi' -> DB 'deskripsi_skema'
                'gambar' => $gambarPath,           // Form 'gambar_skema' -> DB 'gambar'
                'SKKNI' => $skkniPath,              // Form 'file_skkni' -> DB 'SKKNI'
                
                // Migrasi Anda punya kolom 'kode_unit'. Kita isi dengan unit pertama.
                'kode_unit' => $validated['units'][0]['code'], 
            ]);

            // 4. Simpan Unit Kompetensi (Relasi)
            foreach ($validated['units'] as $unit) {
                UnitKompetensi::create([
                    'id_skema' => $skema->id_skema, // Hubungkan ke skema baru
                    'kode_unit' => $unit['code'],
                ]);
            }

            // 5. Kembali ke halaman master skema dengan pesan sukses
            return redirect()->route('master_skema')->with('success', 'Skema baru berhasil ditambahkan!');

        } catch (\Exception $e) {
            Log::error('Gagal menyimpan skema: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
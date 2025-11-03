<?php

namespace App\Http\Controllers;

use App\Models\Skema;
use App\Models\UnitKompetensi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log; // Untuk debugging

class SkemaController extends Controller
{
    /**
     * Menyimpan skema baru ke database.
     */
    public function store(Request $request)
    {
        // 1. Validasi semua input
        $validated = $request->validate([
            'nama_skema' => 'required|string|max:255',
            'gambar_skema' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // 2MB max
            'file_skkni' => 'nullable|file|mimes:pdf|max:2048', // 2MB max
            'deskripsi' => 'required|string',
            'units' => 'required|array|min:1',
            'units.*.code' => 'required|string', // Pastikan setiap unit punya kode
            'tanggal' => 'required|date',
            'asesor' => 'required', // Sesuaikan validasi jika perlu
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
            // 3. Buat Skema Utama
            $skema = Skema::create([
                'nama_skema' => $validated['nama_skema'],
                'deskripsi' => $validated['deskripsi'],
                'tanggal_pelaksanaan' => $validated['tanggal'],
                'gambar_skema' => $gambarPath,
                'file_skkni' => $skkniPath,
                // 'id_asesor' => $validated['asesor'], // Sesuaikan nama kolom
                // Tambahkan kolom lain yang relevan di sini
            ]);

            // 4. Simpan Unit Kompetensi (Relasi)
            foreach ($validated['units'] as $unit) {
                UnitKompetensi::create([
                    'id_skema' => $skema->id_skema, // Hubungkan ke skema baru
                    'kode_unit' => $unit['code'],
                    // 'judul_unit' => $unit['title'], // (Anda hapus ini, jadi di-komen)
                ]);
            }

            // 5. Kembali ke halaman master skema dengan pesan sukses
            return redirect()->route('master_skema')->with('success', 'Skema baru berhasil ditambahkan!');

        } catch (\Exception $e) {
            // Jika ada error, catat dan kembali dengan pesan error
            Log::error('Gagal menyimpan skema: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat menyimpan data. Silakan coba lagi.');
        }
    }
}
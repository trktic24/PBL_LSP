<?php

namespace App\Http\Controllers;

use App\Models\Skema;
// use App\Models\UnitKompetensi; // Dihapus karena tidak ada relasi
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log; 
use Illuminate\Support\Facades\Storage;

class SkemaController extends Controller
{
    /**
     * Menyimpan skema baru ke database.
     */
    public function store(Request $request)
    {
        // 1. Validasi input (Disesuaikan dengan migrasi skema)
        $validated = $request->validate([
            'nama_skema' => 'required|string|max:255',
            'kode_unit' => 'required|string', // Diubah dari 'units'
            'gambar_skema' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'file_skkni' => 'nullable|file|mimes:pdf|max:2048',
            'deskripsi' => 'required|string',
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
                'deskripsi_skema' => $validated['deskripsi'],
                'gambar' => $gambarPath,
                'SKKNI' => $skkniPath,
                'kode_unit' => $validated['kode_unit'], 
            ]);
            
            // 4. (Logika multi-unit dihapus)

            // 5. Kembali ke halaman master skema dengan pesan sukses
            return redirect()->route('master_skema')->with('success', 'Skema baru berhasil ditambahkan!');

        } catch (\Exception $e) {
            Log::error('Gagal menyimpan skema: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Menampilkan form untuk mengedit skema.
     */
    public function edit($id_skema)
    {
        // ==========================================================
        // PERBAIKAN: Hapus 'with('unitKompetensi')'
        // ==========================================================
        $skema = Skema::findOrFail($id_skema);
        
        return view('master.skema.edit_skema', [
            'skema' => $skema
        ]);
    }

    /**
     * Memperbarui skema di database.
     */
    public function update(Request $request, $id_skema)
    {
        $skema = Skema::findOrFail($id_skema);

        // 1. Validasi (Disesuaikan)
        $validated = $request->validate([
            'nama_skema' => 'required|string|max:255',
            'kode_unit' => 'required|string', // Diubah dari 'units'
            'gambar_skema' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'file_skkni' => 'nullable|file|mimes:pdf|max:2048',
            'deskripsi' => 'required|string',
        ]);

        // 2. Handle Update File Gambar
        $gambarPath = $skema->gambar; 
        if ($request->hasFile('gambar_skema')) {
            if ($skema->gambar) Storage::delete($skema->gambar);
            $gambarPath = $request->file('gambar_skema')->store('public/skema_images');
        }

        // 3. Handle Update File SKKNI
        $skkniPath = $skema->SKKNI;
        if ($request->hasFile('file_skkni')) {
            if ($skema->SKKNI) Storage::delete($skema->SKKNI);
            $skkniPath = $request->file('file_skkni')->store('public/skkni_files');
        }

        try {
            // 4. Update Skema Utama
            $skema->update([
                'nama_skema' => $validated['nama_skema'],
                'deskripsi_skema' => $validated['deskripsi'],
                'gambar' => $gambarPath,
                'SKKNI' => $skkniPath,
                'kode_unit' => $validated['kode_unit'],
            ]);

            // 5. (Logika multi-unit dihapus)

            // 6. Kembali ke halaman master skema dengan pesan sukses
            return redirect()->route('master_skema')->with('success', 'Skema berhasil diperbarui!');

        } catch (\Exception $e) {
            Log::error('Gagal memperbarui skema: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Menghapus skema dari database.
     */
    public function destroy($id_skema)
    {
        try {
            $skema = Skema::findOrFail($id_skema);

            if ($skema->gambar) Storage::delete($skema->gambar);
            if ($skema->SKKNI) Storage::delete($skema->SKKNI);
            
            // (Logika multi-unit dihapus)
            
            $skema->delete();

            return redirect()->route('master_skema')->with('success', 'Skema berhasil dihapus.');

        } catch (\Exception $e) {
            Log::error('Gagal menghapus skema: ' . $e->getMessage());
            if (str_contains($e->getMessage(), 'constraint violation')) {
                 return back()->with('error', 'Gagal menghapus: Skema ini sudah terhubung dengan data lain.');
            }
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
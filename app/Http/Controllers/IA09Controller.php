<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\IA09;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Exception;

/**
 * Controller untuk mengelola data FR.IA.09. PERTANYAAN WAWANCARA.
 * Menangani tampilan (Asesi, Asesor, Admin) dan logika penyimpanan Asesor.
 */
class IA09Controller extends Controller
{
    /**
     * Menampilkan formulir IA.09 untuk Asesi (Read-Only).
     * @param int $id ID dari dokumen IA09
     */
    public function showAsesi($id)
    {
        try {
            // Eager loading (with) sangat penting untuk memuat data asesi, asesor, dan skema.
            $ia09Data = IA09::with(['asesi', 'asesor', 'skema'])->findOrFail($id);
            
            // Mengirimkan data ke view dengan key 'data'
            return view('IA09.IA09_asesi', ['data' => $ia09Data]);
        } catch (ModelNotFoundException $e) {
            // Menangani jika ID dokumen IA09 tidak ditemukan
            return back()->with('error', 'Dokumen IA.09 tidak ditemukan.');
        } catch (Exception $e) {
            // Menangani error umum
            \Log::error("Error loading IA09 (Asesi) ID {$id}: " . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat memuat data Asesi: ' . $e->getMessage());
        }
    }

    /**
     * Menampilkan formulir IA.09 untuk Asesor (Form Input).
     * @param int $id ID dari dokumen IA09
     */
    public function showAsesor($id)
    {
        try {
            // Mengambil data IA09 beserta relasi yang dibutuhkan
            $ia09Data = IA09::with(['asesi', 'asesor', 'skema'])->findOrFail($id);
            
            // Mengirimkan data ke view dengan key 'data'
            return view('IA09.IA09_asesor', ['data' => $ia09Data]);
        } catch (ModelNotFoundException $e) {
            return back()->with('error', 'Dokumen IA.09 tidak ditemukan.');
        } catch (Exception $e) {
            \Log::error("Error loading IA09 (Asesor) ID {$id}: " . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat memuat data Asesor: ' . $e->getMessage());
        }
    }

    /**
     * Menampilkan formulir IA.09 untuk Admin (Arsip).
     * @param int $id ID dari dokumen IA09
     */
    public function showAdmin($id)
    {
        try {
            // Mengambil data IA09 beserta relasi yang dibutuhkan
            $ia09Data = IA09::with(['asesi', 'asesor', 'skema'])->findOrFail($id);
            
            // Mengirimkan data ke view dengan key 'data'
            return view('IA09.IA09_admin', ['data' => $ia09Data]);
        } catch (ModelNotFoundException $e) {
            return back()->with('error', 'Dokumen IA.09 tidak ditemukan.');
        } catch (Exception $e) {
            \Log::error("Error loading IA09 (Admin) ID {$id}: " . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat memuat data Admin: ' . $e->getMessage());
        }
    }

    /**
     * Menyimpan (Update) hasil input dari Asesor.
     * @param \Illuminate\Http\Request $request
     * @param int $id ID dari dokumen IA09
     */
    public function updateAsesor(Request $request, $id)
    {
        // Validasi input
        $validatedData = $request->validate([
            'tanggal_asesmen' => 'nullable|date',
            'tuk' => 'nullable|string|max:255',
            'rekomendasi_asesor' => 'nullable|in:K,BK', 
            'catatan_asesor' => 'nullable|string',
            'questions' => 'required|array', 
            'units' => 'required|array',     
        ]);

        try {
            $form = IA09::findOrFail($id);

            // Menggunakan Mass Assignment untuk update data. 
            $form->update($validatedData);

            // Redirect kembali ke halaman Asesor
            return redirect()->route('ia09.asesor', ['id' => $form->id])->with('success', 'Hasil wawancara berhasil disimpan.');

        } catch (ModelNotFoundException $e) {
            return back()->with('error', 'Dokumen IA.09 yang akan diupdate tidak ditemukan.');
        } catch (Exception $e) {
            \Log::error("Error updating IA09 ID {$id}: " . $e->getMessage());
            return back()->with('error', 'Gagal menyimpan hasil wawancara: ' . $e->getMessage());
        }
    }
}

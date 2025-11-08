<?php

namespace App\Http\Controllers;

use App\Models\Skema;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log; 
use Illuminate\Support\Facades\Storage;

class SkemaController extends Controller
{
    /**
     * (BARU) Menampilkan halaman daftar semua skema.
     * Termasuk logika Search dan Sort.
     */
    public function index(Request $request)
    {
        // 1. Ambil input sort dan direction, beri nilai default
        $sortColumn = $request->input('sort', 'id_skema'); // Default sort by id_skema
        $sortDirection = $request->input('direction', 'asc'); // Default sort asc

        // 2. Daftar kolom yang BOLEH di-sort (untuk keamanan)
        $allowedColumns = ['id_skema', 'kode_unit', 'nama_skema', 'deskripsi_skema'];
        
        // 3. Validasi input
        if (!in_array($sortColumn, $allowedColumns)) {
            $sortColumn = 'id_skema'; // Jika tidak valid, kembalikan ke default
        }
        if (!in_array($sortDirection, ['asc', 'desc'])) {
            $sortDirection = 'asc'; // Jika tidak valid, kembalikan ke default
        }

        // 4. Mulai query
        $query = Skema::query();

        // 5. Terapkan 'search' (Filter)
        if ($request->has('search') && $request->input('search') != '') {
            $searchTerm = $request->input('search');
            $query->where(function($q) use ($searchTerm) {
                $q->where('nama_skema', 'like', '%' . $searchTerm . '%')
                  ->orWhere('kode_unit', 'like', '%' . $searchTerm . '%')
                  ->orWhere('deskripsi_skema', 'like', '%' . $searchTerm . '%')
                  ->orWhere('id_skema', '=', $searchTerm);
            });
        }

        // 6. Terapkan 'orderBy' (Sorting)
        $query->orderBy($sortColumn, $sortDirection);

        // 7. Eksekusi query
        $skemas = $query->get();

        // 8. Kirim data Skema ke view
        return view('master.skema.master_skema', [
            'skemas' => $skemas
        ]);
    }

    /**
     * (BARU) Menampilkan formulir untuk menambah skema baru.
     */
    public function create()
    {
        return view('master.skema.add_skema');
    }

    /**
     * Menyimpan skema baru ke database.
     */
    public function store(Request $request)
    {
        // 1. Validasi input (Semua 'required' sesuai form add_skema)
        $validated = $request->validate([
            'nama_skema' => 'required|string|max:255',
            'kode_unit' => 'required|string',
            'gambar_skema' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'file_skkni' => 'required|file|mimes:pdf|max:2048',
            'deskripsi' => 'required|string',
        ]);

        // 2. Handle Upload File
        $gambarPath = $request->file('gambar_skema')->store('public/skema_images');
        $skkniPath = $request->file('file_skkni')->store('public/skkni_files');

        try {
            // 3. Buat Skema Utama (Memetakan nama input ke nama kolom DB)
            $skema = Skema::create([
                'nama_skema' => $validated['nama_skema'],
                'deskripsi_skema' => $validated['deskripsi'], // 'deskripsi' -> 'deskripsi_skema'
                'gambar' => $gambarPath,                     // 'gambar_skema' -> 'gambar'
                'SKKNI' => $skkniPath,                       // 'file_skkni' -> 'SKKNI'
                'kode_unit' => $validated['kode_unit'], 
            ]);
            
            // 4. Kembali dengan notifikasi pop-up kustom
            return redirect()->route('master_skema')
                             ->with('success', "Skema (ID: {$skema->id_skema}) - {$skema->nama_skema} - berhasil ditambahkan!");

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

        // 1. Validasi (File 'nullable' karena boleh tidak diganti)
        $validated = $request->validate([
            'nama_skema' => 'required|string|max:255',
            'kode_unit' => 'required|string',
            'gambar_skema' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // Boleh null
            'file_skkni' => 'nullable|file|mimes:pdf|max:2048', // Boleh null
            'deskripsi' => 'required|string',
        ]);

        // 2. Handle Update File Gambar
        $gambarPath = $skema->gambar; // Path lama
        if ($request->hasFile('gambar_skema')) {
            if ($skema->gambar) Storage::delete(str_replace('public/', '', $skema->gambar)); // Hapus file lama
            $gambarPath = $request->file('gambar_skema')->store('public/skema_images'); // Simpan file baru
        }

        // 3. Handle Update File SKKNI
        $skkniPath = $skema->SKKNI; // Path lama
        if ($request->hasFile('file_skkni')) {
            if ($skema->SKKNI) Storage::delete(str_replace('public/', '', $skema->SKKNI)); // Hapus file lama
            $skkniPath = $request->file('file_skkni')->store('public/skkni_files'); // Simpan file baru
        }

        try {
            // 4. Update Skema Utama (Memetakan nama input ke nama kolom DB)
            $skema->update([
                'nama_skema' => $validated['nama_skema'],
                'deskripsi_skema' => $validated['deskripsi'], // 'deskripsi' -> 'deskripsi_skema'
                'gambar' => $gambarPath,
                'SKKNI' => $skkniPath,
                'kode_unit' => $validated['kode_unit'],
            ]);

            // 5. Kembali dengan notifikasi pop-up kustom
            return redirect()->route('master_skema')
                             ->with('success', "Skema (ID: {$skema->id_skema}) - {$skema->nama_skema} - berhasil diperbarui!");

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

            // Ambil info skema SEBELUM dihapus
            $id = $skema->id_skema;
            $nama = $skema->nama_skema;

            // Hapus file dari storage
            if ($skema->gambar) Storage::delete(str_replace('public/', '', $skema->gambar));
            if ($skema->SKKNI) Storage::delete(str_replace('public/', '', $skema->SKKNI));
            
            // Hapus data dari database
            $skema->delete();

            // Kembali dengan notifikasi pop-up kustom
            return redirect()->route('master_skema')
                             ->with('success', "Skema (ID: {$id}) - {$nama} - berhasil dihapus.");

        } catch (\Exception $e) {
            Log::error('Gagal menghapus skema: ' . $e->getMessage());
            if (str_contains($e->getMessage(), 'constraint violation')) {
                 return back()->with('error', 'Gagal menghapus: Skema ini sudah terhubung dengan data lain.');
            }
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
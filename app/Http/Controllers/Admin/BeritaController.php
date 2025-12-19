<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Berita;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File; // [PENTING] Untuk hapus file fisik
use Illuminate\Support\Facades\Storage;

class BeritaController extends Controller
{
    /**
     * Menampilkan daftar berita dengan Search, Sort, dan Pagination.
     */
    public function index(Request $request)
    {
        // [PERBAIKAN 1] Default direction diubah jadi 'asc' (Panah Atas)
        $sortColumn = $request->input('sort', 'id'); // Default sort by ID
        $sortDirection = $request->input('direction', 'asc'); 

        // [PERBAIKAN 2] Tambahkan 'isi' ke allowed columns
        $allowedColumns = ['id', 'judul', 'isi', 'created_at'];
        
        if (!in_array($sortColumn, $allowedColumns)) $sortColumn = 'id';
        if (!in_array($sortDirection, ['asc', 'desc'])) $sortDirection = 'asc';

        $query = Berita::query();

        if ($request->has('search') && $request->input('search') != '') {
            $searchTerm = $request->input('search');
            $query->where(function($q) use ($searchTerm) {
                $q->where('judul', 'like', '%' . $searchTerm . '%')
                  ->orWhere('isi', 'like', '%' . $searchTerm . '%');
            });
        }

        $query->orderBy($sortColumn, $sortDirection);

        $allowedPerpage = [10, 25, 50, 100]; 
        $perPage = $request->input('per_page', 10);
        if (!in_array($perPage, $allowedPerpage)) $perPage = 10;

        $beritas = $query->paginate($perPage)->onEachSide(0.5);
        $beritas->appends($request->only(['sort', 'direction', 'search', 'per_page']));

        return view('Admin.master.berita.master_berita', [
            'beritas' => $beritas,
            'perPage' => $perPage,
            'sortColumn' => $sortColumn,
            'sortDirection' => $sortDirection,
        ]);
    }

    /**
     * Form tambah berita.
     */
    public function create()
    {
        return view('Admin.master.berita.add_berita');
    }

    /**
     * Simpan berita baru.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'judul' => 'required|string|max:255',
            'isi' => 'required|string',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'judul.required' => 'Judul berita wajib diisi.',
            'isi.required' => 'Konten berita wajib diisi.',
            'gambar.image' => 'File harus berupa gambar.',
        ]);

        // Upload Gambar (Storage Public)
        $gambarPath = null;
        if ($request->hasFile('gambar')) {
            $file = $request->file('gambar');
            $filename = time() . '_' . $file->getClientOriginalName();
            
            // Simpan ke storageapp/public/berita
            // Simpan FULL PATH (berita/filename.jpg)
            $gambarPath = $file->storeAs('berita', $filename, 'public');
        }

        $berita = Berita::create([
            'judul' => $validatedData['judul'],
            'isi' => $validatedData['isi'],
            'gambar' => $gambarPath,
        ]);

        return redirect()->route('admin.master_berita')
                         ->with('success', "Berita '{$berita->judul}' (ID: {$berita->id}) berhasil ditambahkan.");
    }

    /**
     * Form edit berita.
     */
    public function edit($id)
    {
        $berita = Berita::findOrFail($id);
        return view('Admin.master.berita.edit_berita', compact('berita'));
    }

    /**
     * Update berita.
     */
    public function update(Request $request, $id)
    {
        $berita = Berita::findOrFail($id);

        $validatedData = $request->validate([
            'judul' => 'required|string|max:255',
            'isi' => 'required|string',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $gambarPath = $berita->gambar;

        // Update Gambar
        if ($request->hasFile('gambar')) {
            // Hapus gambar lama jika ada
            if ($berita->gambar && Storage::disk('public')->exists($berita->gambar)) {
                Storage::disk('public')->delete($berita->gambar);
            }

            // Upload gambar baru
            $file = $request->file('gambar');
            $filename = time() . '_' . $file->getClientOriginalName();
            
            // Simpan FULL PATH (berita/filename.jpg)
            $gambarPath = $file->storeAs('berita', $filename, 'public');
        }

        $berita->update([
            'judul' => $validatedData['judul'],
            'isi' => $validatedData['isi'],
            'gambar' => $gambarPath,
        ]);

        return redirect()->route('admin.master_berita')
                         ->with('success', "Berita '{$berita->judul}' (ID: {$berita->id}) berhasil diperbarui.");
    }

    /**
     * Hapus berita.
     */
    public function destroy($id)
    {
        $berita = Berita::findOrFail($id);
        $judul = $berita->judul;
        $idBerita = $berita->id;

        // Hapus gambar fisik
        if ($berita->gambar && Storage::disk('public')->exists($berita->gambar)) {
            Storage::disk('public')->delete($berita->gambar);
        }

        $berita->delete();

        return redirect()->route('admin.master_berita')
                         ->with('success', "Berita '{$judul}' (ID: {$idBerita}) berhasil dihapus.");
    }
}
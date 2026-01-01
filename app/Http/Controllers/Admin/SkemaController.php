<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Skema;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class SkemaController extends Controller
{
    /**
     * Menampilkan daftar Skema.
     */
    public function index(Request $request)
    {
        $sortColumn = $request->input('sort', 'id_skema');
        $sortDirection = $request->input('direction', 'asc');
        $allowedColumns = ['id_skema', 'nomor_skema', 'nama_skema', 'harga', 'category_nama'];
        
        if (!in_array($sortColumn, $allowedColumns)) $sortColumn = 'id_skema';
        if (!in_array($sortDirection, ['asc', 'desc'])) $sortDirection = 'asc';

        // 1. EAGER LOADING: Muat relasi 'category' agar $skema->category bisa dipanggil di view
        $query = Skema::with('category'); 

        if ($request->has('search') && $request->input('search') != '') {
            $searchTerm = $request->input('search');
            $query->where(function($q) use ($searchTerm) {
                $q->where('nama_skema', 'like', '%' . $searchTerm . '%')
                ->orWhere('nomor_skema', 'like', '%' . $searchTerm . '%')
                // Pastikan searching relasi juga menggunakan 'category_id' jika itu nama kolom Anda
                ->orWhereHas('category', function($cq) use ($searchTerm) {
                    $cq->where('nama_kategori', 'like', '%' . $searchTerm . '%');
                });
            });
        }

        // 2. QUERY UTAMA:
        // Gunakan LEFT JOIN hanya jika sorting dilakukan berdasarkan nama kategori.
        if ($sortColumn == 'category_nama') {
            $query->leftJoin('categories', 'skema.category_id', '=', 'categories.id')
                // PENTING: Pilih skema.* dan tambahkan kolom kategori untuk sorting.
                // Karena kita menggunakan eager loading 'with('category')' di atas, 
                // kolom skema.* yang di-select ini tidak akan merusak relasi.
                ->select('skema.*', 'categories.nama_kategori') 
                ->orderBy('categories.nama_kategori', $sortDirection);
        } else {
            // Jika tidak sorting kategori, kita hanya perlu memilih semua kolom skema 
            // dan mengandalkan eager loading untuk relasi.
            $query->select('skema.*')
                ->orderBy($sortColumn, $sortDirection);
        }

        $allowedPerpage = [10, 25, 50, 100]; 
        $perPage = $request->input('per_page', 10);
        if (!in_array($perPage, $allowedPerpage)) $perPage = 10;

        $skemas = $query->paginate($perPage)->onEachSide(0.5);
        $skemas->appends($request->only(['sort', 'direction', 'search', 'per_page']));

        return view('admin.master.skema.master_skema', [
            'skemas' => $skemas,
            'perPage' => $perPage,
            'sortColumn' => $sortColumn,
            'sortDirection' => $sortDirection,
        ]);
    }

    /**
     * Form Tambah Skema.
     */
    public function create()
    {
        $categories = Category::all();
        return view('admin.master.skema.add_skema', compact('categories'));
    }

    /**
     * Simpan Skema Baru.
     */
    public function store(Request $request)
    {
        // Validasi Ketat (Semua Wajib/Required)
        $validatedData = $request->validate([
            'nomor_skema' => 'required|string|unique:skema,nomor_skema',
            'nama_skema' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'harga' => 'required|numeric|min:0',
            'deskripsi_skema' => 'required|string',
            'SKKNI' => 'required|file|mimes:pdf|max:5120', 
            'gambar' => 'required|image|mimes:jpeg,png,jpg|max:2048', 
        ], [
            'nomor_skema.unique' => 'Nomor skema ini sudah terdaftar.',
            'category_id.required' => 'Kategori wajib dipilih.',
        ]);

        // Upload File SKKNI
        if ($request->hasFile('SKKNI')) {
            $file = $request->file('SKKNI');
            $filename = time() . '_' . $file->getClientOriginalName();
            // Simpan FULL PATH (skema/skkni/filename.pdf)
            $validatedData['SKKNI'] = $file->storeAs('skema/skkni', $filename, 'public');
        }

        // Upload Gambar
        if ($request->hasFile('gambar')) {
            $file = $request->file('gambar');
            $filename = time() . '_' . $file->getClientOriginalName();
            // Simpan FULL PATH (skema/foto_skema/filename.jpg)
            $validatedData['gambar'] = $file->storeAs('skema/foto_skema', $filename, 'public');
        }

        $skema = Skema::create($validatedData);

        // [PENTING] Redirect langsung ke halaman DETAIL (Kelompok Pekerjaan)
        // Route 'skema.detail' ini akan ditangani oleh DetailSkemaController
        return redirect()->route('admin.skema.detail', $skema->id_skema)
                         ->with('success', "Skema '{$skema->nama_skema}' (ID: {$skema->id_skema}) berhasil dibuat. Silakan lengkapi Kelompok Pekerjaan.");
    }

    /**
     * Form Edit Skema (Hanya Info Dasar).
     */
    public function edit($id)
    {
        $skema = Skema::findOrFail($id);
        $categories = Category::all();
        $skema->category_id = (string) $skema->category_id;
        return view('admin.master.skema.edit_skema', compact('skema', 'categories'));
    }

    /**
     * Update Skema.
     */
    public function update(Request $request, $id)
    {
        $skema = Skema::findOrFail($id);

        $validatedData = $request->validate([
            'nomor_skema' => ['required', 'string', Rule::unique('skema')->ignore($skema->id_skema, 'id_skema')],
            'nama_skema' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'harga' => 'required|numeric|min:0',
            'deskripsi_skema' => 'required|string',
            'SKKNI' => 'nullable|file|mimes:pdf|max:5120', // Nullable saat update
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // Nullable saat update
        ]);

        // Update File SKKNI
        if ($request->hasFile('SKKNI')) {
            if ($skema->SKKNI && Storage::disk('public')->exists($skema->SKKNI)) {
                Storage::disk('public')->delete($skema->SKKNI);
            }
            $file = $request->file('SKKNI');
            $filename = time() . '_' . $file->getClientOriginalName();
            // Simpan FULL PATH
            $validatedData['SKKNI'] = $file->storeAs('skema/skkni', $filename, 'public');
        }

        // Update Gambar
        if ($request->hasFile('gambar')) {
            if ($skema->gambar && Storage::disk('public')->exists($skema->gambar)) { // Hapus prefix manual
                Storage::disk('public')->delete($skema->gambar);
            }
            $file = $request->file('gambar');
            $filename = time() . '_' . $file->getClientOriginalName();
            // Simpan FULL PATH
            $validatedData['gambar'] = $file->storeAs('skema/foto_skema', $filename, 'public');
        }

        $skema->update($validatedData);

        return redirect()->route('admin.master_skema')
                         ->with('success', "Skema '{$skema->nama_skema}' (ID: {$skema->id_skema}) berhasil diperbarui.");
    }

    /**
     * Hapus Skema.
     */
    public function destroy($id)
    {
        $skema = Skema::findOrFail($id);
        $nama = $skema->nama_skema;
        $idSkema = $skema->id_skema;

        if ($skema->SKKNI && Storage::disk('public')->exists($skema->SKKNI)) {
            Storage::disk('public')->delete($skema->SKKNI);
        }

        if ($skema->gambar && Storage::disk('public')->exists($skema->gambar)) {
            Storage::disk('public')->delete($skema->gambar);
        }

        $skema->delete();

        return redirect()->route('admin.master_skema')
                         ->with('success', "Skema '{$nama}' (ID: {$idSkema}) berhasil dihapus.");
    }
}
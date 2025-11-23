<?php

namespace App\Http\Controllers;

use App\Models\Skema;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File; // [PENTING] Pakai File Facade untuk hapus file fisik
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

        $query = Skema::with('category'); 

        // Search Logic
        if ($request->has('search') && $request->input('search') != '') {
            $searchTerm = $request->input('search');
            $query->where(function($q) use ($searchTerm) {
                $q->where('nama_skema', 'like', '%' . $searchTerm . '%')
                  ->orWhere('nomor_skema', 'like', '%' . $searchTerm . '%')
                  ->orWhereHas('category', function($cq) use ($searchTerm) {
                      $cq->where('nama_kategori', 'like', '%' . $searchTerm . '%');
                  });
            });
        }

        // Sorting Logic
        $query->select('skema.*');
        
        if ($sortColumn == 'category_nama') {
            $query->join('categories', 'skema.categorie_id', '=', 'categories.id')
                  ->orderBy('categories.nama_kategori', $sortDirection);
        } else {
            $query->orderBy($sortColumn, $sortDirection);
        }

        // Pagination
        $allowedPerpage = [10, 25, 50, 100]; 
        $perPage = $request->input('per_page', 10);
        if (!in_array($perPage, $allowedPerpage)) $perPage = 10;

        $skemas = $query->paginate($perPage)->onEachSide(0.5);
        $skemas->appends($request->only(['sort', 'direction', 'search', 'per_page']));

        return view('master.skema.master_skema', [
            'skemas' => $skemas,
            'perPage' => $perPage,
            'sortColumn' => $sortColumn,
            'sortDirection' => $sortDirection,
        ]);
    }

    // --- METHOD BARU: UNTUK HALAMAN DETAIL SKEMA ---
    /**
     * Menampilkan detail Skema.
     */
    public function show($id_skema)
    {
        // Load Skema dengan relasi category, kelompokPekerjaan, dan UnitKompetensi
        $skema = Skema::with(['category', 'kelompokPekerjaan.unitKompetensi'])
                    ->findOrFail($id_skema);

        // Data dummy untuk Form Asesmen (sesuai contoh di Blade)
        $formAsesmen = [
            ['kode' => 'FR.APL.01', 'warna' => 'bg-red-500 hover:bg-red-600'],
            ['kode' => 'FR.APL.02', 'warna' => 'bg-red-500 hover:bg-red-600'],
            ['kode' => 'FR.MAPA.01', 'warna' => 'bg-green-500 hover:bg-green-600'],
            ['kode' => 'FR.AK.01', 'warna' => 'bg-blue-600 hover:bg-blue-700'],
            ['kode' => 'FR.AK.02', 'warna' => 'bg-blue-600 hover:bg-blue-700'],
            ['kode' => 'FR.AK.03', 'warna' => 'bg-blue-600 hover:bg-blue-700'],
            ['kode' => 'FR.AK.04', 'warna' => 'bg-green-500 hover:bg-green-600'],
            ['kode' => 'FR.AK.05', 'warna' => 'bg-blue-600 hover:bg-blue-700'],
            ['kode' => 'FR.AK.06', 'warna' => 'bg-blue-600 hover:bg-blue-700'],
            ['kode' => 'FR.IA.01', 'warna' => 'bg-yellow-500 hover:bg-yellow-600'],
            ['kode' => 'FR.IA.02', 'warna' => 'bg-yellow-500 hover:bg-yellow-600'],
            ['kode' => 'FR.IA.05', 'warna' => 'bg-yellow-500 hover:bg-yellow-600'],
            ['kode' => 'FR.IA.11', 'warna' => 'bg-yellow-500 hover:bg-yellow-600'],
        ];

        return view('master.skema.detail_skema', compact('skema', 'formAsesmen'));
    }
    // ----------------------------------------------------------------------
    
    /**
     * Form Tambah Skema.
     */
    public function create()
    {
        $categories = Category::all();
        return view('master.skema.add_skema', compact('categories'));
    }

    /**
     * Simpan Skema Baru.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nomor_skema' => 'required|string|unique:skema,nomor_skema',
            'nama_skema' => 'required|string|max:255',
            'categorie_id' => 'required|exists:categories,id',
            'harga' => 'nullable|numeric|min:0',
            'deskripsi_skema' => 'required|string',
            'SKKNI' => 'nullable|file|mimes:pdf|max:5120', 
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', 
        ], [
            'nomor_skema.unique' => 'Nomor skema ini sudah terdaftar.',
            'categorie_id.required' => 'Kategori wajib dipilih.',
        ]);

        // [PERBAIKAN] Upload File SKKNI ke public/images/skema/skkni
        if ($request->hasFile('SKKNI')) {
            $file = $request->file('SKKNI');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('images/skema/skkni'), $filename);
            $validatedData['SKKNI'] = 'images/skema/skkni/' . $filename;
        }

        // [PERBAIKAN] Upload Gambar ke public/images/skema/foto_skema
        if ($request->hasFile('gambar')) {
            $file = $request->file('gambar');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('images/skema/foto_skema'), $filename);
            $validatedData['gambar'] = 'images/skema/foto_skema/' . $filename;
        }

        $skema = Skema::create($validatedData);

        return redirect()->route('master_skema')
                         ->with('success', "Skema '{$skema->nama_skema}' berhasil dibuat.");
    }

    /**
     * Form Edit Skema.
     */
    public function edit($id)
    {
        $skema = Skema::findOrFail($id);
        $categories = Category::all();
        return view('master.skema.edit_skema', compact('skema', 'categories'));
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
            'categorie_id' => 'required|exists:categories,id',
            'harga' => 'nullable|numeric|min:0',
            'deskripsi_skema' => 'required|string',
            'SKKNI' => 'nullable|file|mimes:pdf|max:5120',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Update File SKKNI
        if ($request->hasFile('SKKNI')) {
            // Hapus file lama
            if ($skema->SKKNI && File::exists(public_path($skema->SKKNI))) {
                File::delete(public_path($skema->SKKNI));
            }
            // Upload baru
            $file = $request->file('SKKNI');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('images/skema/skkni'), $filename);
            $validatedData['SKKNI'] = 'images/skema/skkni/' . $filename;
        }

        // Update Gambar
        if ($request->hasFile('gambar')) {
            // Hapus file lama
            if ($skema->gambar && File::exists(public_path($skema->gambar))) {
                File::delete(public_path($skema->gambar));
            }
            // Upload baru
            $file = $request->file('gambar');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('images/skema/foto_skema'), $filename);
            $validatedData['gambar'] = 'images/skema/foto_skema/' . $filename;
        }

        $skema->update($validatedData);

        return redirect()->route('master_skema')
                         ->with('success', "Skema '{$skema->nama_skema}' berhasil diperbarui.");
    }

    /**
     * Hapus Skema.
     */
    public function destroy($id)
    {
        $skema = Skema::findOrFail($id);
        $nama = $skema->nama_skema;

        // Hapus file fisik SKKNI
        if ($skema->SKKNI && File::exists(public_path($skema->SKKNI))) {
            File::delete(public_path($skema->SKKNI));
        }

        // Hapus file fisik Gambar
        if ($skema->gambar && File::exists(public_path($skema->gambar))) {
            File::delete(public_path($skema->gambar));
        }

        $skema->delete();

        return redirect()->route('master_skema')
                         ->with('success', "Skema '{$nama}' berhasil dihapus.");
    }
}
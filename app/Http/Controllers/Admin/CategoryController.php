<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{
    /**
     * Menampilkan list Kategori dengan Paginasi, Sorting, dan Filtering.
     */
    public function index(Request $request)
    {
        // 1. Ambil input sort dan direction (Default 'id', 'asc')
        $sortColumn = $request->input('sort', 'id');
        $sortDirection = $request->input('direction', 'asc');

        // 2. Daftar kolom yang BOLEH di-sort
        $allowedColumns = ['id', 'nama_kategori', 'slug'];
        
        // Fallback jika input sort tidak valid
        if (!in_array($sortColumn, $allowedColumns)) $sortColumn = 'id'; 
        if (!in_array($sortDirection, ['asc', 'desc'])) $sortDirection = 'asc';

        // 3. Mulai query
        $query = Category::query();

        // 4. Terapkan 'search' (Filter)
        if ($request->has('search') && $request->input('search') != '') {
            $searchTerm = $request->input('search');
            
            $query->where(function($q) use ($searchTerm) {
                $q->where('nama_kategori', 'like', '%' . $searchTerm . '%')
                  ->orWhere('slug', 'like', '%' . $searchTerm . '%');
            });
        }

        // 5. Terapkan 'orderBy' (Sorting)
        $query->orderBy($sortColumn, $sortDirection);

        // 6. Ambil 'per_page' (Paginate Dinamis)
        $allowedPerpage = [10, 25, 50, 100]; 
        $perPage = $request->input('per_page', 10);
        if (!in_array($perPage, $allowedPerpage)) {
            $perPage = 10;
        }

        // 7. Ganti ->get() menjadi ->paginate()
        $categories = $query->paginate($perPage)->onEachSide(0.5);
        
        // 8. (WAJIB) Sertakan semua parameter di link paginasi
        $categories->appends($request->only(['sort', 'direction', 'search', 'per_page']));

        
        // 9. Kirim data lengkap ke view (Path sudah benar)
        return view('admin.master.category.master_category', [ 
            'categories' => $categories,
            'perPage' => $perPage,
            'sortColumn' => $sortColumn,
            'sortDirection' => $sortDirection,
        ]);
    }

    /**
     * Menampilkan form untuk membuat kategori baru.
     */
    public function create()
    {
        // Path view sudah benar
        return view('admin.master.category.add_category');
    }

    /**
     * Menyimpan kategori baru ke database.
     */
    public function store(Request $request)
    {
        $rules = [
            'nama_kategori' => 'required|string|max:255|unique:categories,nama_kategori',
        ];
        
        $messages = [
            'nama_kategori.required' => 'Nama kategori wajib diisi.',
            'nama_kategori.unique' => 'Nama kategori ini sudah ada.',
            'nama_kategori.max' => 'Nama kategori tidak boleh lebih dari 255 karakter.',
        ];

        $validatedData = $request->validate($rules, $messages);

        // Buat slug secara otomatis
        $validatedData['slug'] = Str::slug($validatedData['nama_kategori']);

        // Tangkap model yang baru dibuat
        $category = Category::create($validatedData);

        // Redirect ke rute yang benar dengan pesan sukses + ID
        return redirect()->route('master_category') 
                         ->with('success', "Kategori '{$category->nama_kategori}' (ID: {$category->id}) berhasil ditambahkan.");
    }

    /**
     * Menampilkan detail kategori (sering tidak dipakai, tapi ada di --resource).
     */
    public function show(Category $category)
    {
        // Redirect ke rute yang benar
        return redirect()->route('edit_category', $category->id); 
    }

    /**
     * Menampilkan form untuk mengedit kategori.
     */
    public function edit(Category $category)
    {
        // Path view sudah benar
        return view('admin.master.category.edit_category', compact('category')); 
    }

    /**
     * Update kategori di database.
     */
    public function update(Request $request, Category $category)
    {
        $rules = [
            'nama_kategori' => [
                'required',
                'string',
                'max:255',
                Rule::unique('categories')->ignore($category->id),
            ],
        ];

         $messages = [
            'nama_kategori.required' => 'Nama kategori wajib diisi.',
            'nama_kategori.unique' => 'Nama kategori ini sudah ada.',
            'nama_kategori.max' => 'Nama kategori tidak boleh lebih dari 255 karakter.',
        ];

        $validatedData = $request->validate($rules, $messages);

        // Slug tidak di-update saat edit
        $category->update($validatedData);

        // Redirect ke rute yang benar dengan pesan sukses + ID
        return redirect()->route('master_category')
                         ->with('success', "Kategori '{$category->nama_kategori}' (ID: {$category->id}) berhasil diperbarui.");
    }

    /**
     * Menghapus kategori dari database.
     */
    public function destroy(Category $category)
    {
        // Simpan detail sebelum dihapus
        $namaKategori = $category->nama_kategori;
        $idKategori = $category->id;
        
        $category->delete(); 
        
        // Redirect ke rute yang benar dengan pesan sukses + ID
        return redirect()->route('master_category')
                         ->with('success', "Kategori '{$namaKategori}' (ID: {$idKategori}) dan semua skema yang terhubung telah dihapus.");
    }
}
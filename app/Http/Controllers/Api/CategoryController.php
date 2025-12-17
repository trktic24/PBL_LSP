<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    // GET all categories
    public function index()
    {
        $data = Category::with('skemas')->get();

        return response()->json([
            'status' => 'success',
            'message' => 'List kategori berhasil diambil',
            'data' => $data
        ], 200);
    }

    // POST create category
    public function store(Request $request)
    {
        $request->validate([
            'nama_kategori' => 'required|string|max:255'
        ]);

        $data = Category::create([
            'nama_kategori' => $request->nama_kategori,
            'slug' => Str::slug($request->nama_kategori)
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Kategori berhasil dibuat',
            'data' => $data
        ], 201);
    }

    // GET category detail
    public function show($id)
    {
        $data = Category::with('skemas')->find($id);

        if (!$data) {
            return response()->json([
                'status' => 'error',
                'message' => 'Kategori tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Detail kategori berhasil diambil',
            'data' => $data
        ], 200);
    }

    // UPDATE category (POST update - tetap ada)
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_kategori' => 'required|string|max:255'
        ]);

        $data = Category::find($id);

        if (!$data) {
            return response()->json([
                'status' => 'error',
                'message' => 'Kategori tidak ditemukan'
            ], 404);
        }

        $data->update([
            'nama_kategori' => $request->nama_kategori,
            'slug' => Str::slug($request->nama_kategori)
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Kategori berhasil diupdate',
            'data' => $data
        ], 200);
    }

    // ====== PUT UPDATE BARU (ditambahkan) ======
    public function putUpdate(Request $request, $id)
    {
        $request->validate([
            'nama_kategori' => 'required|string|max:255'
        ]);

        $data = Category::find($id);

        if (!$data) {
            return response()->json([
                'status' => 'error',
                'message' => 'Kategori tidak ditemukan'
            ], 404);
        }

        $data->update([
            'nama_kategori' => $request->nama_kategori,
            'slug' => Str::slug($request->nama_kategori)
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Kategori berhasil diupdate (PUT)',
            'data' => $data
        ], 200);
    }
    // ===========================================

    // DELETE category
    public function destroy($id)
    {
        $data = Category::find($id);

        if (!$data) {
            return response()->json([
                'status' => 'error',
                'message' => 'Kategori tidak ditemukan'
            ], 404);
        }

        $data->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Kategori berhasil dihapus'
        ], 200);
    }
}

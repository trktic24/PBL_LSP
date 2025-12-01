<?php

namespace App\Http\Controllers;

use App\Models\MasterTuk;
use Illuminate\Http\Request;

class TukController extends Controller
{
    /**
     * Menampilkan daftar Tempat Uji Kompetensi (TUK) dengan fitur Sorting, Searching, dan Pagination.
     */
    public function index(Request $request)
    {
        // 1. Inisialisasi Query Builder
        $query = MasterTuk::query();

        // 2. LOGIKA SEARCHING (Berdasarkan nama_lokasi atau alamat_tuk)
        if ($request->filled('search')) {
            $searchTerm = '%' . $request->get('search') . '%';
            $query->where(function ($q) use ($searchTerm) {
                // Kolom sesuai tabel: nama_lokasi dan alamat_tuk
                $q->where('nama_lokasi', 'like', $searchTerm)
                  ->orWhere('alamat_tuk', 'like', $searchTerm);
            });
        }
        
        // 3. LOGIKA SORTING
        
        $sortColumn = $request->get('sort', 'nama_lokasi'); 
        $sortDirection = $request->get('direction', 'asc');

        // Daftar kolom yang diizinkan untuk sorting
        $allowedColumns = ['nama_lokasi', 'alamat_tuk', 'kontak_tuk', 'created_at'];

        if (in_array($sortColumn, $allowedColumns)) { 
            $query->orderBy($sortColumn, $sortDirection);
        } else {
            // Fallback jika kolom tidak valid
            $query->orderBy('nama_lokasi', 'asc'); 
        }

        // 4. Ambil data TUK (dengan Pagination 10 data per halaman)
        $tuks = $query->paginate(10); 
        
        // Mengirimkan data TUK ke view
        return view('landing_page.page_tuk.info-tuk', [
            'tuks' => $tuks,
        ]);
    }


    /**
     * Menampilkan detail spesifik dari satu TUK berdasarkan ID.
     *
     * @param int $id ID TUK (primary key)
     */
    public function showDetail($id)
    {

        $data_tuk = MasterTuk::findOrFail($id);
        

        return view('landing_page.page_tuk.detail-tuk', [
            'data_tuk' => $data_tuk,
        ]);
    }
}
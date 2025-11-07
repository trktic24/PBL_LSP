<?php

namespace App\Http\Controllers;

use App\Models\Tuk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TukController extends Controller
{
    /**
     * Menampilkan halaman list TUK (Read).
     */
    public function index(Request $request)
    {
        // Mulai query
        $query = Tuk::query();

        // Cek jika ada input 'search'
        if ($request->has('search') && $request->input('search') != '') {
            
            $searchTerm = $request->input('search');

            // === INI BAGIAN YANG DIPERBARUI ===
            // Tambahkan kondisi WHERE
            // Mencari di semua kolom teks + ID
            $query->where(function($q) use ($searchTerm) {
                $q->where('nama_lokasi', 'like', '%' . $searchTerm . '%')
                  ->orWhere('alamat_tuk', 'like', '%' . $searchTerm . '%')
                  ->orWhere('kontak_tuk', 'like', '%' . $searchTerm . '%')
                  ->orWhere('link_gmap', 'like', '%' . $searchTerm . '%')
                  ->orWhere('id_tuk', '=', $searchTerm); // Mencari ID TUK yang sama persis
            });
            // ===================================
        }

        // Eksekusi query dan ambil hasilnya
        $tuks = $query->get();

        // Kirim data TUK yang sudah difilter
        return view('tuk.master_tuk', [
            'tuks' => $tuks
        ]);
    }

    /**
     * Menampilkan formulir add TUK (Create - view).
     */
    public function create()
    {
        return view('tuk.add_tuk');
    }

    /**
     * Menyimpan data TUK baru ke database (Create - logic).
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nama_lokasi' => 'required|string|max:255',
            'alamat_tuk'  => 'required|string',
            'kontak_tuk'  => 'required|string|max:100',
            'foto_tuk'    => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'link_gmap'   => 'required|url',
        ]);

        $path = $request->file('foto_tuk')->store('photos/tuk', 'public');
        $validatedData['foto_tuk'] = $path; 

        $tuk = Tuk::create($validatedData); 

        return redirect()->route('master_tuk')->with('success', "TUK (ID: {$tuk->id_tuk}) - {$tuk->nama_lokasi} - berhasil ditambahkan!");
    }

    /**
     * Menampilkan form 'edit_tuk' (Update - view).
     */
    public function edit($id)
    {
        $tuk = Tuk::findOrFail($id);
        return view('tuk.edit_tuk', [
            'tuk' => $tuk
        ]);
    }

    /**
     * Memperbarui data TUK di database (Update - logic).
     */
    public function update(Request $request, $id)
    {
        $tuk = Tuk::findOrFail($id);

        $validatedData = $request->validate([
            'nama_lokasi' => 'required|string|max:255',
            'alamat_tuk'  => 'required|string',
            'kontak_tuk'  => 'required|string|max:100',
            'foto_tuk'    => 'nullable|image|mimes:jpeg,png,jpg|max:2048', 
            'link_gmap'   => 'required|url',
        ]);

        if ($request->hasFile('foto_tuk')) {
            if ($tuk->foto_tuk) {
                Storage::disk('public')->delete($tuk->foto_tuk);
            }
            $path = $request->file('foto_tuk')->store('photos/tuk', 'public');
            $validatedData['foto_tuk'] = $path;
        }

        $tuk->update($validatedData);

        return redirect()->route('master_tuk')->with('success', "TUK (ID: {$tuk->id_tuk}) - {$tuk->nama_lokasi} - berhasil diperbarui!");
    }

    /**
     * Menghapus data TUK dari database (Delete - logic).
     */
    public function destroy($id)
    {
        $tuk = Tuk::findOrFail($id);

        $id_tuk = $tuk->id_tuk;
        $nama_lokasi = $tuk->nama_lokasi;

        if ($tuk->foto_tuk) {
            Storage::disk('public')->delete($tuk->foto_tuk);
        }

        $tuk->delete();

        return redirect()->route('master_tuk')->with('success', "TUK (ID: {$id_tuk}) - {$nama_lokasi} - berhasil dihapus!");
    }
}
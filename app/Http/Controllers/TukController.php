<?php

namespace App\Http\Controllers;

use App\Models\Tuk;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\QueryException; 

class TukController extends Controller
{
    /**
     * Menampilkan halaman list TUK (Read).
     * LOGIKA DIPERBARUI UNTUK PAGINASI
     */
    public function index(Request $request)
    {
        // 1. Ambil input sort dan direction, beri nilai default
        $sortColumn = $request->input('sort', 'id_tuk');
        $sortDirection = $request->input('direction', 'asc');

        // 2. Daftar kolom yang BOLEH di-sort (untuk keamanan)
        $allowedColumns = ['id_tuk', 'nama_lokasi', 'alamat_tuk', 'kontak_tuk'];
        
        if (!in_array($sortColumn, $allowedColumns)) $sortColumn = 'id_tuk';
        if (!in_array($sortDirection, ['asc', 'desc'])) $sortDirection = 'asc';

        // 3. Mulai query
        $query = Tuk::query();

        // 4. Terapkan 'search' (Filter)
        if ($request->has('search') && $request->input('search') != '') {
            $searchTerm = $request->input('search');
            $query->where(function($q) use ($searchTerm) {
                $q->where('nama_lokasi', 'like', '%' . $searchTerm . '%')
                  ->orWhere('alamat_tuk', 'like', '%' . $searchTerm . '%')
                  ->orWhere('kontak_tuk', 'like', '%' . $searchTerm . '%')
                  ->orWhere('link_gmap', 'like', '%' . $searchTerm . '%')
                  ->orWhere('id_tuk', '=', $searchTerm);
            });
        }

        // 5. Terapkan 'orderBy' (Sorting)
        $query->orderBy($sortColumn, $sortDirection);

        // 6. === INI PERBAIKANNYA ===
        // Ganti get() menjadi paginate(). Kita set 10 data per halaman.
        $tuks = $query->paginate(10)->onEachSide(0.5);
        
        // 7. (WAJIB) Sertakan parameter search/sort di link paginasi
        $tuks->appends($request->only(['sort', 'direction', 'search']));

        // 8. Kirim data TUK yang sudah di-paginate
        return view('tuk.master_tuk', ['tuks' => $tuks]);
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

        return redirect()->route('master_tuk')->with('success', "TUK (ID: {$tuk->id_tuk}) {$tuk->nama_lokasi} Berhasil ditambahkan!");
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
                Storage::disk('public')->delete(str_replace('public/', '', $tuk->foto_tuk));
            }
            $path = $request->file('foto_tuk')->store('photos/tuk', 'public');
            $validatedData['foto_tuk'] = $path;
        }

        $tuk->update($validatedData);

        return redirect()->route('master_tuk')->with('success', "TUK (ID: {$tuk->id_tuk}) {$tuk->nama_lokasi} Berhasil diperbarui!");
    }

    /**
     * Menghapus data TUK dari database (Delete - logic).
     */
    public function destroy($id)
    {
        $tuk = Tuk::findOrFail($id);
        $id_tuk = $tuk->id_tuk;
        $nama_lokasi = $tuk->nama_lokasi;

        $conflictingSchedule = Schedule::where('id_tuk', $id_tuk)->first();

        if ($conflictingSchedule) {
            return back()->with('error', 
                "Gagal menghapus TUK (ID: {$id_tuk}): TUK ini masih terhubung dengan Jadwal (ID: {$conflictingSchedule->id_jadwal})."
            );
        }

        try {
            if ($tuk->foto_tuk) {
                Storage::disk('public')->delete(str_replace('public/', '', $tuk->foto_tuk));
            }

            $tuk->delete();

            return redirect()->route('master_tuk')->with('success', "TUK (ID: {$id_tuk}) {$nama_lokasi} Berhasil dihapus!");

        } catch (QueryException $e) {
            return back()->with('error', 'Terjadi kesalahan database: ' . $e->getMessage());
        }
    }
}
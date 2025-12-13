<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tuk;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File; // [PENTING] Pakai File Facade, bukan Storage
use Illuminate\Database\QueryException; 

class TukController extends Controller
{
    /**
     * Menampilkan halaman list TUK (Read).
     */
    public function index(Request $request)
    {
        // 1. Ambil input sort dan direction
        $sortColumn = $request->input('sort', 'id_tuk');
        $sortDirection = $request->input('direction', 'asc');

        // 2. Daftar kolom yang BOLEH di-sort
        $allowedColumns = ['id_tuk', 'nama_lokasi', 'alamat_tuk', 'kontak_tuk'];
        
        if (!in_array($sortColumn, $allowedColumns)) $sortColumn = 'id_tuk';
        if (!in_array($sortDirection, ['asc', 'desc'])) $sortDirection = 'asc';

        // 3. Mulai query
        $query = Tuk::query();

        // 4. Terapkan 'search'
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

        // 5. Sorting
        $query->orderBy($sortColumn, $sortDirection);

        // 6. Pagination Dinamis
        $allowedPerpage = [10, 25, 50, 100]; 
        $perPage = $request->input('per_page', 10);
        if (!in_array($perPage, $allowedPerpage)) {
            $perPage = 10;
        }

        $tuks = $query->paginate($perPage)->onEachSide(0.5);
        
        // 7. Appends parameter ke link pagination
        $tuks->appends($request->only(['sort', 'direction', 'search', 'per_page']));

        return view('admin.tuk.master_tuk', [
            'tuks' => $tuks,
            'perPage' => $perPage 
        ]);
    }

    /**
     * Menampilkan formulir tambah TUK.
     */
    public function create()
    {
        return view('admin.tuk.add_tuk');
    }

    /**
     * Menyimpan data TUK baru (Langsung ke public/images/foto_tuk).
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

        // 1. Ambil file
        $file = $request->file('foto_tuk');
        
        // 2. Generate nama unik
        $filename = time() . '_' . $file->getClientOriginalName();
        
        // 3. Tentukan tujuan: public/images/foto_tuk
        $destinationPath = public_path('images/foto_tuk');
        
        // 4. Pindahkan file
        $file->move($destinationPath, $filename);
        
        // 5. Simpan path relatif ke database
        $validatedData['foto_tuk'] = 'images/foto_tuk/' . $filename; 

        $tuk = Tuk::create($validatedData); 

        return redirect()->route('master_tuk')
                         ->with('success', "TUK (ID: {$tuk->id_tuk}) {$tuk->nama_lokasi} Berhasil ditambahkan!");
    }

    /**
     * Menampilkan form edit TUK.
     */
    public function edit($id)
    {
        $tuk = Tuk::findOrFail($id);
        return view('admin.tuk.edit_tuk', ['tuk' => $tuk]);
    }

    /**
     * Memperbarui data TUK (Hapus foto lama di public, upload baru).
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
            // 1. Hapus foto lama jika ada di public folder
            if ($tuk->foto_tuk && File::exists(public_path($tuk->foto_tuk))) {
                File::delete(public_path($tuk->foto_tuk));
            }

            // 2. Proses upload foto baru
            $file = $request->file('foto_tuk');
            $filename = time() . '_' . $file->getClientOriginalName();
            $destinationPath = public_path('images/foto_tuk');
            
            $file->move($destinationPath, $filename);
            
            $validatedData['foto_tuk'] = 'images/foto_tuk/' . $filename;
        }

        $tuk->update($validatedData);

        return redirect()->route('master_tuk')
                         ->with('success', "TUK (ID: {$tuk->id_tuk}) {$tuk->nama_lokasi} Berhasil diperbarui!");
    }

    /**
     * Menghapus data TUK (Hapus file dari public folder).
     */
    public function destroy($id)
    {
        $tuk = Tuk::findOrFail($id);
        $id_tuk = $tuk->id_tuk;
        $nama_lokasi = $tuk->nama_lokasi;

        // Cek relasi ke jadwal
        $conflictingSchedule = Schedule::where('id_tuk', $id_tuk)->first();

        if ($conflictingSchedule) {
            return back()->with('error', 
                "Gagal menghapus TUK (ID: {$id_tuk}): TUK ini masih terhubung dengan Jadwal (ID: {$conflictingSchedule->id_jadwal})."
            );
        }

        try {
            // Hapus file fisik dari public folder
            if ($tuk->foto_tuk && File::exists(public_path($tuk->foto_tuk))) {
                File::delete(public_path($tuk->foto_tuk));
            }

            $tuk->delete();

            return redirect()->route('master_tuk')
                             ->with('success', "TUK (ID: {$id_tuk}) {$nama_lokasi} Berhasil dihapus!");

        } catch (QueryException $e) {
            return back()->with('error', 'Terjadi kesalahan database: ' . $e->getMessage());
        }
    }
}
<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\MasterTUK;
use App\Models\Jadwal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\QueryException; 

class TukAdminController extends Controller
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
        $query = MasterTUK::query();

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

        return view('Admin.tuk.master_tuk', [
            'tuks' => $tuks,
            'perPage' => $perPage 
        ]);
    }

    /**
     * Menampilkan formulir tambah TUK.
     */
    public function create()
    {
        return view('Admin.tuk.add_tuk');
    }

    /**
     * Menyimpan data TUK baru (Storage Public).
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

        // Simpan file ke storage (public/tuk)
        // Hasilnya path seperti: tuk/hashname.jpg
        $path = $request->file('foto_tuk')->store('foto_tuk', 'public');
        
        $validatedData['foto_tuk'] = $path; 

        $tuk = MasterTUK::create($validatedData); 

        return redirect()->route('admin.master_tuk')
                         ->with('success', "TUK (ID: {$tuk->id_tuk}) {$tuk->nama_lokasi} Berhasil ditambahkan!");
    }

    /**
     * Menampilkan form edit TUK.
     */
    public function edit($id)
    {
        $tuk = MasterTUK::findOrFail($id);
        return view('Admin.tuk.edit_tuk', ['tuk' => $tuk]);
    }

    /**
     * Memperbarui data TUK.
     */
    public function update(Request $request, $id)
    {
        $tuk = MasterTUK::findOrFail($id);

        $validatedData = $request->validate([
            'nama_lokasi' => 'required|string|max:255',
            'alamat_tuk'  => 'required|string',
            'kontak_tuk'  => 'required|string|max:100',
            'foto_tuk'    => 'nullable|image|mimes:jpeg,png,jpg|max:2048', 
            'link_gmap'   => 'required|url',
        ]);

        if ($request->hasFile('foto_tuk')) {
            // 1. Hapus foto lama dari storage jika ada
            if ($tuk->foto_tuk) {
                Storage::disk('public')->delete($tuk->foto_tuk);
            }

            // 2. Upload foto baru
            $path = $request->file('foto_tuk')->store('foto_tuk', 'public');
            $validatedData['foto_tuk'] = $path;
        }

        $tuk->update($validatedData);

        return redirect()->route('admin.master_tuk')
                         ->with('success', "TUK (ID: {$tuk->id_tuk}) {$tuk->nama_lokasi} Berhasil diperbarui!");
    }

    /**
     * Menghapus data TUK.
     */
    public function destroy($id)
    {
        $tuk = MasterTUK::findOrFail($id);
        $id_tuk = $tuk->id_tuk;
        $nama_lokasi = $tuk->nama_lokasi;

        // Cek relasi ke jadwal
        $conflictingSchedule = Jadwal::where('id_tuk', $id_tuk)->first();

        if ($conflictingSchedule) {
            return back()->with('error', 
                "Gagal menghapus TUK (ID: {$id_tuk}): TUK ini masih terhubung dengan Jadwal (ID: {$conflictingSchedule->id_jadwal})."
            );
        }

        try {
            // Hapus file fisik dari storage
            if ($tuk->foto_tuk) {
                Storage::disk('public')->delete($tuk->foto_tuk);
            }

            $tuk->delete();

            return redirect()->route('admin.master_tuk')
                             ->with('success', "TUK (ID: {$id_tuk}) {$nama_lokasi} Berhasil dihapus!");

        } catch (QueryException $e) {
            return back()->with('error', 'Terjadi kesalahan database: ' . $e->getMessage());
        }
    }
}
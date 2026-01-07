<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StrukturOrganisasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class StrukturOrganisasiController extends Controller
{
    /**
     * Menampilkan list Struktur Organisasi.
     */
    public function index(Request $request)
    {
        // MODIFIKASI: Tambahkan 'urutan' ke kolom yang diizinkan
        $allowedColumns = ['id', 'nama', 'jabatan', 'urutan'];
        
        // Default sort kita ubah ke 'urutan' agar hierarki tampil rapi
        $sortColumn = $request->input('sort', 'urutan'); 
        $sortDirection = $request->input('direction', 'asc');

        if (!in_array($sortColumn, $allowedColumns)) $sortColumn = 'urutan';
        if (!in_array($sortDirection, ['asc', 'desc'])) $sortDirection = 'asc';

        $query = StrukturOrganisasi::query();

        if ($request->has('search') && $request->input('search') != '') {
            $searchTerm = $request->input('search');
            $query->where('nama', 'like', '%' . $searchTerm . '%')
                  ->orWhere('jabatan', 'like', '%' . $searchTerm . '%');
        }

        // MODIFIKASI: Prioritaskan urutan
        $query->orderBy($sortColumn, $sortDirection);

        $perPage = $request->input('per_page', 10);
        $organisasis = $query->paginate($perPage)->onEachSide(1);
        $organisasis->appends($request->only(['sort', 'direction', 'search', 'per_page']));

        return view('Admin.master.struktur.index', [
            'organisasis' => $organisasis,
            'perPage' => $perPage,
            'sortColumn' => $sortColumn,
            'sortDirection' => $sortDirection,
        ]);
    }

    /**
     * Menampilkan form tambah Struktur.
     */
    public function create()
    {
        return view('Admin.master.struktur.add_struktur');
    }

    /**
     * Menyimpan Struktur baru.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama'      => 'required|string|max:255',
            'jabatan'   => 'required|string|max:255',
            // MODIFIKASI: Tambahkan validasi urutan (wajib diisi angka)
            'urutan'    => 'required|integer', 
            'gambar'    => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $data = $request->all();

        if ($request->hasFile('gambar')) {
            $file = $request->file('gambar');
            $filename = time() . '_' . $file->getClientOriginalName();
            $data['gambar'] = $file->storeAs('struktur_organisasi', $filename, 'public');
        }

        StrukturOrganisasi::create($data);

        return redirect()->route('admin.master_struktur')
                     ->with('success', 'Data Struktur berhasil ditambahkan.');
    }

    /**
     * Menampilkan form edit Struktur.
     */
    public function edit($id)
    {
        $struktur = StrukturOrganisasi::findOrFail($id);
        return view('Admin.master.struktur.edit_struktur', [
            'organisasi' => $struktur
        ]);
    }

    /**
     * Mengupdate data Struktur.
     */
    public function update(Request $request, $id)
    {
        $struktur = StrukturOrganisasi::findOrFail($id);

        $request->validate([
            'nama'      => 'required|string|max:255',
            'jabatan'   => 'required|string|max:255',
            // MODIFIKASI: Tambahkan validasi urutan saat update
            'urutan'    => 'required|integer', 
            'gambar'    => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $data = $request->all();

        if ($request->hasFile('gambar')) {
            if ($struktur->gambar && Storage::disk('public')->exists($struktur->gambar)) {
                Storage::disk('public')->delete($struktur->gambar);
            }
            $file = $request->file('gambar');
            $filename = time() . '_' . $file->getClientOriginalName();
            $data['gambar'] = $file->storeAs('struktur_organisasi', $filename, 'public');
        }

        $struktur->update($data);

        return redirect()->route('admin.master_struktur')
                 ->with('success', 'Data Struktur berhasil diperbarui.');
    }

    /**
     * Menghapus Struktur.
     */
    public function destroy($id)
    {
        $struktur = StrukturOrganisasi::findOrFail($id);
        
        if ($struktur->gambar && Storage::disk('public')->exists($struktur->gambar)) {
            Storage::disk('public')->delete($struktur->gambar);
        }

        $struktur->delete();

        return redirect()->route('admin.master_struktur')
                 ->with('success', 'Data Struktur berhasil dihapus.');
    }
}
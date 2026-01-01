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
        $sortColumn = $request->input('sort', 'id');
        $sortDirection = $request->input('direction', 'asc');
        $allowedColumns = ['id', 'nama', 'jabatan'];

        if (!in_array($sortColumn, $allowedColumns)) $sortColumn = 'id';
        if (!in_array($sortDirection, ['asc', 'desc'])) $sortDirection = 'asc';

        $query = StrukturOrganisasi::query();

        if ($request->has('search') && $request->input('search') != '') {
            $searchTerm = $request->input('search');
            $query->where('nama', 'like', '%' . $searchTerm . '%')
                  ->orWhere('jabatan', 'like', '%' . $searchTerm . '%');
        }

        $query->orderBy($sortColumn, $sortDirection);

        $perPage = $request->input('per_page', 10);
        $organisasis = $query->paginate($perPage)->onEachSide(1);
        $organisasis->appends($request->only(['sort', 'direction', 'search', 'per_page']));

        return view('admin.master.struktur.index', [
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
        return view('admin.master.struktur.create');
    }

    /**
     * Menyimpan Struktur baru.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama'      => 'required|string|max:255',
            'jabatan'   => 'required|string|max:255',
            'gambar'    => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $data = $request->all();

        if ($request->hasFile('gambar')) {
            $file = $request->file('gambar');
            $filename = time() . '_' . $file->getClientOriginalName();
            // Simpan ke folder struktur_organisasi
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
        return view('admin.master.struktur.edit', compact('struktur'));
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
            'gambar'    => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $data = $request->all();

        if ($request->hasFile('gambar')) {
            // Hapus gambar lama jika ada
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
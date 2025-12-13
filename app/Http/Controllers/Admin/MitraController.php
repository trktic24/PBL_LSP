<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Mitra;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MitraController extends Controller
{
    /**
     * Menampilkan list Mitra.
     */
    public function index(Request $request)
    {
        $sortColumn = $request->input('sort', 'id');
        $sortDirection = $request->input('direction', 'asc');
        $allowedColumns = ['id', 'nama_mitra'];

        if (!in_array($sortColumn, $allowedColumns)) $sortColumn = 'id';
        if (!in_array($sortDirection, ['asc', 'desc'])) $sortDirection = 'asc';

        $query = Mitra::query();

        if ($request->has('search') && $request->input('search') != '') {
            $searchTerm = $request->input('search');
            $query->where('nama_mitra', 'like', '%' . $searchTerm . '%');
        }

        $query->orderBy($sortColumn, $sortDirection);

        $perPage = $request->input('per_page', 10);
        $mitras = $query->paginate($perPage)->onEachSide(0.5);
        $mitras->appends($request->only(['sort', 'direction', 'search', 'per_page']));

        return view('admin.master.mitra.master_mitra', [
            'mitras' => $mitras,
            'perPage' => $perPage,
            'sortColumn' => $sortColumn,
            'sortDirection' => $sortDirection,
        ]);
    }

    /**
     * Menampilkan form tambah Mitra.
     */
    public function create()
    {
        return view('admin.master.mitra.add_mitra');
    }

    /**
     * Menyimpan Mitra baru.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_mitra' => 'required|string|max:255',
            'url'        => 'nullable|url',
            'logo'       => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $data = $request->all();

        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('mitra_logos', 'public');
            $data['logo'] = $path; // Simpan path relatif public/
        }

        Mitra::create($data);

        return redirect()->route('admin.master_mitra')
                         ->with('success', 'Mitra berhasil ditambahkan.');
    }

    /**
     * Menampilkan form edit Mitra.
     */
    public function edit($id)
    {
        $mitra = Mitra::findOrFail($id);
        return view('admin.master.mitra.edit_mitra', compact('mitra'));
    }

    /**
     * Mengupdate data Mitra.
     */
    public function update(Request $request, $id)
    {
        $mitra = Mitra::findOrFail($id);

        $request->validate([
            'nama_mitra' => 'required|string|max:255',
            'url'        => 'nullable|url',
            'logo'       => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $data = $request->all();

        if ($request->hasFile('logo')) {
            // Hapus logo lama jika ada
            if ($mitra->logo && Storage::disk('public')->exists($mitra->logo)) {
                Storage::disk('public')->delete($mitra->logo);
            }
            $path = $request->file('logo')->store('mitra_logos', 'public');
            $data['logo'] = $path;
        }

        $mitra->update($data);

        return redirect()->route('admin.master_mitra')
                         ->with('success', 'Mitra berhasil diperbarui.');
    }

    /**
     * Menghapus Mitra.
     */
    public function destroy($id)
    {
        $mitra = Mitra::findOrFail($id);
        
        if ($mitra->logo && Storage::disk('public')->exists($mitra->logo)) {
            Storage::disk('public')->delete($mitra->logo);
        }

        $mitra->delete();

        return redirect()->route('admin.master_mitra')
                         ->with('success', 'Mitra berhasil dihapus.');
    }
}

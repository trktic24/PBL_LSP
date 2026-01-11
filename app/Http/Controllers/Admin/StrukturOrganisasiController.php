<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StrukturOrganisasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class StrukturOrganisasiController extends Controller
{
    public function index(Request $request)
    {
        // Default sort berdasarkan 'urutan' ASC (1, 2, 3...)
        $sortColumn = $request->input('sort', 'urutan'); 
        $sortDirection = $request->input('direction', 'asc');
        $allowedColumns = ['id', 'nama', 'jabatan', 'urutan'];

        if (!in_array($sortColumn, $allowedColumns)) $sortColumn = 'urutan';
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

        // Pastikan view ini mengarah ke file index.blade.php di folder Admin/master/struktur
        return view('Admin.master.struktur.index', [
            'organisasis' => $organisasis,
            'perPage' => $perPage,
            'sortColumn' => $sortColumn,
            'sortDirection' => $sortDirection,
        ]);
    }

    public function create()
    {
        return view('Admin.master.struktur.add_struktur');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama'      => 'required|string|max:255',
            'jabatan'   => 'required|string|max:255',
            'urutan'    => 'required|integer', 
            // Limit 5MB (5120 KB)
            'gambar'    => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:5120',
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

    public function edit($id)
    {
        $struktur = StrukturOrganisasi::findOrFail($id);
        // Kirim variabel sebagai 'organisasi' agar cocok dengan view
        return view('Admin.master.struktur.edit_struktur', [
            'organisasi' => $struktur
        ]);
    }

    public function update(Request $request, $id)
    {
        $struktur = StrukturOrganisasi::findOrFail($id);

        $request->validate([
            'nama'      => 'required|string|max:255',
            'jabatan'   => 'required|string|max:255',
            'urutan'    => 'required|integer', 
            // Limit 5MB
            'gambar'    => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:5120',
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
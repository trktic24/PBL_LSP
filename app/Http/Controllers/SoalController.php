<?php

namespace App\Http\Controllers;

use App\Models\SoalIA06;
use App\Models\KunciIA06;
use Illuminate\Http\Request;

class SoalController extends Controller
{
    // Menampilkan semua soal
    public function index()
    {
        $soals = SoalIA06::with('kuncis')->get();
        return view('soal.index', compact('soals'));
    }

    // Form tambah soal
    public function create()
    {
        return view('soal.create');
    }

    // Simpan soal baru
    public function store(Request $request)
    {
        $validated = $request->validate([
            'soal_IA06' => 'required|string|max:255',
        ]);

        $soal = SoalIA06::create($validated);
        return redirect()->route('soal.index')->with('success', 'Soal berhasil ditambahkan.');
    }

    // Form edit soal
    public function edit($id)
    {
        $soal = SoalIA06::with('kuncis')->findOrFail($id);
        return view('soal.edit', compact('soal'));
    }

    // Update soal
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'soal_IA06' => 'required|string|max:255',
        ]);

        $soal = SoalIA06::findOrFail($id);
        $soal->update($validated);

        return redirect()->route('soal.index')->with('success', 'Soal berhasil diperbarui.');
    }

    // Hapus soal beserta kunci
    public function destroy($id)
    {
        $soal = SoalIA06::findOrFail($id);
        $soal->kuncis()->delete();
        $soal->delete();

        return redirect()->route('soal.index')->with('success', 'Soal dan kunci terkait berhasil dihapus.');
    }

    // Tambah kunci jawaban ke soal
    public function storeKunci(Request $request, $id)
    {
        $validated = $request->validate([
            'kunci_IA06' => 'required|string|max:255',
        ]);

        $soal = SoalIA06::findOrFail($id);
        $soal->kuncis()->create($validated);

        return back()->with('success', 'Kunci jawaban berhasil ditambahkan.');
    }

    // Edit kunci jawaban
    public function updateKunci(Request $request, $id)
    {
        $validated = $request->validate([
            'kunci_IA06' => 'required|string|max:255',
        ]);

        $kunci = KunciIA06::findOrFail($id);
        $kunci->update($validated);

        return back()->with('success', 'Kunci jawaban berhasil diperbarui.');
    }

    // Hapus satu kunci jawaban
    public function destroyKunci($id)
    {
        $kunci = KunciIA06::findOrFail($id);
        $kunci->delete();

        return back()->with('success', 'Kunci jawaban berhasil dihapus.');
    }

    public function jawabIndex()
{
    $soals = SoalIA06::with('kuncis')->get();
    return view('jawab.index', compact('soals'));
}

// Simpan jawaban peserta
public function jawabStore(Request $request)
{
    $validated = $request->validate([
        'jawaban' => 'required|array',
    ]);

    // Contoh: proses simpan atau periksa benar/salah
    $hasil = [];
    foreach ($validated['jawaban'] as $soal_id => $kunci_id) {
        $soal = SoalIA06::find($soal_id);
        $kunci = KunciIA06::find($kunci_id);
        $hasil[] = [
            'soal' => $soal->soal_IA06,
            'jawaban' => $kunci->kunci_IA06,
        ];
    }

    return view('jawab.hasil', compact('hasil'));
}
public function onlySoal()
{
    $soals = SoalIA06::with('kuncis')->get();
    return view('only-soal', compact('soals'));
}

}

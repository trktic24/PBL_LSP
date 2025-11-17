<?php

namespace App\Http\Controllers;

use App\Models\SoalIA06;   // Model untuk tabel 'soal_ia06'
use App\Models\KunciIA06;   // Model untuk tabel 'kunci_ia06' (Jawaban Asesi)
use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Auth; // Anda akan perlu ini nanti untuk ID Asesi

class SoalController extends Controller
{
    /**
     * UNTUK ADMIN (FR.IA.06A - Daftar Pertanyaan)
     * Menampilkan view: 'frontend/fr_IA_06_a.blade.php'
     */
    public function index()
    {
        // Mengambil semua soal dari tabel 'soal_ia06'
        $soals = SoalIA06::all();

        // Mengirim data ke view
        // 'soalItems' adalah nama variabel yang dipakai di view Anda
        return view('frontend.fr_IA_06_a', ['soalItems' => $soals]);
    }

    /**
     * UNTUK ADMIN (FR.IA.06B - Kunci Jawaban Master)
     * Menampilkan view: 'frontend/fr_IA_06_b.blade.php'
     */
    public function kunciIndex()
    {
        // Mengambil semua soal, yang sekarang juga berisi kunci jawaban master
        $soals = SoalIA06::all();

        // Mengirim data ke view
        // View 'fr_IA_06_b' Anda bisa menampilkan
        // $soal->soal_ia06 dan $soal->kunci_jawaban_ia06
        return view('frontend.fr_IA_06_b', ['soalItems' => $soals]);
    }

    /**
     * UNTUK ASESI (FR.IA.06C - Form Ujian)
     * Menampilkan view: 'frontend/fr_IA_06_c.blade.php'
     */
    public function jawabIndex()
    {
        // Mengambil semua soal untuk ditampilkan ke asesi
        $soals = SoalIA06::all();
        return view('frontend.fr_IA_06_c', compact('soals'));
    }

    /**
     * UNTUK ASESI: Menyimpan jawaban dari 'fr_IA_06_c'
     * Ini akan menyimpan ke tabel 'kunci_ia06' (Tabel Jawaban Asesi)
     */
    public function jawabStore(Request $request)
    {
        $validated = $request->validate([
            // 'jawaban' berasal dari name="jawaban[...]" di view Anda
            'jawaban' => 'required|array',
            'jawaban.*' => 'nullable|string',
        ]);

        // GANTI INI: Dapatkan ID Asesi yang sedang login
        // $id_sertifikasi_asesi = Auth::user()->id_data_sertifikasi_asesi;
        $id_sertifikasi_asesi = 1; // Placeholder, ganti dengan ID asesi yang valid

        // Hapus jawaban lama asesi ini (jika ada, agar bisa mengulang)
        KunciIA06::where('id_data_sertifikasi_asesi', $id_sertifikasi_asesi)->delete();

        // Simpan jawaban baru
        foreach ($validated['jawaban'] as $id_soal => $teks_jawaban) {

            // Hanya simpan jika asesi benar-benar menjawab (tidak kosong)
            if ($teks_jawaban) {
                KunciIA06::create([
                    'id_soal_ia06' => $id_soal,
                    'id_data_sertifikasi_asesi' => $id_sertifikasi_asesi,
                    'teks_jawaban_ia06' => $teks_jawaban // <-- Menyimpan ke kolom yang benar
                ]);
            }
        }

        // Redirect kembali ke halaman form ujian (sesuai nama rute Anda)
        return redirect()->route('fr_IA_06_c')
                         ->with('success', 'Jawaban Anda berhasil disimpan.');
    }


    // =================================================================
    // FUNGSI ADMIN CRUD (Soal & Kunci Jawaban Master)
    // =================================================================

    /**
     * Menampilkan form untuk membuat soal baru
     * (Memerlukan view 'soal.create')
     */
    public function create()
    {
        return view('soal.create'); // (View admin terpisah)
    }

    /**
     * Menyimpan soal BARU beserta Kunci Jawabannya ke tabel 'soal_ia06'
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'soal_ia06' => 'required|string',
            'kunci_jawaban_ia06' => 'nullable|string', // <-- Kunci Master
        ]);

        SoalIA06::create($validated);

        // Redirect ke halaman daftar pertanyaan admin
        return redirect()->route('fr_IA_06_a')->with('success', 'Soal berhasil ditambahkan.');
    }

    /**
     * Menampilkan form untuk mengedit soal
     * (Memerlukan view 'soal.edit')
     */
    public function edit($id)
    {
        $soal = SoalIA06::findOrFail($id);
        return view('soal.edit', compact('soal')); // (View admin terpisah)
    }

    /**
     * Update soal beserta Kunci Jawabannya di tabel 'soal_ia06'
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'soal_ia06' => 'required|string',
            'kunci_jawaban_ia06' => 'nullable|string', // <-- Kunci Master
        ]);

        $soal = SoalIA06::findOrFail($id);
        $soal->update($validated);

        // Redirect ke halaman daftar pertanyaan admin
        return redirect()->route('fr_IA_06_a')->with('success', 'Soal berhasil diperbarui.');
    }

    /**
     * Hapus soal dari tabel 'soal_ia06'
     */
    public function destroy($id)
    {
        // Saat Soal dihapus, jawaban asesi (dari tabel 'kunci_ia06')
        // akan ikut terhapus otomatis karena 'onDelete('cascade')' di migrasi Anda.
        $soal = SoalIA06::findOrFail($id);
        $soal->delete();

        return redirect()->route('fr_IA_06_a')->with('success', 'Soal berhasil dihapus.');
    }

    /**
     * Fungsi duplikat, sama dengan index()
     */
    public function onlySoal()
    {
        $soals = SoalIA06::all();
        return view('frontend.fr_IA_06_a', ['soalItems' => $soals]);
    }
}
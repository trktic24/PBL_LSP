<?php

namespace App\Http\Controllers;

use App\Models\SoalIA06;
use Illuminate\Http\Request;

// PERBAIKAN: Nama Class diganti menjadi IA06Controller (Sesuai nama file)
class IA06Controller extends Controller
{
    /**
     * MENAMPILKAN HALAMAN UTAMA (Daftar Soal + Modal)
     */
    public function index()
    {
        // Ambil semua data soal
        $soals = SoalIA06::all();

        // Kirim data ke view 'frontend.fr_IA_06_a'
        return view('frontend.fr_IA_06_a', ['soalItems' => $soals]);
    }

    /**
     * MENYIMPAN SOAL BARU (Dari Modal Tambah)
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'soal_ia06' => 'required|string',
            'kunci_jawaban_ia06' => 'nullable|string',
        ]);

        SoalIA06::create($validated);

        // Redirect kembali ke halaman yang sama (bukan ke index controller lain)
        return redirect()->route('fr_IA_06_a')->with('success', 'Soal berhasil ditambahkan.');
    }

    /**
     * UPDATE SOAL (Dari Modal Edit)
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'soal_ia06' => 'required|string',
            'kunci_jawaban_ia06' => 'nullable|string',
        ]);

        $soal = SoalIA06::findOrFail($id);
        $soal->update($validated);

        // Redirect kembali ke halaman yang sama
        return redirect()->route('fr_IA_06_a')->with('success', 'Soal berhasil diperbarui.');
    }

    /**
     * HAPUS SOAL
     */
    public function destroy($id)
    {
        $soal = SoalIA06::findOrFail($id);
        $soal->delete();

        return redirect()->route('fr_IA_06_a')->with('success', 'Soal berhasil dihapus.');
    }

    // --- Method create() dan edit() tidak lagi dibutuhkan karena kita pakai Modal ---
    // --- Tapi jika ingin dibiarkan kosong/tidak ada, tidak masalah ---

    public function kunciIndex()
    {
        // Ambil semua data soal
        $soals = SoalIA06::all();

        // Kirim ke view 'frontend.fr_IA_06_b'
        return view('frontend.fr_IA_06_b', ['soalItems' => $soals]);
    }

    public function jawabIndex()
    {
        $soals = SoalIA06::all();
        return view('frontend.fr_IA_06_c', ['soalItems' => $soals]);
    }

    /**
     * UNTUK ASESI: Menyimpan Jawaban ke Database
     */
    public function jawabStore(Request $request)
    {
        // 1. Validasi
        $request->validate([
            'jawaban' => 'required|array', // Harus berupa array
            'jawaban.*' => 'nullable|string',
            // 'nama_asesi' => 'required|string', // Opsional jika ingin validasi nama
        ]);

        // 2. ID Asesi (Nanti diganti Auth::user()->id)
        $id_asesi_dummy = 1;

        // 3. Simpan Jawaban (Looping array jawaban)
        // Format name di view adalah: jawaban[ID_SOAL]
        foreach ($request->jawaban as $id_soal => $teks_jawaban) {

            // Cek apakah jawaban untuk soal ini sudah ada? (Update or Create)
            \App\Models\KunciIA06::updateOrCreate(
                [
                    'id_soal_ia06' => $id_soal,
                    'id_data_sertifikasi_asesi' => $id_asesi_dummy
                ],
                [
                    'teks_jawaban_ia06' => $teks_jawaban ?? '-' // Isi '-' jika kosong
                ]
            );
        }

        // 4. Redirect kembali dengan pesan sukses
        // Pesan 'success' ini yang akan memicu Modal Alpine.js
        return redirect()->route('fr_IA_06_c')->with('success', 'Jawaban berhasil dikirim!');
    }
}
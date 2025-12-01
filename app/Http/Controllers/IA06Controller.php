<?php

namespace App\Http\Controllers;

use App\Models\SoalIA06;
use App\Models\KunciIA06;
use App\Models\Validator; // <-- PENTING: Import Model Validator
use Illuminate\Http\Request;

class IA06Controller extends Controller
{
    // =================================================================
    // 1. ADMIN - DAFTAR PERTANYAAN (FR.IA.06.A)
    // =================================================================
    public function index()
    {
        // Ambil semua data soal
        $soals = SoalIA06::all();

        // Ambil semua data validator (untuk tabel validator di bawah form)
        $validators = Validator::all();

        // Kirim ke view 06A
        return view('frontend.fr_IA_06_a', [
            'soalItems' => $soals,
            'validators' => $validators // <-- Kirim data validator
        ]);
    }

    // =================================================================
    // 2. ADMIN/ASESOR - KUNCI JAWABAN & PENILAIAN (FR.IA.06.B)
    // =================================================================
    public function kunciIndex()
    {
        // ID Dummy Asesi (Simulasi ID 1)
        // Jika nanti sudah pakai login, ganti jadi: $id_asesi = auth()->id();
        $id_asesi = 1;

        // Ambil soal BESERTA jawaban dari asesi tersebut
        // Menggunakan eager loading (with) agar query efisien
        $soals = SoalIA06::with(['jawabanAsesi' => function($query) use ($id_asesi) {
            $query->where('id_data_sertifikasi_asesi', $id_asesi);
        }])->get();

        // Ambil data validator
        $validators = Validator::all();

        // Kirim ke view 06B
        return view('frontend.fr_IA_06_b', [
            'soalItems' => $soals,
            'validators' => $validators // <-- Kirim data validator
        ]);
    }

    // =================================================================
    // 3. ASESI - FORM UJIAN (FR.IA.06.C)
    // =================================================================
    public function jawabIndex()
    {
        // Di halaman asesi, biasanya hanya butuh daftar soal untuk dijawab
        $soals = SoalIA06::all();

        $validators = Validator::all();

        return view('frontend.fr_IA_06_c', [
            'soalItems' => $soals,
            'validators' => $validators
        ]);
    }

    /**
     * Simpan Jawaban Asesi (FR.IA.06.C)
     */
    public function jawabStore(Request $request)
    {
        // Validasi input
        $request->validate([
            'jawaban' => 'required|array',
            'jawaban.*' => 'nullable|string',
        ]);

        $id_asesi_dummy = 1; // Simulasi ID Asesi

        // Loop setiap jawaban yang dikirim
        foreach ($request->jawaban as $id_soal => $teks_jawaban) {
            // Update jika sudah ada, Create jika belum
            KunciIA06::updateOrCreate(
                [
                    'id_soal_ia06' => $id_soal,
                    'id_data_sertifikasi_asesi' => $id_asesi_dummy
                ],
                [
                    'teks_jawaban_ia06' => $teks_jawaban ?? '-'
                ]
            );
        }

        return redirect()->route('fr_IA_06_c')->with('success', 'Jawaban berhasil dikirim!');
    }


    // =================================================================
    // 4. FUNGSI CRUD SOAL (Untuk Modal di Halaman 06A)
    // =================================================================

    // Simpan Soal Baru
    public function store(Request $request)
    {
        $validated = $request->validate([
            'soal_ia06' => 'required|string',
            'kunci_jawaban_ia06' => 'nullable|string',
        ]);

        SoalIA06::create($validated);

        return redirect()->route('fr_IA_06_a')->with('success', 'Soal berhasil ditambahkan.');
    }

    // Update Soal Lama
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'soal_ia06' => 'required|string',
            'kunci_jawaban_ia06' => 'nullable|string',
        ]);

        $soal = SoalIA06::findOrFail($id);
        $soal->update($validated);

        return redirect()->route('fr_IA_06_a')->with('success', 'Soal berhasil diperbarui.');
    }

    // Hapus Soal
    public function destroy($id)
    {
        $soal = SoalIA06::findOrFail($id);
        $soal->delete();

        return redirect()->route('fr_IA_06_a')->with('success', 'Soal berhasil dihapus.');
    }
}
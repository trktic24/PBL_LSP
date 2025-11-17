<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ia10s; // 1. Hanya model ini yang kita perlukan
use App\Models\DataSertifikasiAsesi; // 2. Untuk ambil data Asesi
use App\Models\User; // (Asumsi) Untuk ambil data Asesor yang login
use Illuminate\Support\Facades\Auth; // Untuk ambil data Asesor yang login

class IA10Controller extends Controller
{
    /**
     * Tampilkan form IA-10.
     * Kita tidak perlu ambil pertanyaan, cukup data asesi & asesor.
     */
    public function create($id_asesi) // Anda tetap perlu ID Asesi
    {
        // 1. Ambil data asesi
        $asesi = DataSertifikasiAsesi::findOrFail($id_asesi);

        // 2. Ambil data asesor (user yang sedang login)
        $asesor = Auth::user();

        // 3. Tampilkan view dan kirim data asesi & asesor
        return view('frontend.fr_IA_10', [ // Sesuaikan nama view jika beda
            'asesi' => $asesi,
            'asesor' => $asesor,
        ]);
    }

    /**
     * Simpan data dari form IA-10 ke tabel 'ia10s'.
     */
    public function store(Request $request)
    {
        // 1. Validasi data (contoh sederhana)
        $request->validate([
            'nama_asesi' => 'required|string',
            'nama_asesor' => 'required|string',
            'supervisor_name' => 'required|string',
            'q1' => 'required|in:ya,tidak', // Pastikan semua Q terisi
            'q2' => 'required|in:ya,tidak',
            'q3' => 'required|in:ya,tidak',
            'q4' => 'required|in:ya,tidak',
            'q5' => 'required|in:ya,tidak',
            'q6' => 'required|in:ya,tidak',
            'relation' => 'required|string',
            // ... tambahkan validasi lain jika perlu
        ]);

        try {
            // 2. Simpan SEMUA data ke tabel 'ia10s' dalam satu perintah
            // Pastikan $fillable di Model (Langkah 1) sudah lengkap
            Ia10s::create($request->all());

            return redirect()->back()->with('success', 'Form IA.10 berhasil disimpan!');

        } catch (\Exception $e) {
            // 3. Jika gagal
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\ia02;
use App\Models\SkenarioIa02;
// Ann-Note: Pastikan Anda meng-import model DataSertifikasiAsesi Anda
// use App\Models\DataSertifikasiAsesi; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class IA02Controller extends Controller
{
    /**
     * Menampilkan form FR.IA.02 yang sudah terisi data
     * (Tahap 4 - Menghubungkan ke View)
     *
     * @param string $id (id_data_sertifikasi_asesi)
     * @return View
     */
    public function show(string $id): View
    {
        // Ann-Note: Asumsi $id adalah 'id_data_sertifikasi_asesi'
        // dan model 'DataSertifikasiAsesi' Anda sudah memiliki relasi
        // ke 'asesi', 'asesor', dan 'skema'.

        /*
        // --- CONTOH PENGAMBILAN DATA ---
        // 1. Cari data sertifikasi utama berdasarkan ID
        $sertifikasi = DataSertifikasiAsesi::with([
                            'asesi', 
                            'asesor', 
                            'skema', 
                            'skema.unitKompetensis' // Asumsi ada relasi ini
                        ])
                        ->findOrFail($id);

        // 2. Cek apakah asesor yang login berhak mengakses
        // (Tambahkan logika keamanan ini nanti)
        // if ($sertifikasi->asesor->user_id !== Auth::id()) {
        //     abort(403, 'Tidak diizinkan');
        // }

        // 3. Cari data skenario IA.02 yang sudah ada, atau buat objek baru jika belum
        $skenario = SkenarioIa02::firstOrNew([
            'id_data_sertifikasi_asesi' => $id
        ]);

        // 4. Kirim semua data ke View
        return view('asesor.fr_ia_02', [
            'sertifikasi' => $sertifikasi,
            'skenario' => $skenario,
            // 'unitKompetensis' => $sertifikasi->skema->unitKompetensis, // Untuk tabel Kelompok Pekerjaan
        ]);
        */
        
        // --- UNTUK SEKARANG (STATIC VIEW) ---
        // Karena kita belum punya model DataSertifikasiAsesi,
        // kita akan tampilkan view statis seperti yang sudah Anda buat.
        // Hapus komentar di atas dan hapus baris di bawah ini jika model Anda sudah siap.
        return view('asesor.fr_ia_02');
    }

    /**
     * Menyimpan data dari form FR.IA.02
     *
     * @param Request $request
     * @param string $id (id_data_sertifikasi_asesi)
     * @return RedirectResponse
     */
    public function store(Request $request, string $id): RedirectResponse
    {
        // 1. Validasi data yang masuk dari form
        $validated = $request->validate([
            'skenario' => 'required|string',
            'peralatan' => 'required|string',
            'waktu' => 'required|string|max:100',
            // Tambahkan validasi untuk tabel Kelompok Pekerjaan jika perlu
        ]);

        // 2. Gunakan updateOrCreate()
        // - Jika data dengan id_data_sertifikasi_asesi = $id sudah ada, data akan di-update.
        // - Jika belum ada, data baru akan dibuat.
        ia02::updateOrCreate(
            ['id_data_sertifikasi_asesi' => $id], // Kunci pencarian
            [
                'skenario' => $validated['skenario'],
                'peralatan' => $validated['peralatan'],
                'waktu' => $validated['waktu'],
            ] // Data yang akan di-update atau di-create
        );

        // 3. Kembalikan ke halaman sebelumnya dengan pesan sukses
        return redirect()->back()
                         ->with('success', 'Data Skenario FR.IA.02 berhasil disimpan!');
    }
}

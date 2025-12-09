<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\ia02;
// use App\Models\SkenarioIa02; // Removed as it doesn't exist
use App\Models\DataSertifikasiAsesi;
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
        // 1. Cari data sertifikasi utama berdasarkan ID
        $sertifikasi = DataSertifikasiAsesi::with([
            'asesi.user',
            'jadwal.asesor',
            'jadwal.skema.unitKompetensi', // Relasi ke Unit Kompetensi via Skema
            'jadwal.tuk'
        ])
            ->findOrFail($id);

        // 2. Cari data skenario IA.02 yang sudah ada, atau buat objek baru jika belum
        $skenario = ia02::firstOrNew([
            'id_data_sertifikasi_asesi' => $id
        ]);

        // 3. Ambil data unit kompetensi untuk ditampilkan di tabel
        $unitKompetensis = $sertifikasi->jadwal->skema->unitKompetensi ?? collect();

        // 4. Kirim semua data ke View
        return view('frontend.FR_IA_02', [
            'sertifikasi' => $sertifikasi,
            'skenario' => $skenario,
            'jadwal' => $sertifikasi->jadwal,
            'asesi' => $sertifikasi->asesi,
            'skema' => $sertifikasi->jadwal->skema,
            'unitKompetensis' => $unitKompetensis,
        ]);
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
        ]);

        // 2. Gunakan updateOrCreate()
        ia02::updateOrCreate(
            ['id_data_sertifikasi_asesi' => $id],
            [
                'skenario' => $validated['skenario'],
                'peralatan' => $validated['peralatan'],
                'waktu' => $validated['waktu'],
            ]
        );

        // 3. Kembalikan ke halaman sebelumnya dengan pesan sukses
        return redirect()->back()
            ->with('success', 'Data Skenario FR.IA.02 berhasil disimpan!');
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\DataSertifikasiAsesi; // <-- Panggil model pivot-mu
use App\Models\Asesor;

class AsesiTrackerController extends Controller
{
    /**
     * Menampilkan halaman tracker untuk satu asesi pada jadwal tertentu.
     *
     * @param int $id_sertifikasi_asesi (Ini adalah $asesi->pivot->id_data_sertifikasi_asesi)
     */
    public function show($id_sertifikasi_asesi)
    {
        // 1. Cari data pivot berdasarkan Primary Key-nya
        // Kita eager load relasi 'asesi' dan 'jadwal' (beserta skema-nya)
        $dataSertifikasi = DataSertifikasiAsesi::with([
                                'asesi.user', // Ambil data asesi & user-nya
                                'jadwal.skema', // Ambil data jadwal & skema-nya
                                'jadwal.tuk'    // Ambil data TUK-nya juga
                            ])
                            ->findOrFail($id_sertifikasi_asesi);
        
        // --- Tambahan: Otorisasi (Sangat Penting) ---
        // Pastikan Asesor yang login adalah asesor yang benar
        $asesor = Asesor::where('user_id', Auth::id())->first();
        if (!$asesor || $dataSertifikasi->jadwal->id_asesor != $asesor->id_asesor) {
             abort(403, 'Anda tidak berhak mengakses data ini.');
        }
        // --- Selesai Otorisasi ---


        // 2. Siapkan data untuk view
        $asesi = $dataSertifikasi->asesi;
        $jadwal = $dataSertifikasi->jadwal;

        // 3. Kirim data ke view 'tracker'
        return view('frontend.tracker', [
            'asesi' => $asesi,
            'jadwal' => $jadwal,
            'dataSertifikasi' => $dataSertifikasi // Ini berisi semua status (pra_asesmen, dll)
        ]);
    }
}

<?php

namespace App\Http\Controllers\Asesor;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\DataSertifikasiAsesi; // <-- Panggil model pivot-mu
use App\Models\Asesor;
use App\Models\ResponApl2Ia01;

use App\Http\Controllers\Controller;

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
            'jadwal.mastertuk',    // Ambil data TUK-nya juga
            'responbuktiAk01',
            'ia10', // <-- Load data IA10
            'ia02',
            'ia07',
            'ia06Answers',
        ])
            ->findOrFail($id_sertifikasi_asesi);

        // --- Tambahan: Otorisasi (Sangat Penting) ---
        // Pastikan Asesor yang login adalah asesor yang benar
        $asesor = Asesor::where('id_user', Auth::id())->first();
        if (!$asesor || $dataSertifikasi->jadwal->id_asesor != $asesor->id_asesor) {
            abort(403, 'Anda tidak berhak mengakses data ini.');
        }
        // --- Selesai Otorisasi ---


        // 2. Siapkan data untuk view
        $asesi = $dataSertifikasi->asesi;
        $jadwal = $dataSertifikasi->jadwal;

        // 3. Kirim data ke view 'tracker'
        return view('asesor.tracker', [
            'asesi' => $asesi,
            'jadwal' => $jadwal,
            'dataSertifikasi' => $dataSertifikasi // Ini berisi semua status (pra_asesmen, dll)
        ]);
    }  
}

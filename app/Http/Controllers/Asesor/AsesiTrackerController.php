<?php

namespace App\Http\Controllers\Asesor;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\DataSertifikasiAsesi; // <-- Panggil model pivot-mu
use App\Models\Asesor;
use App\Models\ResponApl02Ia01;
use App\Models\LembarJawabIA05;

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
            'jadwal.skema.listForm',
            'jadwal.mastertuk',    // Ambil data TUK-nya juga
            'responbuktiAk01',
            'ia10', // <-- Load data IA10
            'ia02',
            'ia07',
            'ia06Answers',
        ])
            ->findOrFail($id_sertifikasi_asesi);

        // --- Tambahan: Otorisasi (Sangat Penting) ---
        // Pastikan Asesor yang login adalah asesor yang benar (Atau Admin/Superadmin)
        $user = Auth::user();
        if ($user->hasRole('admin') || $user->hasRole('superadmin')) {
            // Admin can access all trackers
        } else {
            $asesor = Asesor::where('id_user', $user->id_user)->first();
            if (!$asesor || $dataSertifikasi->jadwal->id_asesor != $asesor->id_asesor) {
                abort(403, 'Anda tidak berhak mengakses data ini.');
            }
        }
        // --- Selesai Otorisasi ---

        // ==========================================================
        // 🔥 LOGIKA BARU: CEK STATUS IA.05 (Tanpa Kolom Status) 🔥
        // ==========================================================
        $is_ia05_graded = LembarJawabIA05::where('id_data_sertifikasi_asesi', $id_sertifikasi_asesi)
            ->whereNotNull('pencapaian_ia05') // Cek apakah kolom nilai sudah terisi ('ya'/'tidak')
            ->exists(); // Hasilnya TRUE (sudah dinilai) atau FALSE (belum)
        

        $has_ak02_data = \App\Models\Ak02::where('id_data_sertifikasi_asesi', $id_sertifikasi_asesi)->exists();
        $listForm = optional($dataSertifikasi->jadwal->skema->listForm);
        // ==========================================================

        // 2. Siapkan data untuk view
        $asesi = $dataSertifikasi->asesi;
        $jadwal = $dataSertifikasi->jadwal;

        // 3. Kirim data ke view 'tracker'
        return view('asesor.tracker', [
            'asesi' => $asesi,
            'jadwal' => $jadwal,
            'dataSertifikasi' => $dataSertifikasi, // Ini berisi semua status (pra_asesmen, dll)

            // 👇 KIRIM VARIABEL BARU INI KE BLADE
            'is_ia05_graded' => $is_ia05_graded,
            'has_ak02_data' => $has_ak02_data,
            'listForm' => $listForm,
        ]);
    }  
}

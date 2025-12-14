<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Asesi;
use Illuminate\Http\Request;


class AsesiProfileController extends Controller
{
    // Helper function untuk mengambil data asesi
    private function getAsesi($id)
    {
        return Asesi::with(['user', 'dataPekerjaan'])->findOrFail($id);
    }

    public function settings($id_asesi)
    {
        $asesi = $this->getAsesi($id_asesi);
        return view('admin.profile_asesi.asesi_profile_settings', compact('asesi'));
    }

   public function form($id_asesi)
    {
        // 1. Cari Data Sertifikasi (Pendaftaran) milik Asesi ini
        // Kita cari yang paling baru dibuat (latest)
        $pendaftaran = \App\Models\DataSertifikasiAsesi::with(['jadwal.skema.listForm'])
                            ->where('id_asesi', $id_asesi)
                            ->latest() // Urutkan dari yang terbaru
                            ->first();
        
        $activeForms = [];
        $namaSkema = 'Belum mendaftar skema';

        // 2. Cek Validitas Data Pendaftaran -> Jadwal -> Skema
        if ($pendaftaran && $pendaftaran->jadwal && $pendaftaran->jadwal->skema) {
            
            $skema = $pendaftaran->jadwal->skema;
            $namaSkema = $skema->nama_skema;

            // 3. Mapping Kode Form
            $map = [
                'apl_01' => 'FR.APL.01', 'apl_02' => 'FR.APL.02',
                'fr_mapa_01' => 'FR.MAPA.01', 'fr_mapa_02' => 'FR.MAPA.02',
                'fr_ak_01' => 'FR.AK.01', 'fr_ak_04' => 'FR.AK.04',
                'fr_ia_01' => 'FR.IA.01', 'fr_ia_02' => 'FR.IA.02', 'fr_ia_03' => 'FR.IA.03',
                'fr_ia_04' => 'FR.IA.04', 'fr_ia_05' => 'FR.IA.05', 'fr_ia_06' => 'FR.IA.06',
                'fr_ia_07' => 'FR.IA.07', 'fr_ia_08' => 'FR.IA.08', 'fr_ia_09' => 'FR.IA.09',
                'fr_ia_10' => 'FR.IA.10', 'fr_ia_11' => 'FR.IA.11',
                'fr_ak_02' => 'FR.AK.02', 'fr_ak_03' => 'FR.AK.03', 'fr_ak_05' => 'FR.AK.05',
                'fr_ak_06' => 'FR.AK.06',
            ];

            // 4. Cek Konfigurasi di Database (list_form)
            if ($skema->listForm) {
                $config = $skema->listForm;
                $formsFound = false;

                foreach ($map as $dbColumn => $displayCode) {
                    // Pastikan nilainya 1 (True)
                    if ($config->$dbColumn == 1) {
                        $activeForms[] = $displayCode;
                        $formsFound = true;
                    }
                }

                // FALLBACK: Jika config ada tapi isinya 0 semua (Admin belum centang)
                if (!$formsFound) {
                     $activeForms = array_values($map); // Tampilkan semua
                }

            } else {
                // FALLBACK: Jika Admin belum pernah simpan config sama sekali
                $activeForms = array_values($map); // Tampilkan semua
            }
        } 

        // 5. Ambil Data Asesi untuk Sidebar (Optional, jika view butuh object $asesi)
        $asesi = \App\Models\Asesi::findOrFail($id_asesi);

        // DEBUGGING SEMENTARA (Hapus tanda komentar // di bawah jika masih kosong)
        // dd($activeForms, $namaSkema, $pendaftaran);

        return view('admin.profile_asesi.asesi_profile_form', compact('asesi', 'activeForms', 'namaSkema'));
    }
    
    public function bukti($id_asesi)
    {
        $asesi = $this->getAsesi($id_asesi);
        return view('admin.profile_asesi.asesi_profile_bukti', compact('asesi'));
    }

    public function tracker($id_asesi)
    {
        $asesi = $this->getAsesi($id_asesi);
        return view('admin.profile_asesi.asesi_profile_tracker', compact('asesi'));
    }

}
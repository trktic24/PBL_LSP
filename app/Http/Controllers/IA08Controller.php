<?php

namespace App\Http\Controllers;

use App\Models\DataSertifikasiAsesi;
use App\Models\KelompokPekerjaan;
use App\Models\UnitKompetensi;
use App\Models\DataPortofolio;   // ← WAJIB ditambahkan
use Illuminate\Http\Request;

class IA08Controller extends Controller
{
    public function show($id_sertifikasi_asesi)
    {
        // ===============================
        // AMBIL DATA UTAMA
        // ===============================
        $data = DataSertifikasiAsesi::with([
            'asesi',                 
            'jadwal.tuk',
            'jadwal.skema',
            'jadwal.asesor',        
            'jadwal.jenisTuk',
        ])->findOrFail($id_sertifikasi_asesi);


        // ===============================
        // KELOMPOK PEKERJAAN
        // ===============================
        $kelompokPekerjaan = KelompokPekerjaan::where(
            'id_skema',
            $data->jadwal->skema->id_skema
        )->get();

        $kelompokIds = $kelompokPekerjaan->pluck('id_kelompok_pekerjaan');


        // ===============================
        // UNIT KOMPETENSI
        // ===============================
        $unitKompetensi = UnitKompetensi::whereIn(
            'id_kelompok_pekerjaan',
            $kelompokIds
        )
        ->orderBy('urutan')
        ->get();


        // ===============================
        // DATA BUKTI PORTOFOLIO (BARU)
        // ===============================
        $buktiPortofolio = DataPortofolio::where(
            'id_data_sertifikasi_asesi',
            $id_sertifikasi_asesi
        )
        ->select([
            'id_portofolio',
            'persyaratan_dasar',
            'persyaratan_administratif',
            'created_at'
        ])
        ->get();


        return view('frontend.IA_08.IA_08', [
            'skema'             => $data->jadwal->skema,
            'jenisTuk'          => $data->jadwal->jenisTuk,
            'asesor'            => $data->jadwal->asesor,
            'asesi'             => $data->asesi,
            'kelompokPekerjaan' => $kelompokPekerjaan,
            'unitKompetensi'    => $unitKompetensi,

            // ===============================
            // BUKTI PORTOFOLIO UNTUK TABEL
            // ===============================
            'buktiPortofolio'   => $buktiPortofolio,

            'data_sesi' => [
                'tanggal_asesmen' => $data->jadwal->tanggal_pelaksanaan
                    ? date('Y-m-d', strtotime($data->jadwal->tanggal_pelaksanaan))
                    : now()->format('Y-m-d'),
            ],
        ]);
    }
}
<?php

// Controller/AsesmenController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Asesor; 
use App\Models\Asesi; 

class AsesmenController extends Controller
{
    public function showFrAk01()
    {
        // 1. Ambil data dari database. Jika null, buat data dummy dari factory.
        $asesor = Asesor::first() ?? Asesor::factory()->make(['nama_lengkap' => 'DATA ASESOR DUMMY']);
        $asesi = Asesi::first() ?? Asesi::factory()->make(['nama_lengkap' => 'DATA ASESI DUMMY']);
        
        // 🚨 HAPUS/COMMENT BARIS DD INI!
        // dd($asesor, $asesi); 
        
        // 2. Siapkan data dummy untuk TUK dan Bukti
        $data_asesmen = [
            'tuk' => 'Mandiri', 
            'bukti_dikumpulkan' => [
                'Verifikasi Portofolio', 'Hasil Test Tulis', 'Hasil Wawancara',
            ],
        ];

        // 3. Kirimkan data ke View
        return view('persetujuan assesmen dan kerahasiaan.fr_ak01', [
            'asesor' => $asesor, 
            'asesi' => $asesi,
            'data_asesmen' => $data_asesmen,
        ]);
    }
}
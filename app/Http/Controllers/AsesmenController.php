<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Asesor; 
use App\Models\Asesi; 

class AsesmenController extends Controller
{
    /**
     * Method untuk menampilkan View (hanya kerangka HTML)
     * Data akan dimuat melalui JavaScript (Fetch API).
     */
    public function showFrAk01()
    {
        // View ini akan memuat data melalui API
        return view('persetujuan_assesmen_dan_kerahasiaan.fr_ak01'); 
    }

    /**
     * METHOD API: Menyediakan data dalam format JSON untuk View.
     */
    public function getFrAk01Data()
    {
        // 1. Definisikan path TTD dummy untuk fallback jika database kosong.
        // Path ini harus sesuai dengan format di DB Anda (e.g., images/tanda_tangan/file.png)
        $dummyTtdPath = 'images/tanda_tangan/ttd_asesi_1_1762621068.png'; 

        // 2. Ambil data dari database. Jika null, gunakan objek dasar untuk menghindari error factory.
        $asesor = Asesor::first() ?? (object)['nama_lengkap' => 'DATA ASESOR VIA API'];
        
        // Asesi diberi fallback TTD agar gambar muncul saat DB kosong
        $asesi = Asesi::first() ?? (object)[
            'nama_lengkap' => 'DATA ASESI VIA API',
            'tanda_tangan' => $dummyTtdPath, 
        ];
        
        // 3. Siapkan data dummy untuk TUK dan Bukti
        $data_asesmen = [
            'tuk' => 'Mandiri', 
            'bukti_dikumpulkan' => [
                'Verifikasi Portofolio', 'Hasil Test Tulis', 'Hasil Wawancara',
            ],
        ];

        // 4. KEMBALIKAN DATA DALAM FORMAT JSON (API)
        return response()->json([
            'asesor' => $asesor,
            'asesi' => $asesi,
            'data_asesmen' => $data_asesmen,
            // Kirimkan path TTD Asesi agar bisa digunakan oleh JavaScript (JS)
            'tanda_tangan' => $asesi->tanda_tangan,
        ]);
    }
}
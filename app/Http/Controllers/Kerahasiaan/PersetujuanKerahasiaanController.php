<?php

namespace App\Http\Controllers\Kerahasiaan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PersetujuanKerahasiaanController extends Controller
{
    /**
     * Method untuk menampilkan View FR.AK.01 (hanya kerangka HTML)
     */
    public function showFrAk01($id_asesi)
    {
        Log::info("WEB: Menampilkan halaman FR.AK.01 untuk Asesi ID $id_asesi");
        
        // Cuma kirim ID-nya aja ke view
        return view('persetujuan_assesmen_dan_kerahasiaan.fr_ak01', [
            'id_asesi_untuk_js' => $id_asesi 
        ]); 
    }
}
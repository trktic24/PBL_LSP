<?php

/**
 * File: app/Http/Controllers/FormulirPendaftaran/TandaTanganController.php
 * * Versi RAPI: Controller ini HANYA bertugas menampilkan halaman (view).
 * Semua logic pengambilan data & simpan data dipindah ke API Controller.
 */

namespace App\Http\Controllers\FormulirPendaftaran; // 1. Namespace udah bener

// 2. Import-nya jadi LEBIH SEDIKIT
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
// Kita HAPUS 'use Request', 'use File', 'use Validator', 'use Asesi'
// karena udah gak dipake di file ini.

class TandaTanganController extends Controller
{
    /**
     * Method untuk MENAMPILKAN halaman tanda tangan.
     * * Versi API: CUMA ngirim ID Asesi-nya aja ke view.
     * Nanti JavaScript di view yang akan "nembak" API pake ID ini
     * untuk ngambil data JSON-nya.
     */
    public function showSignaturePage()
    {
        // "Hack" untuk ngetes: kita tetap pake Asesi ID 1
        $id_asesi_hardcoded = 1; 

        Log::info("WEB: Menampilkan halaman tanda tangan untuk Asesi ID $id_asesi_hardcoded");
        
        // 3. CUMA KIRIM ID-NYA AJA, BUKAN SEMUA DATA ASESI
        return view('formulir_pendaftaran.tanda_tangan_pemohon', [
            'id_asesi_untuk_js' => $id_asesi_hardcoded
        ]);
    }

    // 4. SEMUA FUNGSI LAIN (store, storeAjax) DIHAPUS
    // ... (Fungsi-fungsi itu udah pindah ke controller API lu) ...

}
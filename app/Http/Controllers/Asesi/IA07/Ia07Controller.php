<?php

namespace App\Http\Controllers\Asesi\IA07;

use App\Http\Controllers\Controller;
use App\Models\DataSertifikasiAsesi;
use Illuminate\Http\Request;

class Ia07Controller extends Controller
{
    public function index($id_sertifikasi)
    {
        // KITA UPDATE BAGIAN WITH-NYA
        $sertifikasi = DataSertifikasiAsesi::with([
            'asesi',            
            'ia07', // Data jawaban (rekapan)
            'jadwal.asesor',
            'jadwal.jenisTuk',
            
            // NAH INI DIA KUNCINYA:
            // Kita ambil Skema -> Kelompok Pekerjaan -> Unit Kompetensi
            // Biar bisa diloop di Blade buat bikin header kayak gambar
            'jadwal.skema.kelompokPekerjaan.unitKompetensi' 
            
        ])->findOrFail($id_sertifikasi);

        $asesi = $sertifikasi->asesi;

        return view('IA_07.IA_07', compact('sertifikasi','asesi'));
    }
}
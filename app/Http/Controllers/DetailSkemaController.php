<?php

namespace App\Http\Controllers;

use App\Models\Skema;
use Illuminate\Http\Request;

class DetailSkemaController extends Controller
{
    /**
     * TAB 1: Informasi Detail Skema
     * View: master.skema.detail_skema
     */
    public function index($id_skema)
    {
        $skema = Skema::with(['category'])->findOrFail($id_skema);
        
        // Kita tidak butuh $formAsesmen di sini karena view-nya khusus info
        return view('master.skema.detail_skema', compact('skema'));
    }

    /**
     * TAB 2: Kelompok Pekerjaan & Unit Kompetensi
     * View: master.skema.detail_kelompokpekerjaan
     */
    public function kelompok($id_skema)
    {
        // Load skema beserta kelompok pekerjaan dan unit kompetensinya
        $skema = Skema::with(['kelompokPekerjaan.unitKompetensi'])->findOrFail($id_skema);
        
        // [PERBAIKAN] Arahkan ke view yang benar & hapus variable yang tidak ada
        return view('master.skema.detail_kelompokpekerjaan', compact('skema'));
    }

    /**
     * TAB 3: Bank Soal / Form Asesmen
     * View: master.skema.detail_banksoal
     */
    public function bankSoal($id_skema)
    {
        $skema = Skema::findOrFail($id_skema);
        
        // Data dummy Form Asesmen (Nanti diganti database real)
        $formAsesmen = [
            ['kode' => 'FR.APL.01', 'nama' => 'Permohonan Sertifikasi Kompetensi', 'warna' => 'bg-blue-600'],
            ['kode' => 'FR.APL.02', 'nama' => 'Asesmen Mandiri', 'warna' => 'bg-blue-600'],
            ['kode' => 'FR.MAPA.01', 'nama' => 'Merencanakan Aktivitas dan Proses Asesmen', 'warna' => 'bg-green-600'],
            ['kode' => 'FR.AK.01', 'nama' => 'Persetujuan Asesmen dan Kerahasiaan', 'warna' => 'bg-yellow-500'],
            ['kode' => 'FR.IA.01', 'nama' => 'Ceklis Observasi Aktivitas di Tempat Kerja', 'warna' => 'bg-purple-600'],
            // ...
        ];

        return view('master.skema.detail_banksoal', compact('skema', 'formAsesmen'));
    }
}
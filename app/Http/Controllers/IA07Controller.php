<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route; 
// Import semua Models yang relevan
use App\Models\Asesi;
use App\Models\Asesor;
use App\Models\Skema;
use App\Models\JenisTuk; 

class IA07Controller extends Controller
{
    /**
     * Menampilkan halaman Form FR.IA.07 berdasarkan route yang dipanggil (Asesor atau Asesi).
     */
    public function index()
    {
        // ----------------------------------------------------
        // 1. PENGAMBILAN DATA (MENGGUNAKAN MODEL NYATA)
        // ----------------------------------------------------
        
        $asesi = Asesi::first();
        $asesor = Asesor::first();
        
        $skema = null;
        if ($asesor && $asesor->skema()->exists()) {
            $skema = $asesor->skema()->first();
        } else {
             $skema = Skema::first(); 
        }

        // Ambil data Jenis TUK untuk radio button
        $jenisTukOptions = JenisTuk::pluck('jenis_tuk', 'id_jenis_tuk'); 

        // Data Dummy Unit Kompetensi
        $units = [
            ['code' => 'J.620100.004.02', 'title' => 'Menggunakan Struktur Data'],
            ['code' => 'J.620100.005.02', 'title' => 'Mengimplementasikan User Interface'],
            ['code' => 'J.620100.009.01', 'title' => 'Melakukan Instalasi Software Tools'],
            ['code' => 'J.620100.017.02', 'title' => 'Mengimplementasikan Pemrograman Terstruktur'],
        ];

        // --- Handle Data Kosong (Fallbacks) ---
        if (!$asesi) { $asesi = (object) ['nama_lengkap' => 'Nama Asesi (DB KOSONG)']; }
        if (!$asesor) { $asesor = (object) ['nama_lengkap' => 'Nama Asesor (DB KOSONG)', 'nomor_regis' => 'MET.000.000000.2019']; }
        if (!$skema) { $skema = (object) ['nama_skema' => 'SKEMA KOSONG', 'nomor_skema' => 'N/A']; }
        
        // ----------------------------------------------------
        // 2. LOGIKA PENENTUAN VIEW BERDASARKAN NAMA ROUTE
        // ----------------------------------------------------
        
        $currentRouteName = Route::currentRouteName();
        
        if ($currentRouteName === 'ia07.asesi') {
            // Jika diakses via /IA07_Asesi
            return view('frontend.IA_07.IA07_Asesi', compact('asesi', 'asesor', 'skema', 'units', 'jenisTukOptions'));
        } else {
            // Jika diakses via /IA07_Asesor atau lainnya
            return view('frontend.IA_07.IA07_Asesor', compact('asesi', 'asesor', 'skema', 'units', 'jenisTukOptions'));
        }
    }

    /**
     * Menyimpan data dari Form FR.IA.07.
     */
    public function store(Request $request)
    {
        // Logika penyimpanan data (assessment) akan ditaruh di sini
        return dd($request->all()); 
    }
}
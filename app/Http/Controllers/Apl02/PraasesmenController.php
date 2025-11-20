<?php

namespace App\Http\Controllers\Apl02;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller; 
use App\Models\UnitKompetensi; 
use App\Models\Elemen; 
use App\Models\KriteriaUnjukKerja; 
use Illuminate\Support\Facades\DB; // Jika Anda perlu debug query

class PraAsesmenController extends Controller
{
    /**
     * Menampilkan formulir pra-asesmen dengan data Unit, Elemen, dan KUK.
     */
    public function index()
    {
        // 1. PENGAMBILAN DATA UTAMA MENGGUNAKAN RELASI
        // Kunci: Eager Loading (::with) menggunakan nama fungsi relasi: 'elemens' dan 'kriteriaUnjukKerja'.
        $units = UnitKompetensi::with('elemens.kriteriaUnjukKerja')
                ->orderBy('kode_unit', 'asc') // Asumsi Anda punya kolom ini untuk pengurutan
                ->get();

        // 2. TRANSFORMASI DATA UNTUK BLADE
        // Kita ubah hasil Collection Eloquent menjadi array sederhana ($skemaList) 
        // yang disukai oleh Blade View.
        $skemaList = $units->mapWithKeys(function ($unit, $index) {
            
            // $unit: Data dari tabel master_unit_kompetensi
            
            // Transformasi Elemen
            $elementsData = $unit->elemens->map(function ($element, $el_index) {
                
                // $element: Data dari tabel master_elemen
                return [
                    // Kolom 'no_elemen' dari Factory Elemen: 1.1, 1.2, dst.
                    'no' => $element->no_elemen, 
                    
                    // NAMA ELEMEN diambil dari kolom 'elemen' di tabel master_elemen
                    'name' => $element->elemen, 
                    
                    // KUK diambil dari relasi HasMany $element->kriteriaUnjukKerja
                    'kuks' => $element->kriteriaUnjukKerja
                                ->sortBy('no_kriteria') // Urutkan KUK-nya
                                ->pluck('kriteria') // Ambil hanya teks KUK (kolom 'kriteria' di Factory KUK)
                                ->all(), 
                ];
            })->all();

            // Data per Unit Kompetensi
            return [
                $index + 1 => [ // Menggunakan nomor urut untuk key array
                    'kode' => $unit->kode_unit,         // Kolom dari UnitKompetensi
                    'judul' => $unit->judul_unit,       // Kolom dari UnitKompetensi
                    'unit' => $index + 1,
                    'elements' => $elementsData,
                ]
            ];
        })->all(); // Konversi Collection akhir menjadi array PHP

        // 3. Data Pelengkap (Asesor dan Skema Info)
        // Dalam kasus nyata, ini juga diambil dari database, tapi kita simulasikan dulu.
        $asesor = [
            'nama' => 'Jajang Sokbreker, S.T., M.T.', 
            'no_reg' => 'MET00XXXXX.XXXXX'          
        ];
        
        $skema_title = 'Junior Web Developer'; 
        $skema_kode = 'SKM12XXXXXX';           

        $idAsesi = 1; 
        // 4. Mengirimkan semua data ke Blade View
        return view('pra-assesmen.praasesmen', compact('skemaList', 'asesor', 'skema_title', 'skema_kode'));
    }
}
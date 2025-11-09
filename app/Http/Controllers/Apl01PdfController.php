<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Asesi;

class Apl01PdfController extends Controller
{
    /**
     * Method untuk DOWNLOAD PDF FR.APL.01
     * 
     * Data yang diambil:
     * 1. Data Asesi (pribadi) + tanda_tangan (path)
     * 2. Data Pekerjaan Asesi
     * 3. Data Sertifikasi Asesi (tujuan_asesmen)
     * 4. Skema Sertifikasi (via jadwal -> master_skema)
     * 5. Unit Kompetensi (dari master_skema)
     * 6. Bukti Kelengkapan (status + nama bukti)
     */
    public function download($id_asesi)
    {
        // LANGKAH 1: Ambil data asesi + semua relasi yang dibutuhkan
        $asesi = Asesi::with([
            'dataPekerjaan',                          // Relasi ke data_pekerjaan_asesi
            'dataSertifikasi',                        // Relasi ke data_sertifikasi_asesi
            // 'dataSertifikasi.jadwal',                 // Nested: ambil jadwal
            // 'dataSertifikasi.jadwal.skema',     // Nested: ambil master_skema
            'dataSertifikasi.buktiKelengkapan',       // Nested: ambil bukti_kelengkapan (DIUBAH dari dataPortofolio)
            'user'                                    // Relasi ke users
        // ])->findOrFail($id_asesi);
        
        // LANGKAH 2: Ambil data sertifikasi
        $dataSertifikasi = $asesi->dataSertifikasi;
        
        // LANGKAH 3: Ambil tujuan asesmen
        $tujuanAsesmen = $dataSertifikasi->tujuan_asesmen ?? null;
        
        // // LANGKAH 4: Ambil unit kompetensi dari master skema
        // $unitKompetensi = [];
        // if ($dataSertifikasi && $dataSertifikasi->jadwal && $dataSertifikasi->jadwal->masterSkema) {
        //     $unitKompetensi = $dataSertifikasi->jadwal->masterSkema->unitKompetensi ?? [];
        // }
        
        // LANGKAH 5: Ambil bukti kelengkapan
        $buktiKelengkapan = $dataSertifikasi ? $dataSertifikasi->buktiKelengkapan : collect([]);
        
        // LANGKAH 6: Ambil path tanda tangan dari tabel asesi
        $pathTandaTangan = $asesi->tanda_tangan ?? null;
        
        // LANGKAH 7: Konversi path ke full path untuk PDF
        $fullPathTandaTangan = null;
        if ($pathTandaTangan) {
            // Jika path sudah lengkap (http://...), pakai langsung
            if (filter_var($pathTandaTangan, FILTER_VALIDATE_URL)) {
                $fullPathTandaTangan = $pathTandaTangan;
            } else {
                // Jika path relatif, convert ke full path
                // Asumsi: gambar ada di public/storage/tanda_tangan/
                $fullPathTandaTangan = public_path('storage/' . $pathTandaTangan);
            }
        }
        
        // LANGKAH 8: Siapkan semua data untuk dikirim ke view
        $data = [
            'asesi' => $asesi,
            'dataPekerjaan' => $asesi->dataPekerjaan,
            'dataSertifikasi' => $dataSertifikasi,
            // 'tujuanAsesmen' => $tujuanAsesmen,
            // 'unitKompetensi' => $unitKompetensi,
            'buktiKelengkapan' => $buktiKelengkapan,
            'fullPathTandaTangan' => $fullPathTandaTangan,
        ];
        
        // LANGKAH 9: Load view blade dan convert ke PDF
        $pdf = Pdf::loadView('pdf.apl01', $data);
        
        // LANGKAH 10: Set ukuran kertas A4 portrait
        $pdf->setPaper('A4', 'portrait');
        
        // LANGKAH 11: Set options untuk DomPDF (support gambar)
        $pdf->setOptions([
            'isHtml5ParserEnabled' => true,
            'isRemoteEnabled' => true,
            'defaultFont' => 'sans-serif'
        ]);
        
        // LANGKAH 12: Generate nama file
        $filename = 'FR.APL.01_' . str_replace(' ', '_', $asesi->nama_lengkap) . '_' . date('YmdHis') . '.pdf';
        
        // LANGKAH 13: Download PDF
        return $pdf->download($filename);
    }
    
    /**
     * Method untuk PREVIEW PDF (tampilkan di browser tanpa download)
     */
    public function preview($id_asesi)
    {
        // Sama seperti download(), cuma pakai stream()
        
        $asesi = Asesi::with([
            'dataPekerjaan',
            'dataSertifikasi',
            'dataSertifikasi.jadwal',
            'dataSertifikasi.jadwal.masterSkema',
            'dataSertifikasi.buktiKelengkapan',
            'user'
        ])->findOrFail($id_asesi);
        
        $dataSertifikasi = $asesi->dataSertifikasi;
        $tujuanAsesmen = $dataSertifikasi->tujuan_asesmen ?? null;
        
        $unitKompetensi = [];
        if ($dataSertifikasi && $dataSertifikasi->jadwal && $dataSertifikasi->jadwal->masterSkema) {
            $unitKompetensi = $dataSertifikasi->jadwal->masterSkema->unitKompetensi ?? [];
        }
        
        $buktiKelengkapan = $dataSertifikasi ? $dataSertifikasi->buktiKelengkapan : collect([]);
        
        $pathTandaTangan = $asesi->tanda_tangan ?? null;
        $fullPathTandaTangan = null;
        if ($pathTandaTangan) {
            if (filter_var($pathTandaTangan, FILTER_VALIDATE_URL)) {
                $fullPathTandaTangan = $pathTandaTangan;
            } else {
                $fullPathTandaTangan = public_path('storage/' . $pathTandaTangan);
            }
        }
        
        $data = [
            'asesi' => $asesi,
            'dataPekerjaan' => $asesi->dataPekerjaan,
            'dataSertifikasi' => $dataSertifikasi,
            'tujuanAsesmen' => $tujuanAsesmen,
            'unitKompetensi' => $unitKompetensi,
            'buktiKelengkapan' => $buktiKelengkapan,
            'fullPathTandaTangan' => $fullPathTandaTangan,
        ];
        
        $pdf = Pdf::loadView('pdf.apl01', $data);
        $pdf->setPaper('A4', 'portrait');
        $pdf->setOptions([
            'isHtml5ParserEnabled' => true,
            'isRemoteEnabled' => true,
            'defaultFont' => 'sans-serif'
        ]);
        
        // stream() = tampilkan di browser (bisa preview dulu sebelum download)
        return $pdf->stream('FR.APL.01_Preview.pdf');
    }
}
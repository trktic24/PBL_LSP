<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Asesi;
use Illuminate\Support\Facades\Log; // [TAMBAHAN] Untuk mencatat error

class Apl01PdfController extends Controller
{
    /**
     * Method untuk DOWNLOAD PDF FR.APL.01
     */
    public function download($id_asesi)
    {
        // LANGKAH 1: Ambil data asesi + semua relasi yang dibutuhkan
        $asesi = Asesi::with([
            'dataPekerjaan',                  // Relasi ke data_pekerjaan_asesi
            'dataSertifikasi',                // Relasi ke data_sertifikasi_asesi
            'dataSertifikasi.jadwal',         // Nested: ambil jadwal
            'dataSertifikasi.jadwal.masterSkema', // Nested: ambil master_skema (disamakan dgn preview)
            'dataSertifikasi.buktiKelengkapan', // Nested: ambil bukti_kelengkapan
            'user'                            // Relasi ke users
        ])->findOrFail($id_asesi);
        
        // LANGKAH 2: Ambil data sertifikasi
        $dataSertifikasi = $asesi->dataSertifikasi;
        
        // LANGKAH 3: Ambil tujuan asesmen
        $tujuanAsesmen = $dataSertifikasi->tujuan_asesmen ?? null;
        
        // LANGKAH 4: Ambil unit kompetensi dari master skema
        $unitKompetensi = [];
        if ($dataSertifikasi && $dataSertifikasi->jadwal && $dataSertifikasi->jadwal->masterSkema) {
            $unitKompetensi = $dataSertifikasi->jadwal->masterSkema->unitKompetensi ?? [];
        }
        
        // LANGKAH 5: Ambil bukti kelengkapan
        $buktiKelengkapan = $dataSertifikasi ? $dataSertifikasi->buktiKelengkapan : collect([]);
        
        // LANGKAH 6: Ambil path tanda tangan dari tabel asesi
        $pathTandaTangan = $asesi->tanda_tangan ?? null;
        
        // LANGKAH 7A: [PERBAIKAN] Konversi TANDA TANGAN ke Base64 (Anti-Gagal)
        $fullPathTandaTangan = null;
        if ($pathTandaTangan) {
            try {
                $imagePath = storage_path('app/public/' . $pathTandaTangan); 
                if (file_exists($imagePath)) {
                    $imageData = file_get_contents($imagePath);
                    $imageType = pathinfo($imagePath, PATHINFO_EXTENSION);
                    $fullPathTandaTangan = 'data:image/' . $imageType . ';base64,' . base64_encode($imageData);
                }
            } catch (\Exception $e) {
                Log::error('Gagal memuat tanda tangan APL-01: ' . $e->getMessage());
            }
        }
        
        // LANGKAH 7B: [TAMBAHAN] Konversi LOGO ke Base64 (Anti-Gagal)
        $logoPath = public_path('images/Logo_LSP_No_BG.png'); // Path ke logo
        $logoLspBase64 = null;
        try {
            if (file_exists($logoPath)) {
                $imageData = file_get_contents($logoPath);
                $imageType = pathinfo($logoPath, PATHINFO_EXTENSION); 
                $logoLspBase64 = 'data:image/' . $imageType . ';base64,' . base64_encode($imageData);
            }
        } catch (\Exception $e) {
            Log::error('Gagal memuat logo LSP: ' . $e->getMessage());
        }
        
        // LANGKAH 8: Siapkan semua data untuk dikirim ke view
        $data = [
            'asesi' => $asesi,
            'dataPekerjaan' => $asesi->dataPekerjaan,
            'dataSertifikasi' => $dataSertifikasi,
            'tujuanAsesmen' => $tujuanAsesmen,
            'unitKompetensi' => $unitKompetensi,
            'buktiKelengkapan' => $buktiKelengkapan,
            'fullPathTandaTangan' => $fullPathTandaTangan, // <-- Mengirim Tanda Tangan
            'logoLspBase64' => $logoLspBase64          // <-- Mengirim Logo
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
        // --- Logika disamakan persis dengan download() ---

        // 1. Ambil Data
        $asesi = Asesi::with([
            'dataPekerjaan',
            'dataSertifikasi',
            'dataSertifikasi.jadwal',
            'dataSertifikasi.jadwal.masterSkema',
            'dataSertifikasi.buktiKelengkapan',
            'user'
        ])->findOrFail($id_asesi);
        
        // 2. Data Sertifikasi
        $dataSertifikasi = $asesi->dataSertifikasi;
        // 3. Tujuan Asesmen
        $tujuanAsesmen = $dataSertifikasi->tujuan_asesmen ?? null;
        
        // 4. Unit Kompetensi
        $unitKompetensi = [];
        if ($dataSertifikasi && $dataSertifikasi->jadwal && $dataSertifikasi->jadwal->masterSkema) {
            $unitKompetensi = $dataSertifikasi->jadwal->masterSkema->unitKompetensi ?? [];
        }
        
        // 5. Bukti
        $buktiKelengkapan = $dataSertifikasi ? $dataSertifikasi->buktiKelengkapan : collect([]);
        
        // 6. Path TTD
        $pathTandaTangan = $asesi->tanda_tangan ?? null;
        
        // 7A. Base64 TTD
        $fullPathTandaTangan = null;
        if ($pathTandaTangan) {
            try {
                $imagePath = public_path($pathTandaTangan); // <-- Ganti jadi ini
                if (file_exists($imagePath)) {
                    $imageData = file_get_contents($imagePath);
                    $imageType = pathinfo($imagePath, PATHINFO_EXTENSION);
                    $fullPathTandaTangan = 'data:image/' . $imageType . ';base64,' . base64_encode($imageData);
                }
            } catch (\Exception $e) {
                Log::error('Gagal memuat tanda tangan APL-01 (Preview): ' . $e->getMessage());
            }
        }

        // 7B. Base64 Logo
        $logoPath = public_path('images/Logo_LSP_No_BG.png');
        $logoLspBase64 = null;
        try {
            if (file_exists($logoPath)) {
                $imageData = file_get_contents($logoPath);
                $imageType = pathinfo($logoPath, PATHINFO_EXTENSION); 
                $logoLspBase64 = 'data:image/' . $imageType . ';base64,' . base64_encode($imageData);
            }
        } catch (\Exception $e) {
            Log::error('Gagal memuat logo LSP (Preview): ' . $e->getMessage());
        }
        
        // 8. Siapkan Data
        $data = [
            'asesi' => $asesi,
            'dataPekerjaan' => $asesi->dataPekerjaan,
            'dataSertifikasi' => $dataSertifikasi,
            'tujuanAsesmen' => $tujuanAsesmen,
            'unitKompetensi' => $unitKompetensi,
            'buktiKelengkapan' => $buktiKelengkapan,
            'fullPathTandaTangan' => $fullPathTandaTangan,
            'logoLspBase64' => $logoLspBase64
        ];
        
        // 9-11. Load PDF
        $pdf = Pdf::loadView('pdf.apl01', $data);
        $pdf->setPaper('A4', 'portrait');
        $pdf->setOptions([
            'isHtml5ParserEnabled' => true,
            'isRemoteEnabled' => true,
            'defaultFont' => 'sans-serif'
        ]);
        
        // 13. Stream (Preview)
        return $pdf->stream('FR.APL.01_Preview.pdf');
    }
}
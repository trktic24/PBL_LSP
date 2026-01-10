<?php

namespace App\Http\Controllers\Asesi\Pdf;

use App\Models\Admin;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\DataSertifikasiAsesi;
use Barryvdh\DomPDF\Facade\Pdf as PDF;

class Apl01PdfController extends Controller
{
    /**
     * Fungsi helper untuk normalisasi string agar bisa dicocokkan
     * Menghapus spasi, underscore, timestamp, dan mengubah ke lowercase
     */
    private function normalizeString($string)
    {
        // Ambil nama file tanpa path dan extension
        $filename = pathinfo($string, PATHINFO_FILENAME);
        
        // Hapus timestamp pattern (contoh: _1767249257_iJD)
        $filename = preg_replace('/_\d{10,}_[a-zA-Z0-9]+$/', '', $filename);
        
        // Hapus konten dalam tanda kurung beserta tanda kurungnya
        // Contoh: "Surat Keterangan Kerja 1 (Surat Keterangan Kerja)" → "Surat Keterangan Kerja 1"
        $filename = preg_replace('/\s*\([^)]*\)/', '', $filename);
        
        // Hapus angka yang diikuti spasi atau di akhir (seperti " 1", " 2")
        // Contoh: "Surat Keterangan Kerja 1" → "Surat Keterangan Kerja"
        $filename = preg_replace('/\s+\d+\s*$/', '', $filename);
        
        // Ubah ke lowercase
        $normalized = strtolower($filename);
        
        // Hapus karakter khusus (spasi, underscore, dash, slash, dll)
        $normalized = str_replace([' ', '_', '-', '/', '\\', '.', ',', '(', ')'], '', $normalized);
        
        return $normalized;
    }

    /**
     * Fungsi untuk mencocokkan label dengan file path
     * Menggunakan metode partial matching yang lebih fleksibel
     */
    private function matchLabelWithFile($label, $filePath)
    {
        $normalizedLabel = $this->normalizeString($label);
        $normalizedFile = $this->normalizeString($filePath);
        
        // Metode 1: Cek apakah label ada di dalam file (exact substring)
        $exactMatch = str_contains($normalizedFile, $normalizedLabel);
        
        // Metode 2: Cek apakah file ada di dalam label (untuk kasus label lebih panjang)
        $reverseMatch = str_contains($normalizedLabel, $normalizedFile);
        
        // Metode 3: Similarity check untuk kasus yang sangat mirip
        similar_text($normalizedLabel, $normalizedFile, $percent);
        $similarityMatch = $percent >= 70; // 70% similarity threshold
        
        $isMatch = $exactMatch || $reverseMatch || $similarityMatch;
        
        // Debug logging - hapus jika sudah tidak diperlukan
        \Log::info('Matching attempt:', [
            'label' => $label,
            'normalized_label' => $normalizedLabel,
            'file_path' => $filePath,
            'normalized_file' => $normalizedFile,
            'exact_match' => $exactMatch,
            'reverse_match' => $reverseMatch,
            'similarity' => round($percent, 2) . '%',
            'similarity_match' => $similarityMatch,
            'final_match' => $isMatch
        ]);
        
        return $isMatch;
    }

    public function generateApl01(Request $request, $id_data_sertifikasi)
    {
        $dataSertifikasi = DataSertifikasiAsesi::with([
            'asesi',
            'asesi.user',
            'asesi.dataPekerjaan',
            'jadwal.skema.unitKompetensi',
            'portofolio',
            'buktiDasar',
        ])->find($id_data_sertifikasi);

        if (!$dataSertifikasi) {
            abort(404, 'Data Permohonan Sertifikasi (APL 01) tidak ditemukan.');
        }

        $dataPekerjaan = $dataSertifikasi->asesi->dataPekerjaan()->orderBy('created_at', 'desc')->first();

        $skema = $dataSertifikasi->jadwal->skema;
        $skema->load('unitKompetensi');

        // Ambil admin yang login / admin pertama
        $admin = Admin::where('id_user', auth()->id())->first() ?? Admin::first();

        // --- LOGIKA BASE64 UNTUK GAMBAR LOGO ---
        $logoBnspBase64 = null;
        $logoLspBase64 = null;

        $logoBnspPath = public_path('images/logo_BNSP.png');
        if (file_exists($logoBnspPath)) {
            $logoBnspBase64 = base64_encode(file_get_contents($logoBnspPath));
        }

        $logoLspPath = public_path('images/logo_LSP_No_BG.png');
        if (file_exists($logoLspPath)) {
            $logoLspBase64 = base64_encode(file_get_contents($logoLspPath));
        }

        // --- LOGIKA TANDA TANGAN ADMIN (DIPERBAIKI) ---
        $ttdAdminBase64 = null;
        if ($admin && $admin->tanda_tangan_admin) {
            // Path yang benar sesuai dengan penyimpanan di ProfileController
            // File disimpan di storage/app/public/tanda_tangan_admin/
            $pathTtdAdmin = storage_path('app/public/' . $admin->tanda_tangan_admin);
            
            if (file_exists($pathTtdAdmin)) {
                $ttdAdminBase64 = base64_encode(file_get_contents($pathTtdAdmin));
            } else {
                // Log untuk debugging jika file tidak ditemukan
                \Log::warning('File TTD Admin tidak ditemukan', [
                    'expected_path' => $pathTtdAdmin,
                    'db_value' => $admin->tanda_tangan_admin
                ]);
            }
        }

        // --- LOGIKA TANDA TANGAN ASESI ---
        $ttdAsesiBase64 = null;
        if ($dataSertifikasi->asesi && $dataSertifikasi->asesi->tanda_tangan) {
            $pathTtdAsesi = storage_path('app/private_uploads/ttd_asesi/' . basename($dataSertifikasi->asesi->tanda_tangan));

            if (file_exists($pathTtdAsesi)) {
                $ttdAsesiBase64 = base64_encode(file_get_contents($pathTtdAsesi));
            }
        }

        // --- AMBIL SEMUA DATA BUKTI DASAR ---
        $allBuktiDasar = [];
        foreach ($dataSertifikasi->buktiDasar as $bukti) {
            $allBuktiDasar[] = [
                'file_path' => $bukti->bukti_dasar,
                'status_kelengkapan' => $bukti->status_kelengkapan,
                'keterangan' => $bukti->keterangan,
                'status_validasi' => $bukti->status_validasi,
            ];
        }

        // --- PROSES PERSYARATAN DASAR ---
        $persyaratanDasarData = [];
        foreach ($dataSertifikasi->portofolio as $portofolio) {
            if ($portofolio->persyaratan_dasar) {
                $label = $portofolio->persyaratan_dasar;
                
                // Cari file yang cocok dengan label ini
                $matchedBukti = null;
                foreach ($allBuktiDasar as $bukti) {
                    if ($this->matchLabelWithFile($label, $bukti['file_path'])) {
                        $matchedBukti = $bukti;
                        break;
                    }
                }

                $persyaratanDasarData[] = [
                    'label' => $label,
                    'status_kelengkapan' => $matchedBukti['status_kelengkapan'] ?? null,
                    'keterangan' => $matchedBukti['keterangan'] ?? null,
                ];
            }
        }

        // --- PROSES PERSYARATAN ADMINISTRATIF ---
        $persyaratanAdministratifData = [];
        foreach ($dataSertifikasi->portofolio as $portofolio) {
            if ($portofolio->persyaratan_administratif) {
                $label = $portofolio->persyaratan_administratif;
                
                // Cari file yang cocok dengan label ini
                $matchedBukti = null;
                foreach ($allBuktiDasar as $bukti) {
                    if ($this->matchLabelWithFile($label, $bukti['file_path'])) {
                        $matchedBukti = $bukti;
                        break;
                    }
                }

                $persyaratanAdministratifData[] = [
                    'label' => $label,
                    'status_kelengkapan' => $matchedBukti['status_kelengkapan'] ?? null,
                    'keterangan' => $matchedBukti['keterangan'] ?? null,
                ];
            }
        }

        $portofolio = $dataSertifikasi->portofolio->first();

        // Data untuk dikirim ke Blade PDF
        $data = [
            'sertifikasi' => $dataSertifikasi,
            'asesi' => $dataSertifikasi->asesi,
            'skema' => $skema,
            'pekerjaan' => $dataPekerjaan,
            'admin' => $admin,
            'ttdAdminBase64' => $ttdAdminBase64,
            'ttdAsesiBase64' => $ttdAsesiBase64,
            'logoBnspBase64' => $logoBnspBase64,
            'logoLspBase64' => $logoLspBase64,
            'portofolio' => $portofolio,
            'persyaratanDasarData' => $persyaratanDasarData,
            'persyaratanAdministratifData' => $persyaratanAdministratifData,
        ];

        // Kode pembuatan PDF
        $pdf = PDF::loadView('asesi.pdf.apl01', $data);

        $nama = $dataSertifikasi->asesi->nama_lengkap;
        $namaAsesi = preg_replace('/[^A-Za-z0-9 ]/', '', $nama);
        $namaAsesiClean = str_word_count($namaAsesi) > 1 ? $namaAsesi : str_replace(' ', '_', $namaAsesi);
        $namaFile = 'FR.APL.01_' . $namaAsesiClean . '_' . date('YmdHis') . '.pdf';

        if ($request->query('mode') == 'preview') {
            // Tampilkan di browser (View/Stream)
            return $pdf->stream($namaFile);
        }

        return $pdf->download($namaFile);
    }
}
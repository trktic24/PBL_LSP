<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DataSertifikasiAsesi;
use App\Models\Admin;
use PDF; // Pastikan alias PDF sudah terdaftar

class Apl01PdfController extends Controller
{
    public function generateApl01($id_data_sertifikasi)
    {
        $dataSertifikasi = DataSertifikasiAsesi::with(['asesi', 'asesi.user', 'asesi.dataPekerjaan', 'jadwal.skema.unitKompetensi', 'buktiDasar'])->find($id_data_sertifikasi);

        if (!$dataSertifikasi) {
            abort(404, 'Data Permohonan Sertifikasi (APL 01) tidak ditemukan.');
        }

        $dataPekerjaan = $dataSertifikasi->asesi->dataPekerjaan->first();

        $skema = $dataSertifikasi->jadwal->skema;
        $skema->load('unitKompetensi');

        // Ambil admin yang login / admin pertama
        $admin = Admin::where('id_user', auth()->id())->first() ?? Admin::first();

        // --- LOGIKA BASE64 UNTUK GAMBAR LOGO ---

        $logoBnspBase64 = null;
        $logoLspBase64 = null;

        // Path logo BNSP (pastikan path ini benar di folder public/images)
        $logoBnspPath = public_path('images/logo_BNSP.png');

        if (file_exists($logoBnspPath)) {
            $logoBnspBase64 = base64_encode(file_get_contents($logoBnspPath));
        }

        // Path logo LSP (pastikan path ini benar di folder public/images)
        $logoLspPath = public_path('images/logo_LSP_No_BG.png');

        if (file_exists($logoLspPath)) {
            $logoLspBase64 = base64_encode(file_get_contents($logoLspPath));
        }

        // --- LOGIKA TANDA TANGAN ADMIN ---

        // Convert tanda tangan admin ke base64 (logika yang sudah ada)
        $ttdBase64 = null;

        if ($admin && $admin->tanda_tangan_admin) {
            // Asumsi $admin->tanda_tangan_admin berisi path relatif seperti 'uploads/ttd/admin.png'
            $pathTtdAdmin = public_path($admin->tanda_tangan_admin);

            if (file_exists($pathTtdAdmin)) {
                $ttdBase64 = base64_encode(file_get_contents($pathTtdAdmin));
            }
        }

        // Data untuk dikirim ke Blade PDF
        $data = [
            'sertifikasi' => $dataSertifikasi,
            'asesi' => $dataSertifikasi->asesi,
            'skema' => $skema,
            'pekerjaan' => $dataPekerjaan,
            'admin' => $admin,
            'ttdBase64' => $ttdBase64,
            // Tambahkan variabel Base64 baru
            'logoBnspBase64' => $logoBnspBase64,
            'logoLspBase64' => $logoLspBase64,
        ];

        $pdf = PDF::loadView('pdf.apl01', $data);
        $nama = $dataSertifikasi->asesi->nama_lengkap;
        $namaAsesi = preg_replace('/[^A-Za-z0-9 ]/', '', $nama);
        $namaAsesiClean = str_word_count($namaAsesi) > 1 ? $namaAsesi : str_replace(' ', '_', $namaAsesi);
        $namaFile = 'FR.APL.01_' . $namaAsesiClean . '_' . date('YmdHis') . '.pdf';

        return $pdf->download($namaFile);
    }
}

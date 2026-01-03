<?php

namespace App\Http\Controllers\Asesi\Pdf;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\DataSertifikasiAsesi;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class Apl02PdfController extends Controller
{
    public function generateApl02($id_sertifikasi)
    {
        $user = Auth::user();

        // 1. Definisikan query dasar dulu (jangan langsung dieksekusi/findOrFail)
        $query = DataSertifikasiAsesi::with(['asesi', 'jadwal.skema.unitKompetensi.elemen.kriteria', 'jadwal.asesor', 'responApl2ia01']);

        // 2. LOGIC FILTERING:
        // Cek dulu, apakah user ini Asesi?
        // Kalau dia Asesi ($user->asesi ada datanya), kita WAJIB filter biar dia cuma bisa liat punya sendiri.
        if ($user->asesi) {
            $query->where('id_asesi', $user->asesi->id_asesi);
        }

        // Note: Kalau yang login Asesor (user->asesi isinya null), dia bakal lewatin 'if' di atas.
        // Jadi query-nya bebas nyari ID sertifikasi manapun (sesuai tugas Asesor).

        // 3. Baru deh dieksekusi query-nya
        $sertifikasi = $query->findOrFail($id_sertifikasi);

        // --- KE BAWAHNYA SAMA AJA ---

        $asesor = $sertifikasi->jadwal->asesor ?? null;

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

        // --- LOGIKA TANDA TANGAN ASESI ---
        $ttdAsesiBase64 = null;
        if ($sertifikasi->asesi && $sertifikasi->asesi->tanda_tangan) {
            // Hati-hati path ini, sesuaikan dengan struktur folder lu
            $pathTtdAsesi = storage_path('app/private_uploads/ttd_asesi/' . basename($sertifikasi->asesi->tanda_tangan));
            if (file_exists($pathTtdAsesi)) {
                $ttdAsesiBase64 = base64_encode(file_get_contents($pathTtdAsesi));
            }
        }

        // --- LOGIKA TANDA TANGAN ASESOR ---
        $ttdAsesorBase64 = null;
        if ($asesor && $asesor->tanda_tangan) {
            // Path: storage/app/private_uploads/asesor_docs/{id_user}/filename.png
            // Ambil id_user dari asesor
            $idUser = $asesor->user_id ?? ($asesor->id_user ?? null);

            if ($idUser) {
                $pathTtdAsesor = storage_path('app/private_uploads/asesor_docs/' . $idUser . '/' . basename($asesor->tanda_tangan));
            } else {
                // Fallback jika tidak ada id_user
                $pathTtdAsesor = storage_path('app/private_uploads/asesor_docs/' . basename($asesor->tanda_tangan));
            }

            if (file_exists($pathTtdAsesor)) {
                $ttdAsesorBase64 = base64_encode(file_get_contents($pathTtdAsesor));
            }
        }

        $data = [
            'sertifikasi' => $sertifikasi,
            'asesi' => $sertifikasi->asesi,
            'skema' => $sertifikasi->jadwal->skema,
            'asesor' => $asesor,
            'tanggal' => Carbon::parse($sertifikasi->updated_at)->isoFormat('dddd, DD MMM YYYY'),
            'logoBnspBase64' => $logoBnspBase64,
            'logoLspBase64' => $logoLspBase64,
            'ttdAsesiBase64' => $ttdAsesiBase64,
            'ttdAsesorBase64' => $ttdAsesorBase64,
        ];

        $pdf = Pdf::loadView('asesi.pdf.fr_apl_02', $data);
        $pdf->setPaper('a4', 'portrait');

        // Format nama file
        $nama = $sertifikasi->asesi->nama_lengkap;
        $namaAsesi = preg_replace('/[^A-Za-z0-9 ]/', '', $nama);
        $namaAsesiClean = str_word_count($namaAsesi) > 1 ? $namaAsesi : str_replace(' ', '_', $namaAsesi);
        $namaFile = 'FR.APL.02_' . $namaAsesiClean . '_' . date('YmdHis') . '.pdf';

        return $pdf->download($namaFile);
    }
}

<?php

namespace App\Http\Controllers\Asesi\Pdf;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\DataSertifikasiAsesi;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class Ak01PdfController extends Controller
{
    public function generateAk01($id_sertifikasi)
    {
        $user = Auth::user();

        // 1. Ambil Data Sertifikasi
        // Gunakan find (bukan findOrFail dulu) untuk handle error manual jika perlu
        $sertifikasi = DataSertifikasiAsesi::with([
            'asesi',
            'jadwal.skema',
            'jadwal.masterTuk',
            'jadwal.asesor'
        ])->find($id_sertifikasi);

        // Cek apakah data ada
        if (!$sertifikasi) {
            abort(404, 'Data sertifikasi tidak ditemukan.');
        }

        // 2. VALIDASI KEAMANAN (Authorization Check)
        // Jika yang login adalah Asesi, pastikan data ini miliknya
        if ($user->asesi) {
            if ($sertifikasi->id_asesi !== $user->asesi->id_asesi) {
                abort(403, 'Anda tidak memiliki hak akses ke dokumen ini.');
            }
        }
        // Jika User BUKAN Asesi (berarti Admin atau Asesor), loloskan saja.

        // 3. Siapkan Data untuk View
        $data = [
            'sertifikasi' => $sertifikasi,
            'asesi'       => $sertifikasi->asesi,
            'skema'       => $sertifikasi->jadwal->skema,
            'tuk'         => $sertifikasi->jadwal->masterTuk,
            'asesor'      => $sertifikasi->jadwal->asesor,
            'tanggal'     => Carbon::now()->isoFormat('D MMMM Y'),
            'hari'        => Carbon::now()->isoFormat('dddd'),
            'jam'         => Carbon::now()->format('H:i'),
        ];

        // 4. Load View PDF
        $pdf = Pdf::loadView('asesi.pdf.fr_ak_01', $data);
        $pdf->setPaper('a4', 'portrait');

        // Nama file yang rapi
        $namaAsesi = preg_replace('/[^A-Za-z0-9 ]/', '', $sertifikasi->asesi->nama_lengkap);
        $namaFile = 'FR.AK.01_Persetujuan_' . str_replace(' ', '_', $namaAsesi) . '.pdf';

        return $pdf->download($namaFile);
    }
}
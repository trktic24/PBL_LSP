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

        // 1. Ambil Data Sertifikasi beserta relasi yang dibutuhkan
        // Kita butuh data Asesi, Jadwal, Skema, TUK, dan Asesor
        $sertifikasi = DataSertifikasiAsesi::with([
            'asesi',
            'jadwal.skema',
            'jadwal.masterTuk',    // Pastikan relasi 'masterTuk' ada di model Jadwal
            'jadwal.asesor'  // Pastikan relasi 'asesor' ada di model Jadwal
        ])
            // ->where('id_asesi', $user->asesi->id_asesi) // Removed to allow Asesor access
            ->findOrFail($id_sertifikasi);

        // 2. Siapkan Data untuk View
        $data = [
            'sertifikasi' => $sertifikasi,
            'asesi' => $sertifikasi->asesi,
            'skema' => $sertifikasi->jadwal->skema,
            'tuk' => $sertifikasi->jadwal->masterTuk,
            'asesor' => $sertifikasi->jadwal->asesor,
            'tanggal' => Carbon::now()->isoFormat('D MMMM Y'),
            'hari' => Carbon::now()->isoFormat('dddd'),
            'jam' => Carbon::now()->format('H:i'),
        ];

        // 3. Load View PDF
        $pdf = Pdf::loadView('asesi.pdf.fr_ak_01', $data);
        $pdf->setPaper('a4', 'portrait');

        return $pdf->stream('FR.AK.01_Persetujuan_' . $sertifikasi->asesi->nama_lengkap . '.pdf');
    }
}
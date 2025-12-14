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
    // App/Http/Controllers/Asesi/Apl02/Apl02PdfController.php

public function generateApl02($id_sertifikasi)
{
    $user = Auth::user();

    $sertifikasi = DataSertifikasiAsesi::with([
        'asesi',
        'jadwal.skema',
        // PENTING: Muat relasi sampai ke Kriteria Unjuk Kerja (KUK)
        // Pastikan nama relasi (method) di model Anda sesuai (misal: 'elemen', 'kriteria')
        'jadwal.skema.unitKompetensi.elemen.kriteria' 
    ])
    ->where('id_asesi', $user->asesi->id_asesi)
    ->findOrFail($id_sertifikasi);

    $data = [
        'sertifikasi' => $sertifikasi,
        'asesi'       => $sertifikasi->asesi,
        'skema'       => $sertifikasi->jadwal->skema,
        'tanggal'     => Carbon::now()->isoFormat('D MMMM Y'),
    ];

    $pdf = Pdf::loadView('asesi.pdf.fr_apl_02', $data);
    $pdf->setPaper('a4', 'portrait');

    // return $pdf->stream('FR.APL.02_' . $sertifikasi->asesi->nama_lengkap . '.pdf');
    return $pdf->download('FR.APL.02_' . $sertifikasi->asesi->nama_lengkap . '.pdf');
}
}
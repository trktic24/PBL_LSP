<?php

namespace App\Http\Controllers\Asesi\Pdf;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\DataSertifikasiAsesi;
// Pastikan import model Admin jika Anda menggunakan logika cek admin manual
// use App\Models\Admin; 
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class Apl02PdfController extends Controller
{
    public function generateApl02($id_sertifikasi)
    {
        $user = Auth::user();

        // 1. Query Data (Tanpa where id_asesi dulu)
        $sertifikasi = DataSertifikasiAsesi::with([
            'asesi',
            'jadwal.skema',
            'jadwal.skema.unitKompetensi.elemen.kriteria' 
        ])->find($id_sertifikasi); // Gunakan find, bukan findOrFail agar kita bisa handle error sendiri

        // 2. Validasi Data Ditemukan
        if (!$sertifikasi) {
            abort(404, 'Data sertifikasi tidak ditemukan.');
        }

        // 3. Validasi Hak Akses (Security Check)
        // Cek apakah user adalah Asesi
        if ($user->asesi) {
            // Jika Asesi, pastikan dia pemilik data ini
            if ($sertifikasi->id_asesi !== $user->asesi->id_asesi) {
                abort(403, 'Anda tidak berhak mengakses dokumen ini.');
            }
        } 
        // Jika user BUKAN Asesi (berarti Admin/Assesor), loloskan saja (atau bisa tambah cek role)
        // else { 
        //    // Logic tambahan jika ingin memastikan dia benar-benar admin
        //    if (!$user->hasRole('admin')) abort(403); 
        // }

        // 4. Siapkan Data View
        $data = [
            'sertifikasi' => $sertifikasi,
            'asesi'       => $sertifikasi->asesi,
            'skema'       => $sertifikasi->jadwal->skema,
            'tanggal'     => Carbon::now()->isoFormat('D MMMM Y'),
        ];

        // 5. Generate PDF
        $pdf = Pdf::loadView('asesi.pdf.fr_apl_02', $data);
        $pdf->setPaper('a4', 'portrait');

        // Nama file yang rapi
        $namaAsesi = preg_replace('/[^A-Za-z0-9 ]/', '', $sertifikasi->asesi->nama_lengkap);
        $namaFile = 'FR.APL.02_' . str_replace(' ', '_', $namaAsesi) . '.pdf';

        return $pdf->download($namaFile);
    }
}
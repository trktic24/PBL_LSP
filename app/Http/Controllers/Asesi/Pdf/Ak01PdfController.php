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
    public function generateAk01(Request $request, $id_sertifikasi)
    {
        $user = Auth::user();

        // Ambil Data Sertifikasi beserta relasi yang dibutuhkan
        $sertifikasi = DataSertifikasiAsesi::with([
            'asesi',
            'jadwal.skema',
            'jadwal.masterTuk',
            'jadwal.jenisTuk',
            'jadwal.asesor',
            'responbuktiAk01.buktiMaster' // Relasi ke respon bukti AK01 dan master bukti
        ])
            ->findOrFail($id_sertifikasi);

        // Ambil data yang diperlukan
        $asesi = $sertifikasi->asesi;
        $jadwal = $sertifikasi->jadwal;
        $skema = $jadwal->skema;
        $tuk = $jadwal->masterTuk;
        $jenisTuk = $jadwal->jenisTuk;
        $asesor = $jadwal->asesor;

        // Ambil respon bukti AK01 yang sudah diceklis oleh asesor
        $responBukti = $sertifikasi->responbuktiAk01;
        
        // Ambil tanggal dari respon bukti AK01 (created_at)
        $tanggalRespon = $responBukti->first() 
            ? Carbon::parse($responBukti->first()->created_at)->isoFormat('dddd, DD MMM YYYY')
            : Carbon::now()->isoFormat('dddd, DD MMM YYYY');

        // Format tanggal pelaksanaan
        $tanggalPelaksanaan = $jadwal->tanggal_pelaksanaan 
            ? Carbon::parse($jadwal->tanggal_pelaksanaan)
            : Carbon::now();

        $hari = $tanggalPelaksanaan->isoFormat('dddd');
        $tanggal = $tanggalPelaksanaan->isoFormat('D MMMM Y');
        
        // Format waktu mulai
        $waktuMulai = $jadwal->waktu_mulai 
            ? Carbon::parse($jadwal->waktu_mulai)->format('H:i')
            : '00:00';

        // Lokasi TUK
        $lokasiTuk = ($tuk->nama_lokasi ?? '') . ($tuk->alamat_tuk ? ', ' . $tuk->alamat_tuk : '');

        // --- LOGIKA TANDA TANGAN ASESI ---
        $ttdAsesiBase64 = null;
        if ($asesi && $asesi->tanda_tangan) {
            $pathTtdAsesi = storage_path('app/private_uploads/ttd_asesi/' . basename($asesi->tanda_tangan));
            if (file_exists($pathTtdAsesi)) {
                $ttdAsesiBase64 = base64_encode(file_get_contents($pathTtdAsesi));
            }
        }

        // --- LOGIKA TANDA TANGAN ASESOR ---
        $ttdAsesorBase64 = null;
        if ($asesor && $asesor->tanda_tangan) {
            // Path: storage/app/private_uploads/asesor_docs/{id_user}/filename.png
            // Ambil id_user dari asesor
            $idUser = $asesor->user_id ?? $asesor->id_user ?? null;
            
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

        // Siapkan Data untuk View
        $data = [
            'sertifikasi' => $sertifikasi,
            'asesi' => $asesi,
            'skema' => $skema,
            'tuk' => $tuk,
            'jenisTuk' => $jenisTuk,
            'asesor' => $asesor,
            'responBukti' => $responBukti,
            'tanggal' => $tanggal,
            'hari' => $hari,
            'jam' => $waktuMulai,
            'lokasiTuk' => $lokasiTuk,
            'tanggalRespon' => $tanggalRespon,
            'ttdAsesiBase64' => $ttdAsesiBase64,
            'ttdAsesorBase64' => $ttdAsesorBase64,
        ];

        // Load View PDF
        $pdf = Pdf::loadView('asesi.pdf.fr_ak_01', $data);
        $pdf->setPaper('a4', 'portrait');
        if ($request->query('mode') == 'preview') {
            // Tampilkan di browser (Preview)
            return $pdf->stream('FR.AK.01_Persetujuan_' . $asesi->nama_lengkap . '.pdf');
        }
        // return $pdf->stream('FR.AK.01_Persetujuan_' . $asesi->nama_lengkap . '.pdf');
        return $pdf->download('FR.AK.01_Persetujuan_' . $asesi->nama_lengkap . '.pdf');
    }
}
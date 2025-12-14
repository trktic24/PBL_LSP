<?php

namespace App\Http\Controllers\Asesi\Pdf;

use App\Http\Controllers\Controller;
use App\Models\DataSertifikasiAsesi;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class KartuPesertaPdfController extends Controller
{
    /**
     * Generate Kartu Peserta PDF (Stream)
     */
    public function generateKartuPeserta($id_sertifikasi)
    {
        // try {
        // Ambil data dengan relasi lengkap
        $sertifikasi = DataSertifikasiAsesi::with([
            'asesi',
            'jadwal.skema',
            'jadwal.masterTuk',
            'jadwal.jenisTuk',
            'jadwal.asesor',
            'buktiDasar', // Tambahkan relasi bukti dasar untuk foto
        ])->findOrFail($id_sertifikasi);

        // Validasi data lengkap
        if (!$sertifikasi->asesi || !$sertifikasi->jadwal) {
            return back()->with('error', 'Data tidak lengkap untuk membuat kartu peserta.');
        }

        // Load logo sebagai base64
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

        // Load foto asesi dari BUKTI DASAR
        $fotoAsesiBase64 = null;

        // Cari bukti dasar yang keterangannya adalah "Foto Background Merah"
        $buktiFoto = $sertifikasi->buktiDasar->first(function ($bukti) {
            // Cek apakah keterangan mengandung kata kunci foto
            return stripos($bukti->keterangan, 'Foto') !== false && stripos($bukti->keterangan, 'Background') !== false;
        });

        if ($buktiFoto && $buktiFoto->bukti_dasar) {
            // Path dari DB: images/bukti_dasar/121/Foto_Background_Merah.jpg
            $pathFromDb = $buktiFoto->bukti_dasar;

            // Hapus leading slash jika ada
            $pathFromDb = ltrim($pathFromDb, '/');

            // Karena path sudah lengkap dari database: images/bukti_dasar/...
            // Langsung gunakan public_path()
            $fotoPath = public_path($pathFromDb);

            Log::info('Mencari foto dengan keterangan: ' . $buktiFoto->keterangan);
            Log::info('Path dari DB: ' . $pathFromDb);
            Log::info('Full path: ' . $fotoPath);

            if (file_exists($fotoPath)) {
                $fotoAsesiBase64 = base64_encode(file_get_contents($fotoPath));
                Log::info('✓ Foto berhasil dimuat!');
            } else {
                Log::warning('✗ File foto tidak ditemukan: ' . $fotoPath);
            }
        } else {
            Log::warning("Tidak ada bukti dasar dengan keterangan 'Foto Background Merah'");
        }

        // Jika tidak ada foto dari bukti dasar, cek foto_asesi di tabel asesi
        if (!$fotoAsesiBase64 && $sertifikasi->asesi->foto_asesi) {
            $fotoPath = public_path('storage/' . $sertifikasi->asesi->foto_asesi);
            if (file_exists($fotoPath)) {
                $fotoAsesiBase64 = base64_encode(file_get_contents($fotoPath));
            }
        }

        // Jika masih tidak ada, gunakan default
        if (!$fotoAsesiBase64) {
            $defaultFotoPath = public_path('images/default_photo.jpg');
            if (file_exists($defaultFotoPath)) {
                $fotoAsesiBase64 = base64_encode(file_get_contents($defaultFotoPath));
            }
        }

        $tanggalPelaksanaan = $sertifikasi->jadwal->tanggal_pelaksanaan ? \Carbon\Carbon::parse($sertifikasi->jadwal->tanggal_pelaksanaan)->locale('id')->isoFormat('D MMMM YYYY') : '-';

        $hari = $sertifikasi->jadwal->tanggal_pelaksanaan ? \Carbon\Carbon::parse($sertifikasi->jadwal->tanggal_pelaksanaan)->locale('id')->isoFormat('dddd') : '-';

        $waktuMulai = $sertifikasi->jadwal->waktu_mulai ? \Carbon\Carbon::parse($sertifikasi->jadwal->waktu_mulai)->format('H:i') : '-';
        $waktuSelesai = $sertifikasi->jadwal->waktu_selesai ? \Carbon\Carbon::parse($sertifikasi->jadwal->waktu_selesai)->format('H:i') : '-';
        $waktu = $waktuMulai . ' - ' . $waktuSelesai . ' WIB';

        // Format tanggal lahir
        $tanggalLahir = $sertifikasi->asesi->tanggal_lahir ? \Carbon\Carbon::parse($sertifikasi->asesi->tanggal_lahir)->locale('id')->isoFormat('D MMMM YYYY') : '-';

        // Data untuk view
        $data = [
            'logoBnsp' => $logoBnspBase64,
            'logoLsp' => $logoLspBase64,
            'fotoAsesi' => $fotoAsesiBase64,
            'namaAsesi' => $sertifikasi->asesi->nama_lengkap ?? '-',
            'tanggalLahir' => $tanggalLahir,
            'nik' => $sertifikasi->asesi->nik ?? '-',
            'hari' => $hari,
            'tanggal' => $tanggalPelaksanaan,
            'pukul' => $waktu,
            'jenisTuk' => $sertifikasi->jadwal->jenisTuk->jenis_tuk ?? '-',
            'lokasi' => $sertifikasi->jadwal->masterTuk->nama_lokasi ?? '-',
            'alamat' => $sertifikasi->jadwal->masterTuk->alamat_tuk ?? '-',
            'skema' => $sertifikasi->jadwal->skema->nama_skema ?? '-',
            'nomorSkema' => $sertifikasi->jadwal->skema->nomor_skema ?? '-',
            'namaAsesor' => $sertifikasi->jadwal->asesor->nama_lengkap ?? '-',
        ];

        // Generate PDF
        $pdf = Pdf::loadView('asesi.pdf.kartu_peserta', $data);
        $pdf->setPaper('A4', 'portrait');

        // Stream PDF (tampilkan di browser)
        return $pdf->download('Kartu_Peserta_' . $sertifikasi->asesi->nama_lengkap . '.pdf');

        // Uncomment untuk download langsung
        //

        // } catch (\Exception $e) {
        //     Log::error('Error Generate Kartu Peserta: ' . $e->getMessage());
        //     Log::error('Stack Trace: ' . $e->getTraceAsString());
        //     return back()->with('error', 'Gagal membuat kartu peserta: ' . $e->getMessage());
        // }
    }
}

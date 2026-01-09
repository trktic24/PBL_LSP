<?php

namespace App\Http\Controllers\Asesi\Pdf;

use App\Http\Controllers\Controller;
use App\Models\DataSertifikasiAsesi;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage; // <--- WAJIB ADA

class KartuPesertaPdfController extends Controller
{
    public function generateKartuPeserta($id_sertifikasi)
    {
        // 1. AMBIL DATA
        $sertifikasi = DataSertifikasiAsesi::with([
            'asesi',
            'jadwal.skema',
            'jadwal.masterTuk',
            'jadwal.jenisTuk',
            'jadwal.asesor',
            'buktiDasar', 
        ])->findOrFail($id_sertifikasi);

        // Validasi dasar
        if (!$sertifikasi->asesi || !$sertifikasi->jadwal) {
            return back()->with('error', 'Data tidak lengkap.');
        }

        // 2. SETUP LOGO (Aset Aplikasi = Public Path)
        $logoBnspBase64 = $this->getBase64Image(public_path('images/Logo_BNSP.png'));
        $logoLspBase64 = $this->getBase64Image(public_path('images/Logo_LSP_No_BG.png'));

        // 3. SETUP FOTO ASESI (STRICT PRIVATE STORAGE)
        $fotoAsesiBase64 = null;

        // Cari bukti yang mengandung kata "Foto" dan "Background" di keterangannya
        // Sesuai dengan controller upload lu: $finalKeterangan = $request->jenis_dokumen ...
        $buktiFoto = $sertifikasi->buktiDasar->first(function ($bukti) {
            return stripos($bukti->keterangan, 'Foto') !== false && stripos($bukti->keterangan, 'Background') !== false;
        });

        // LOGIC KHUSUS PRIVATE DOCS
        if ($buktiFoto && $buktiFoto->bukti_dasar) {
            $path = $buktiFoto->bukti_dasar; // Contoh: bukti_dasar/10/Foto_Background_Merah.jpg

            // Cek langsung ke Disk 'private_docs'
            if (Storage::disk('private_docs')->exists($path)) {
                // Ambil konten file
                $fileContent = Storage::disk('private_docs')->get($path);
                
                // Convert ke Base64 biar PDF bisa baca
                $fotoAsesiBase64 = base64_encode($fileContent);
                
                Log::info("PDF: Foto ditemukan di Private Storage ($path)");
            } else {
                Log::warning("PDF: Data ada di DB tapi fisik file tidak ada di private_docs ($path)");
            }
        }

        // 4. FALLBACK (Jaga-jaga kalau user belum upload foto merah)
        // Kita pakai default image aja, GAK USAH cek foto profil user di public lagi sesuai request
        if (!$fotoAsesiBase64) {
            $fotoAsesiBase64 = $this->getBase64Image(public_path('images/default_photo.jpg'));
        }

        // 5. FORMAT TANGGAL & WAKTU
        setlocale(LC_TIME, 'id_ID');
        \Carbon\Carbon::setLocale('id');

        $tglPelaksanaan = $sertifikasi->jadwal->tanggal_pelaksanaan 
            ? \Carbon\Carbon::parse($sertifikasi->jadwal->tanggal_pelaksanaan)->isoFormat('D MMMM YYYY') 
            : '-';
            
        $hari = $sertifikasi->jadwal->tanggal_pelaksanaan 
            ? \Carbon\Carbon::parse($sertifikasi->jadwal->tanggal_pelaksanaan)->isoFormat('dddd') 
            : '-';

        $waktuMulai = $sertifikasi->jadwal->waktu_mulai ? \Carbon\Carbon::parse($sertifikasi->jadwal->waktu_mulai)->format('H:i') : '-';
        $waktuSelesai = $sertifikasi->jadwal->waktu_selesai ? \Carbon\Carbon::parse($sertifikasi->jadwal->waktu_selesai)->format('H:i') : '-';
        $waktu = "$waktuMulai - $waktuSelesai WIB";

        $tglLahir = $sertifikasi->asesi->tanggal_lahir 
            ? \Carbon\Carbon::parse($sertifikasi->asesi->tanggal_lahir)->isoFormat('D MMMM YYYY') 
            : '-';

        // 6. RENDER PDF
        $data = [
            'logoBnsp' => $logoBnspBase64,
            'logoLsp' => $logoLspBase64,
            'fotoAsesi' => $fotoAsesiBase64, 
            'namaAsesi' => $sertifikasi->asesi->nama_lengkap ?? '-',
            'nik' => $sertifikasi->asesi->nik ?? '-',
            'tanggalLahir' => $tglLahir,
            'hari' => $hari,
            'tanggal' => $tglPelaksanaan,
            'pukul' => $waktu,
            'jenisTuk' => $sertifikasi->jadwal->jenisTuk->jenis_tuk ?? '-',
            'lokasi' => $sertifikasi->jadwal->masterTuk->nama_lokasi ?? '-',
            'alamat' => $sertifikasi->jadwal->masterTuk->alamat_tuk ?? '-',
            'skema' => $sertifikasi->jadwal->skema->nama_skema ?? '-',
            'nomorSkema' => $sertifikasi->jadwal->skema->nomor_skema ?? '-',
            'namaAsesor' => $sertifikasi->jadwal->asesor->nama_lengkap ?? '-',
        ];

        $pdf = Pdf::loadView('asesi.pdf.kartu_peserta', $data);
        $pdf->setPaper('A4', 'portrait');

        return $pdf->download('Kartu_Peserta.pdf');
    }

    // Helper kecil biar kodingan rapi
    private function getBase64Image($path)
    {
        if (file_exists($path)) {
            return base64_encode(file_get_contents($path));
        }
        return null;
    }
}
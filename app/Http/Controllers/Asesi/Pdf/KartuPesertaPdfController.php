<?php

namespace App\Http\Controllers\Asesi\Pdf;

use App\Http\Controllers\Controller;
use App\Models\DataSertifikasiAsesi;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth; // <--- TAMBAHKAN INI

class KartuPesertaPdfController extends Controller
{
    public function generateKartuPeserta(Request $request, $id_sertifikasi)
    {
        // 1. AMBIL DATA
        $sertifikasi = DataSertifikasiAsesi::with([
            'asesi',
            'jadwal.skema',
            'jadwal.masterTuk',
            'jadwal.jenisTuk',
            'jadwal.asesor',
            'buktiDasar', 
        ])->find($id_sertifikasi); // Gunakan find agar bisa handle error manual

        // Validasi Ketersediaan Data
        if (!$sertifikasi) {
            abort(404, 'Data sertifikasi tidak ditemukan.');
        }

        // Validasi Relasi Penting
        if (!$sertifikasi->asesi || !$sertifikasi->jadwal) {
            return back()->with('error', 'Data asesi atau jadwal tidak lengkap.');
        }

        // -----------------------------------------------------------
        // 2. VALIDASI HAK AKSES (SECURITY CHECK)
        // -----------------------------------------------------------
        $user = Auth::user();

        // Jika yang login adalah Asesi, pastikan data ini miliknya
        if ($user->asesi) {
            if ($sertifikasi->id_asesi !== $user->asesi->id_asesi) {
                abort(403, 'Anda tidak berhak mengakses Kartu Peserta ini.');
            }
        }
        // Jika Admin (user->asesi null), otomatis lolos (boleh akses punya siapa aja)

        // -----------------------------------------------------------
        // 3. SETUP LOGO & GAMBAR
        // -----------------------------------------------------------
        
        // Logo BNSP & LSP (Public Path)
        $logoBnspBase64 = $this->getBase64Image(public_path('images/Logo_BNSP.png'));
        $logoLspBase64 = $this->getBase64Image(public_path('images/Logo_LSP_No_BG.png'));

        // Foto Asesi (Private Storage)
        $fotoAsesiBase64 = null;

        // Cari bukti foto background merah/biru
        $buktiFoto = $sertifikasi->buktiDasar->first(function ($bukti) {
            return stripos($bukti->keterangan, 'Foto') !== false && stripos($bukti->keterangan, 'Background') !== false;
        });

        // Logic ambil file dari Private Storage
        if ($buktiFoto && $buktiFoto->bukti_dasar) {
            $path = $buktiFoto->bukti_dasar; 

            // Cek disk 'private_docs'
            if (Storage::disk('private_docs')->exists($path)) {
                $fileContent = Storage::disk('private_docs')->get($path);
                $fotoAsesiBase64 = base64_encode($fileContent);
            }
        }

        // Fallback jika foto tidak ada / belum upload
        if (!$fotoAsesiBase64) {
            $fotoAsesiBase64 = $this->getBase64Image(public_path('images/default_photo.jpg'));
        }

        // -----------------------------------------------------------
        // 4. FORMAT TANGGAL
        // -----------------------------------------------------------
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

        // -----------------------------------------------------------
        // 5. RENDER PDF
        // -----------------------------------------------------------
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

        // Ganti nama file biar rapi
        $namaFile = 'Kartu_Peserta_' . str_replace(' ', '_', $sertifikasi->asesi->nama_lengkap) . '.pdf';

        if ($request->query('mode') == 'preview') {
            // Tampilkan di browser (Preview)
            return $pdf->stream($namaFile);
        }

        // Ubah ke download() agar konsisten dengan controller lain
        return $pdf->download($namaFile);
    }

    private function getBase64Image($path)
    {
        if (file_exists($path)) {
            return base64_encode(file_get_contents($path));
        }
        return null;
    }
}
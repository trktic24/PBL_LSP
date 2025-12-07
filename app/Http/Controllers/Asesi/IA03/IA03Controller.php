<?php

namespace App\Http\Controllers\Asesi\IA03;

use App\Models\IA03;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\DataSertifikasiAsesi;

class IA03Controller extends Controller
{
    /**
     * INDEX → Menampilkan seluruh IA03 untuk 1 asesi (per jadwal)
     */
    public function index($id_data_sertifikasi_asesi)
    {
        // Ambil data sertifikasi asesi + relasi lengkap
        $sertifikasi = DataSertifikasiAsesi::with(['asesi', 'jadwal', 'jadwal.asesor', 'jadwal.skema', 'jadwal.jenisTuk', 'jadwal.tuk', 'jadwal.skema.kelompokPekerjaan', 'jadwal.skema.kelompokPekerjaan.unitKompetensi'])->findOrFail($id_data_sertifikasi_asesi);

        // Ambil seluruh pertanyaan IA03 milik asesi ini
        $pertanyaanIA03 = IA03::where('id_data_sertifikasi_asesi', $id_data_sertifikasi_asesi)->get();
        $catatanUmpanBalik = $pertanyaanIA03
            ->pluck('catatan_umpan_balik')
            ->filter()
            ->map(function ($catatan) {
                return trim($catatan);
            })
            ->filter();

        if (empty($catatanUmpanBalik)) {
            $catatanUmpanBalik = null;
        }

        // Data yang butuh ditampilkan di view
        $asesi = $sertifikasi->asesi;
        $asesor = $sertifikasi->asesor; // lewat accessor
        $skema = $sertifikasi->skema;
        $jenisTuk = $sertifikasi->jenis_tuk;
        $tuk = $sertifikasi->tuk;
        $tanggal = $sertifikasi->tanggal_pelaksanaan;

        // Unit & kelompok pekerjaan
        $kelompokPekerjaan = $skema?->kelompokPekerjaan ?? collect();
        $unitKompetensi = $kelompokPekerjaan->first()?->unitKompetensi ?? collect();

        $trackerUrl = $sertifikasi->jadwal?->id_jadwal ? '/tracker/' . $sertifikasi->jadwal->id_jadwal : '/dashboard';
        $backUrl = session('backUrl', $trackerUrl);
        
        return view('asesi.ia03.index', compact('sertifikasi', 'asesi', 'asesor', 'skema', 'jenisTuk', 'tuk', 'tanggal', 'kelompokPekerjaan', 'unitKompetensi', 'pertanyaanIA03', 'trackerUrl', 'backUrl', 'catatanUmpanBalik'));
    }

    /**
     * SHOW → Menampilkan detail 1 pertanyaan IA03
     */
    public function show($id)
    {
        $ia03 = IA03::with('dataSertifikasiAsesi')->findOrFail($id);

        return view('asesi/ia03.show', compact('ia03'));
    }
}

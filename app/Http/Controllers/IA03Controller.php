<?php

namespace App\Http\Controllers;

use App\Models\IA03;
use App\Models\DataSertifikasiAsesi;
use Illuminate\Http\Request;

class IA03Controller extends Controller
{
    /**
     * INDEX → Menampilkan seluruh IA03 untuk 1 asesi (per jadwal)
     */
    public function index($id_data_sertifikasi_asesi)
    {
        // Ambil data sertifikasi asesi + relasi lengkap
        $dataAsesi = DataSertifikasiAsesi::with(['asesi', 'jadwal', 'jadwal.asesor', 'jadwal.skema', 'jadwal.jenisTuk', 'jadwal.tuk', 'jadwal.skema.kelompokPekerjaan', 'jadwal.skema.kelompokPekerjaan.unitKompetensi'])->findOrFail($id_data_sertifikasi_asesi);

        // Ambil seluruh pertanyaan IA03 milik asesi ini
        $pertanyaanIA03 = IA03::where('id_data_sertifikasi_asesi', $id_data_sertifikasi_asesi)->get();

        // Data yang butuh ditampilkan di view
        $asesi = $dataAsesi->asesi;
        $asesor = $dataAsesi->asesor; // lewat accessor
        $skema = $dataAsesi->skema;
        $jenisTuk = $dataAsesi->jenis_tuk;
        $tuk = $dataAsesi->tuk;
        $tanggal = $dataAsesi->tanggal_pelaksanaan;

        // Unit & kelompok pekerjaan
        $kelompokPekerjaan = $skema?->kelompokPekerjaan ?? collect();
        $unitKompetensi = $kelompokPekerjaan->first()?->unitKompetensi ?? collect();

        $trackerUrl = $dataAsesi->jadwal?->id ? '/tracker/' . $dataAsesi->jadwal->id : '/dashboard';
        $backUrl = session('backUrl', $trackerUrl);

        return view('ia03.index', compact(
            'dataAsesi', 
            'asesi', 
            'asesor', 
            'skema', 
            'jenisTuk', 
            'tuk', 
            'tanggal', 
            'kelompokPekerjaan', 
            'unitKompetensi', 
            'pertanyaanIA03', 
            'trackerUrl', 
            'backUrl'));
    }

    /**
     * SHOW → Menampilkan detail 1 pertanyaan IA03
     */
    public function show($id)
    {
        $ia03 = IA03::with('dataSertifikasiAsesi')->findOrFail($id);

        return view('ia03.show', compact('ia03'));
    }
}

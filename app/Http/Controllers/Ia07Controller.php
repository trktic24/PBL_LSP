<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DataSertifikasiAsesi;
use App\Models\Ia07;
use App\Models\PertanyaanLisan; // Import Model yang benar
use App\Models\UnitKompetensi;
use Illuminate\Support\Facades\DB;

class Ia07Controller extends Controller
{
    public function index($id_sertifikasi)
    {
        // 1. Ambil Data Sertifikasi
        $sertifikasi = DataSertifikasiAsesi::with(['jadwal.skema', 'jadwal.asesor', 'asesi'])
                        ->findOrFail($id_sertifikasi);

        $asesi = $sertifikasi->asesi;
        $skema = $sertifikasi->jadwal->skema;
        $asesor = $sertifikasi->jadwal->asesor;

        // 2. Ambil Unit Kompetensi
        // PERBAIKAN DISINI: Menggunakan whereHas untuk menembus relasi
        // "Ambil Unit yang punya KelompokPekerjaan, dimana KelompokPekerjaan itu punya id_skema yg sesuai"
        
        $units = UnitKompetensi::with(['pertanyaanLisan' => function($query) {
                    $query->orderBy('id_pertanyaan_lisan', 'asc');
                }])
                ->whereHas('kelompokPekerjaan', function($query) use ($skema) {
                    $query->where('id_skema', $skema->id_skema);
                })
                ->get();

        // 3. Ambil jawaban user sebelumnya
        $jawabanUser = Ia07::where('id_data_sertifikasi_asesi', $id_sertifikasi)
                        ->pluck('jawaban_asesi', 'id_pertanyaan_lisan')
                        ->toArray();

        return view('IA_07.IA_07', compact(
            'sertifikasi', 
            'asesi', 
            'skema', 
            'asesor', 
            'units',
            'jawabanUser'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_data_sertifikasi_asesi' => 'required|exists:data_sertifikasi_asesi,id_data_sertifikasi_asesi',
            'jawaban'                   => 'required|array',
        ]);

        DB::transaction(function () use ($request) {
            
            // Loop setiap jawaban dari form
            // $idPertanyaan = id_pertanyaan_lisan
            // $teksJawaban = isi jawaban asesi
            foreach ($request->jawaban as $idPertanyaan => $teksJawaban) {
                
                // Cek jika jawaban ada isinya
                if (!empty($teksJawaban)) {
                    Ia07::updateOrCreate(
                        [
                            'id_data_sertifikasi_asesi' => $request->id_data_sertifikasi_asesi,
                            'id_pertanyaan_lisan'       => $idPertanyaan,
                        ],
                        [
                            'jawaban_asesi' => $teksJawaban
                            // Rekomendasi biarkan null (tugas asesor)
                        ]
                    );
                }
            }
        });

        return redirect()->route('dashboard')->with('success', 'Jawaban FR.IA.07 berhasil disimpan. Silakan lanjutkan ke tahap berikutnya.');
    }
}
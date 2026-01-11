<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Asesi;
use App\Models\Asesor;
use App\Models\Skema;
use App\Models\JenisTUK;
use App\Models\Jadwal;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\DataSertifikasiAsesi;
use App\Models\Ia07;
use Illuminate\Support\Facades\DB;

class IA07Controller extends Controller
{
    public function index($idSertifikasi)
    {
        $sertifikasi = DataSertifikasiAsesi::with([
            'asesi',
            'jadwal.asesor',
            'jadwal.skema.unitKompetensi',
            'jadwal.jenisTuk'
        ])->findOrFail($idSertifikasi);

        $asesi = $sertifikasi->asesi;
        $asesor = $sertifikasi->jadwal->asesor;
        $skema = $sertifikasi->jadwal->skema;
        $jadwal = $sertifikasi->jadwal;

        // --- LOGIKA BARU: Cek Data Existing & Read Only ---
        $existingData = Ia07::where('id_data_sertifikasi_asesi', $idSertifikasi)->get();
        $isReadOnly = $existingData->count() > 0;
        // --------------------------------------------------

        $jenisTukOptions = JenisTUK::pluck('jenis_tuk', 'id_jenis_tuk');

        $units = $skema->unitKompetensi->map(function ($unit) {
            return [
                'code' => $unit->kode_unit,
                'title' => $unit->judul_unit
            ];
        });

        // Fallbacks
        if (!$asesi) $asesi = (object) ['nama_lengkap' => 'Nama Asesi (DB KOSONG)'];
        if (!$asesor) $asesor = (object) ['nama_lengkap' => 'Nama Asesor (DB KOSONG)', 'nomor_regis' => '-'];
        if (!$skema) $skema = (object) ['nama_skema' => 'SKEMA KOSONG', 'nomor_skema' => 'N/A'];

        return view('frontend.FR_IA_07', compact(
            'asesi', 'asesor', 'skema', 'units', 'jenisTukOptions', 'jadwal', 
            'sertifikasi', 'existingData', 'isReadOnly' // Kirim variable baru ini
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_jenis_tuk' => 'required',
            'tanggal_pelaksanaan' => 'required|date',
            'id_data_sertifikasi_asesi' => 'required|exists:data_sertifikasi_asesi,id_data_sertifikasi_asesi',
        ]);

        $idSertifikasi = $request->input('id_data_sertifikasi_asesi');

        DB::beginTransaction();
        try {
            $allData = $request->all();

            foreach ($allData as $key => $value) {
                if (preg_match('/^jawaban_(.+)_q(\d+)$/', $key, $matches)) {
                    $unitCode = $matches[1];
                    $questionNum = $matches[2];

                    $pertanyaan = "Pertanyaan No $questionNum Unit $unitCode";
                    $keputusanKey = "keputusan_{$unitCode}_q{$questionNum}";

                    if (!isset($allData[$keputusanKey])) continue;

                    $keputusanVal = $allData[$keputusanKey];
                    $isKompeten = ($keputusanVal === 'K');

                    Ia07::updateOrCreate(
                        [
                            'id_data_sertifikasi_asesi' => $idSertifikasi,
                            'pertanyaan' => $pertanyaan,
                        ],
                        [
                            'jawaban_asesi' => $value,
                            'jawaban_diharapkan' => 'Lihat Kunci Jawaban',
                            'pencapaian' => $isKompeten ? 1 : 0,
                        ]
                    );
                }
            }

            DB::commit();

            // --- UBAH REDIRECT KE BACK AGAR POPUP MUNCUL ---
            return redirect()->back()->with('success', 'Penilaian FR.IA.07 berhasil disimpan.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal menyimpan penilaian: ' . $e->getMessage());
        }
    }

    // Method cetakPDF & adminShow biarkan tetap seperti semula...
    public function cetakPDF($idSertifikasi) { /* Gunakan kode lama Anda */ }
    public function adminShow($id_skema) { /* Gunakan kode lama Anda */ }
}
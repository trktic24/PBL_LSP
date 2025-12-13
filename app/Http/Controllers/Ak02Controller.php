<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Ak02;
use App\Models\DataSertifikasiAsesi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Ak02Controller extends Controller
{
    public function edit($id_asesi)
    {
        // PERBAIKAN UTAMA DI SINI:
        // Gunakan 'jadwal.skema...' bukan 'skema...'
        $asesi = DataSertifikasiAsesi::with([
            'jadwal.skema.kelompokPekerjaan.unitKompetensi',
            'user'
        ])->findOrFail($id_asesi);

        // Ambil data penilaian yang sudah ada
        $penilaianList = Ak02::where('id_data_sertifikasi_asesi', $id_asesi)
                             ->get()
                             ->keyBy('id_unit_kompetensi');

        return view('frontend.AK_02.FR_AK_02', compact('asesi', 'penilaianList'));
    }

    public function update(Request $request, $id_asesi)
    {
        $request->validate([
            'penilaian' => 'required|array',
            'global_kompeten' => 'nullable|in:Kompeten,Belum Kompeten',
        ]);

        // Ambil input global
        $globalKompeten = $request->input('global_kompeten');
        $globalTindakLanjut = $request->input('global_tindak_lanjut');
        $globalKomentar = $request->input('global_komentar');

        DB::beginTransaction();
        try {
            foreach ($request->penilaian as $idUnit => $data) {
                // Ambil checkbox (array)
                $bukti = isset($data['jenis_bukti']) ? $data['jenis_bukti'] : [];

                Ak02::updateOrCreate(
                    [
                        'id_unit_kompetensi' => $idUnit,
                        'id_data_sertifikasi_asesi' => $id_asesi,
                    ],
                    [
                        'jenis_bukti'   => $bukti,
                        // Simpan input global ke setiap baris unit
                        'kompeten'      => $globalKompeten,
                        'tindak_lanjut' => $globalTindakLanjut,
                        'komentar'      => $globalKomentar,
                    ]
                );
            }

            DB::commit();
            return redirect()->back()->with('success', 'Rekaman Asesmen FR.AK.02 berhasil disimpan.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
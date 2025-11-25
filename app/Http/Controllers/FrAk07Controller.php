<?php

namespace App\Http\Controllers;

use App\Models\DataSertifikasiAsesi;
use App\Models\HasilPenyesuaianAK07;
use App\Models\PersyaratanModifikasiAK07;
use App\Models\PoinPotensiAK07;
use App\Models\ResponDiperlukanPenyesuaianAK07;
use App\Models\ResponPotensiAK07;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FrAk07Controller extends Controller
{
    public function index($id_data_sertifikasi_asesi)
    {
        $dataSertifikasi = DataSertifikasiAsesi::with([
            'asesi',
            'jadwal.skema',
            'jadwal.asesor',
            'responPotensiAk07',
            'responPenyesuaianAk07',
            'hasilPenyesuaianAk07'
        ])->findOrFail($id_data_sertifikasi_asesi);

        $poinPotensi = PoinPotensiAK07::all();
        $persyaratanModifikasi = PersyaratanModifikasiAK07::with('catatanKeterangan')->get();

        return response()->json([
            'data_sertifikasi' => $dataSertifikasi,
            'poin_potensi' => $poinPotensi,
            'persyaratan_modifikasi' => $persyaratanModifikasi,
        ]);
    }

    public function create($id_data_sertifikasi_asesi)
    {
        $dataSertifikasi = DataSertifikasiAsesi::with([
            'asesi',
            'jadwal.skema',
            'jadwal.asesor',
            'responPotensiAk07',
            'responPenyesuaianAk07',
            'hasilPenyesuaianAk07'
        ])->findOrFail($id_data_sertifikasi_asesi);

        $poinPotensi = PoinPotensiAK07::all();
        $persyaratanModifikasi = PersyaratanModifikasiAK07::with('catatanKeterangan')->get();

        return view('frontend.FR_AK_07', [
            'sertifikasi' => $dataSertifikasi,
            'masterPotensi' => $poinPotensi,
            'masterPersyaratan' => $persyaratanModifikasi,
            'skema' => $dataSertifikasi->jadwal->skema ?? null,
            'asesi' => $dataSertifikasi->asesi ?? null,
            'asesor' => $dataSertifikasi->jadwal->asesor ?? null,
            'jadwal' => $dataSertifikasi->jadwal ?? null,
        ]);
    }

    public function store(Request $request, $id_data_sertifikasi_asesi)
    {
        $request->validate([
            'respon_potensi' => 'array',
            'respon_potensi.*.id_poin_potensi_AK07' => 'required|exists:poin_potensi_AK07,id_poin_potensi_AK07',
            'respon_potensi.*.respon_asesor' => 'nullable|string',

            'respon_penyesuaian' => 'array',
            'respon_penyesuaian.*.id_persyaratan_modifikasi_AK07' => 'required|exists:persyaratan_modifikasi_AK07,id_persyaratan_modifikasi_AK07',
            'respon_penyesuaian.*.id_catatan_keterangan_AK07' => 'nullable|exists:catatan_keterangan_AK07,id_catatan_keterangan_AK07',
            'respon_penyesuaian.*.respon_penyesuaian' => 'required|in:Ya,Tidak',
            'respon_penyesuaian.*.respon_catatan_keterangan' => 'nullable|string',

            'hasil_penyesuaian' => 'array',
            'hasil_penyesuaian.Acuan_Pembanding_Asesmen' => 'nullable|string',
            'hasil_penyesuaian.Metode_Asesmen' => 'nullable|string',
            'hasil_penyesuaian.Instrumen_Asesmen' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            // 1. Save Respon Potensi
            if ($request->has('respon_potensi')) {
                foreach ($request->respon_potensi as $potensi) {
                    ResponPotensiAK07::updateOrCreate(
                        [
                            'id_data_sertifikasi_asesi' => $id_data_sertifikasi_asesi,
                            'id_poin_potensi_AK07' => $potensi['id_poin_potensi_AK07']
                        ],
                        [
                            'respon_asesor' => $potensi['respon_asesor']
                        ]
                    );
                }
            }

            // 2. Save Respon Penyesuaian
            if ($request->has('respon_penyesuaian')) {
                foreach ($request->respon_penyesuaian as $penyesuaian) {
                    ResponDiperlukanPenyesuaianAK07::updateOrCreate(
                        [
                            'id_data_sertifikasi_asesi' => $id_data_sertifikasi_asesi,
                            'id_persyaratan_modifikasi_AK07' => $penyesuaian['id_persyaratan_modifikasi_AK07']
                        ],
                        [
                            'id_catatan_keterangan_AK07' => $penyesuaian['id_catatan_keterangan_AK07'] ?? null,
                            'respon_penyesuaian' => $penyesuaian['respon_penyesuaian'],
                            'respon_catatan_keterangan' => $penyesuaian['respon_catatan_keterangan'] ?? null
                        ]
                    );
                }
            }

            // 3. Save Hasil Penyesuaian
            if ($request->has('hasil_penyesuaian')) {
                HasilPenyesuaianAK07::updateOrCreate(
                    ['id_data_sertifikasi_asesi' => $id_data_sertifikasi_asesi],
                    [
                        'Acuan_Pembanding_Asesmen' => $request->hasil_penyesuaian['Acuan_Pembanding_Asesmen'] ?? '',
                        'Metode_Asesmen' => $request->hasil_penyesuaian['Metode_Asesmen'] ?? '',
                        'Instrumen_Asesmen' => $request->hasil_penyesuaian['Instrumen_Asesmen'] ?? ''
                    ]
                );
            }

            DB::commit();
            return response()->json(['message' => 'Data saved successfully']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Error saving data', 'error' => $e->getMessage()], 500);
        }
    }
}

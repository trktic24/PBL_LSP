<?php

namespace App\Http\Controllers;

use App\Models\UmpanBalik;
use Illuminate\Http\Request;
use App\Models\DataSertifikasiAsesi;

class UmpanBalikController extends Controller
{
    public function index()
    {
    $respon = UmpanBalik::with('dataSertifikasiAsesi')->first(); // dummy data
    $asesi  = DataSertifikasiAsesi::with('asesi')->orderBy('id_data_sertifikasi_asesi', 'desc')->first();

    return view('umpan_balik', ['respon' => $respon, 'asesi'  => $asesi]);

    }


    public function show($id)
    {
        $respon = UmpanBalik::with('dataSertifikasiAsesi')->findOrFail($id);

        return view('detail_umpan_balik', ['respon' => $respon]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_data_sertifikasi_asesi' => 'required|exists:data_sertifikasi_asesi,id_data_sertifikasi_asesi',
            'penjelasan_proses_asesmen' => 'required|boolean',
            'memahami_standar_kompetensi'   => 'required|boolean',
            'diskusi_metode_dengan_asesor'  => 'required|boolean',
            'menggali_bukti_pendukung'  => 'required|boolean',
            'kesempatan_demos_kompetensi'   => 'required|boolean',   
            'penjelasan_keputusan_asesmen'  => 'required|boolean',
            'umpan_balik_setelah_asesmen'   => 'required|boolean',
            'mempelajari_dokumen_asesmen'   => 'required|boolean',
            'jaminan_kerahasiaan'   => 'required|boolean',
            'komunikasi_efektif_asesor' => 'required|boolean',
            'catatan'            => 'nullable|string',
        ]);

        $item = UmpanBalik::create($validated);

        return redirect()->route('terimakasih', ['id' => $item->id_umpan_balik]);

    }

    public function update(Request $request, $id)
    {
        $item = UmpanBalik::findOrFail($id);

        $validated = $request->validate([
            'id_data_sertifikasi_asesi' => 'nullable|exists:data_sertifikasi_asesi,id_data_sertifikasi_asesi',
            'penjelasan_proses_asesmen' => 'required|boolean',
            'memahami_standar_kompetensi'   => 'required|boolean',
            'diskusi_metode_dengan_asesor'  => 'required|boolean',
            'menggali_bukti_pendukung'  => 'required|boolean',
            'kesempatan_demos_kompetensi'   => 'required|boolean',   
            'penjelasan_keputusan_asesmen'  => 'required|boolean',
            'umpan_balik_setelah_asesmen'   => 'required|boolean',
            'mempelajari_dokumen_asesmen'   => 'required|boolean',
            'jaminan_kerahasiaan'   => 'required|boolean',
            'komunikasi_efektif_asesor' => 'required|boolean',
            'catatan'            => 'nullable|string',
        ]);

        $item->update($validated);

        return response()->json([
            'message' => 'Umpan Balik berhasil diperbarui',
            'data' => $item
        ]);
    }

    public function destroy($id)
    {
        $item = UmpanBalik::findOrFail($id);
        $item->delete();

        return response()->json(['message' => 'Umpan Balik berhasil dihapus']);
    }
}
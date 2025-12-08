<?php

namespace App\Http\Controllers;

use App\Models\UmpanBalik;
use Illuminate\Http\Request;
use App\Models\DataSertifikasiAsesi;

class UmpanBalikController extends Controller
{
    public function index(Request $request)
    {
        $respon = UmpanBalik::with('dataSertifikasiAsesi')->first(); // dummy data
        $asesi  = DataSertifikasiAsesi::with('asesi')->orderBy('id_data_sertifikasi_asesi', 'desc')->first();

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'respon' => $respon,
                'asesi' => $asesi
            ]);
        }

        return view('umpan_balik', ['respon' => $respon, 'asesi'  => $asesi]);
    }

    public function show(Request $request, $id)
    {
        $respon = UmpanBalik::with('dataSertifikasiAsesi')->findOrFail($id);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'data' => $respon
            ]);
        }

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

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Umpan Balik berhasil disimpan',
                'data' => $item
            ]);
        }

        return redirect()->route('terimakasih_umpan_balik', ['id' => $item->id_umpan_balik]);
    }

    public function update(Request $request, $id)
    {
        $item = UmpanBalik::findOrFail($id);

        $validated = $request->validate([
            'id_data_sertifikasi_asesi' => 'nullable|exists:data_sertifikasi_asesi,id_data_sertifikasi_asesi',
            'penjelasan_proses_asesmen' => 'sometimes|boolean',
            'memahami_standar_kompetensi' => 'sometimes|boolean',
            'diskusi_metode_dengan_asesor' => 'sometimes|boolean',
            'menggali_bukti_pendukung' => 'sometimes|boolean',
            'kesempatan_demos_kompetensi' => 'sometimes|boolean',
            'penjelasan_keputusan_asesmen' => 'sometimes|boolean',
            'umpan_balik_setelah_asesmen' => 'sometimes|boolean',
            'mempelajari_dokumen_asesmen' => 'sometimes|boolean',
            'jaminan_kerahasiaan' => 'sometimes|boolean',
            'komunikasi_efektif_asesor' => 'sometimes|boolean',
            'catatan' => 'sometimes|string'
        ]);

        $item->update($validated);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Umpan Balik berhasil diperbarui',
                'data' => $item
            ]);
        }

        return redirect()->back()->with('success', 'Umpan Balik berhasil diperbarui');
    }

    public function destroy(Request $request, $id)
    {
        $item = UmpanBalik::findOrFail($id);
        $item->delete();

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Umpan Balik berhasil dihapus']);
        }

        return redirect()->back()->with('success', 'Umpan Balik berhasil dihapus');
    }
}
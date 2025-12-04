<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SoalIa06;
use App\Models\JawabanIa06;
use App\Models\UmpanBalikIa06;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class SoalIa06Controller extends Controller
{
    // =================================================================
    // 1. ADMIN (Role ID: 1): MENGELOLA BANK SOAL
    // =================================================================

    public function index()
    {
        // Semua role boleh melihat soal
        $data = SoalIa06::all();
        return response()->json(['success' => true, 'data' => $data], 200);
    }

    public function store(Request $request)
    {
        // PROTEKSI: Hanya Admin (1)
        if ($request->user()->role_id !== 1) {
            return response()->json(['message' => 'Akses Ditolak. Hanya Admin.'], 403);
        }

        $validator = Validator::make($request->all(), [
            'soal_ia06'          => 'required|string',
            'kunci_jawaban_ia06' => 'required|string',
        ]);

        if ($validator->fails()) return response()->json(['errors' => $validator->errors()], 422);

        $soal = SoalIa06::create($request->all());
        return response()->json(['success' => true, 'message' => 'Soal berhasil dibuat', 'data' => $soal], 201);
    }

    public function update(Request $request, $id)
    {
        // PROTEKSI: Hanya Admin (1)
        if ($request->user()->role_id !== 1) {
            return response()->json(['message' => 'Akses Ditolak. Hanya Admin.'], 403);
        }

        $soal = SoalIa06::find($id);
        if (!$soal) return response()->json(['message' => 'Soal tidak ditemukan'], 404);

        $soal->update($request->all());
        return response()->json(['success' => true, 'message' => 'Soal diupdate', 'data' => $soal], 200);
    }

    public function destroy(Request $request, $id)
    {
        // PROTEKSI: Hanya Admin (1)
        if ($request->user()->role_id !== 1) {
            return response()->json(['message' => 'Akses Ditolak. Hanya Admin.'], 403);
        }

        $soal = SoalIa06::find($id);
        if (!$soal) return response()->json(['message' => 'Soal tidak ditemukan'], 404);

        $soal->delete();
        return response()->json(['success' => true, 'message' => 'Soal dihapus'], 200);
    }

    // =================================================================
    // 2. ASESI (Role ID: 2): MENJAWAB SOAL
    // =================================================================

    public function storeJawabanAsesi(Request $request)
    {
        // PROTEKSI: Hanya Asesi (2)
        if ($request->user()->role_id !== 2) {
            return response()->json(['message' => 'Akses Ditolak. Hanya Asesi yang boleh menjawab.'], 403);
        }

        $validator = Validator::make($request->all(), [
            'id_data_sertifikasi_asesi' => 'required|exists:data_sertifikasi_asesi,id_data_sertifikasi_asesi',
            'jawabans'                  => 'required|array',
            'jawabans.*.id_soal_ia06'   => 'required|exists:soal_ia06,id_soal_ia06',
            'jawabans.*.jawaban_asesi'  => 'required|string',
        ]);

        if ($validator->fails()) return response()->json(['errors' => $validator->errors()], 422);

        try {
            DB::beginTransaction();
            $savedAnswers = [];

            foreach ($request->jawabans as $item) {
                $jawaban = JawabanIa06::updateOrCreate(
                    [
                        'id_data_sertifikasi_asesi' => $request->id_data_sertifikasi_asesi,
                        'id_soal_ia06'              => $item['id_soal_ia06']
                    ],
                    [
                        'jawaban_asesi' => $item['jawaban_asesi']
                        // Asesi dilarang mengisi 'pencapaian'
                    ]
                );
                $savedAnswers[] = $jawaban;
            }

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Jawaban tersimpan', 'data' => $savedAnswers], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    // =================================================================
    // 3. ASESOR (Role ID: 3): REVIEW & PENILAIAN
    // =================================================================

    public function storePenilaianAsesor(Request $request)
    {
        // PROTEKSI: Hanya Asesor (3)
        if ($request->user()->role_id !== 3) {
            return response()->json(['message' => 'Akses Ditolak. Hanya Asesor yang boleh menilai.'], 403);
        }

        $validator = Validator::make($request->all(), [
            'penilaian'                    => 'required|array',
            'penilaian.*.id_jawaban_ia06'  => 'required|exists:jawaban_ia06,id_jawaban_ia06',
            'penilaian.*.pencapaian'       => 'required|boolean', // 1 = Ya, 0 = Tidak
        ]);

        if ($validator->fails()) return response()->json(['errors' => $validator->errors()], 422);

        $count = 0;
        foreach ($request->penilaian as $item) {
            $jawaban = JawabanIa06::find($item['id_jawaban_ia06']);
            if ($jawaban) {
                $jawaban->update(['pencapaian' => $item['pencapaian']]);
                $count++;
            }
        }

        return response()->json(['success' => true, 'message' => "$count jawaban dinilai."], 200);
    }

    public function storeUmpanBalikAsesi(Request $request)
    {
        // PROTEKSI: Hanya Asesor (3)
        if ($request->user()->role_id !== 3) {
            return response()->json(['message' => 'Akses Ditolak. Hanya Asesor.'], 403);
        }

        $validator = Validator::make($request->all(), [
            'id_data_sertifikasi_asesi' => 'required|exists:data_sertifikasi_asesi,id_data_sertifikasi_asesi',
            'umpan_balik'               => 'required|string',
        ]);

        if ($validator->fails()) return response()->json(['errors' => $validator->errors()], 422);

        $feedback = UmpanBalikIa06::updateOrCreate(
            ['id_data_sertifikasi_asesi' => $request->id_data_sertifikasi_asesi],
            ['umpan_balik' => $request->umpan_balik]
        );

        return response()->json(['success' => true, 'message' => 'Umpan balik tersimpan', 'data' => $feedback], 200);
    }

    // =================================================================
    // UMUM: LIHAT DATA (Bisa Asesi/Asesor)
    // =================================================================

    public function getJawabanAsesi($id_asesi) {
        // Bisa diakses Asesi (2) atau Asesor (3)
        // (Opsional: Tambahkan pengecekan jika ingin strict)

        $data = JawabanIa06::with('soal')->where('id_data_sertifikasi_asesi', $id_asesi)->get();
        return response()->json(['success' => true, 'data' => $data], 200);
    }

    public function getUmpanBalikAsesi($id_asesi) {
        $data = UmpanBalikIa06::where('id_data_sertifikasi_asesi', $id_asesi)->first();
        return response()->json(['success' => true, 'data' => $data], 200);
    }

    public function show($id) {
         $soal = SoalIa06::find($id);
         if (!$soal) return response()->json(['message' => 'Soal tidak ditemukan'], 404);
         return response()->json(['success' => true, 'data' => $soal], 200);
    }
}
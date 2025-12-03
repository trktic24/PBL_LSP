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
    // 1. ADMIN/MASTER DATA: CRUD BANK SOAL
    // =================================================================

    // GET /api/v1/soal-ia06
    public function index()
    {
        $data = SoalIa06::all();
        return response()->json(['success' => true, 'data' => $data], 200);
    }

    // POST /api/v1/soal-ia06
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'soal_ia06'          => 'required|string',
            'kunci_jawaban_ia06' => 'nullable|string',
        ]);

        if ($validator->fails()) return response()->json(['errors' => $validator->errors()], 422);

        $soal = SoalIa06::create($request->all());
        return response()->json(['success' => true, 'message' => 'Soal dibuat', 'data' => $soal], 201);
    }

    // GET /api/v1/soal-ia06/{id}
    public function show($id)
    {
        $soal = SoalIa06::find($id);
        if (!$soal) return response()->json(['message' => 'Soal tidak ditemukan'], 404);
        return response()->json(['success' => true, 'data' => $soal], 200);
    }

    // PUT /api/v1/soal-ia06/{id}
    public function update(Request $request, $id)
    {
        $soal = SoalIa06::find($id);
        if (!$soal) return response()->json(['message' => 'Soal tidak ditemukan'], 404);

        $soal->update($request->all());
        return response()->json(['success' => true, 'message' => 'Soal diupdate', 'data' => $soal], 200);
    }

    // DELETE /api/v1/soal-ia06/{id}
    public function destroy($id)
    {
        $soal = SoalIa06::find($id);
        if (!$soal) return response()->json(['message' => 'Soal tidak ditemukan'], 404);

        $soal->delete();
        return response()->json(['success' => true, 'message' => 'Soal dihapus'], 200);
    }

    // =================================================================
    // 2. FITUR ASESI: MENJAWAB SOAL
    // =================================================================

    /**
     * POST /api/v1/soal-ia06/jawab
     * Asesi mengirim jawaban (bisa banyak sekaligus dalam array)
     */
    public function storeJawabanAsesi(Request $request)
    {
        // Validasi Array Jawaban
        $validator = Validator::make($request->all(), [
            'id_data_sertifikasi_asesi' => 'required|exists:data_sertifikasi_asesi,id_data_sertifikasi_asesi',
            'jawabans'                  => 'required|array', // Wajib array
            'jawabans.*.id_soal_ia06'   => 'required|exists:soal_ia06,id_soal_ia06',
            'jawabans.*.jawaban_asesi'  => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $savedAnswers = [];

        try {
            DB::beginTransaction(); // Pakai transaction biar aman

            foreach ($request->jawabans as $item) {
                // updateOrCreate: Kalau asesi sudah jawab soal ini, update. Kalau belum, create.
                $jawaban = JawabanIa06::updateOrCreate(
                    [
                        'id_data_sertifikasi_asesi' => $request->id_data_sertifikasi_asesi,
                        'id_soal_ia06'              => $item['id_soal_ia06']
                    ],
                    [
                        'jawaban_asesi' => $item['jawaban_asesi']
                        // Pencapaian tidak di-update di sini (tugas asesor)
                    ]
                );
                $savedAnswers[] = $jawaban;
            }

            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Jawaban berhasil disimpan',
                'data' => $savedAnswers
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Gagal menyimpan: ' . $e->getMessage()], 500);
        }
    }

    /**
     * GET /api/v1/soal-ia06/jawaban/{id_asesi}
     * Melihat jawaban asesi + soalnya (Dipakai Asesi & Asesor)
     */
    public function getJawabanAsesi($id_asesi)
    {
        // Mengambil jawaban dan join otomatis dengan tabel soal
        $data = JawabanIa06::with('soal') // Load relasi soal
                ->where('id_data_sertifikasi_asesi', $id_asesi)
                ->get();

        return response()->json(['success' => true, 'data' => $data], 200);
    }

    // =================================================================
    // 3. FITUR ASESOR: MENILAI & UMPAN BALIK
    // =================================================================

    /**
     * POST /api/v1/soal-ia06/penilaian
     * Asesor memberikan conteng kompeten (1) atau tidak (0) per soal
     */
    public function storePenilaianAsesor(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'penilaian'                    => 'required|array',
            'penilaian.*.id_jawaban_ia06'  => 'required|exists:jawaban_ia06,id_jawaban_ia06',
            'penilaian.*.pencapaian'       => 'required|boolean', // 1 or 0
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

        return response()->json(['success' => true, 'message' => "$count jawaban telah dinilai."], 200);
    }

    /**
     * POST /api/v1/soal-ia06/umpan-balik
     * Asesor memberikan komentar akhir
     */
    public function storeUmpanBalikAsesi(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_data_sertifikasi_asesi' => 'required|exists:data_sertifikasi_asesi,id_data_sertifikasi_asesi',
            'umpan_balik'               => 'required|string',
        ]);

        if ($validator->fails()) return response()->json(['errors' => $validator->errors()], 422);

        $feedback = UmpanBalikIa06::updateOrCreate(
            ['id_data_sertifikasi_asesi' => $request->id_data_sertifikasi_asesi],
            ['umpan_balik' => $request->umpan_balik]
        );

        return response()->json(['success' => true, 'message' => 'Umpan balik disimpan', 'data' => $feedback], 200);
    }

    // GET /api/v1/soal-ia06/umpan-balik/{id_asesi}
    public function getUmpanBalikAsesi($id_asesi)
    {
        $data = UmpanBalikIa06::where('id_data_sertifikasi_asesi', $id_asesi)->first();

        if(!$data) return response()->json(['message' => 'Belum ada umpan balik'], 404);

        return response()->json(['success' => true, 'data' => $data], 200);
    }
}
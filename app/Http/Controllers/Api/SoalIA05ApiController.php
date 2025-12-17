<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SoalIA05;
use App\Http\Resources\SoalIA05Resource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SoalIA05ApiController extends Controller
{
    // === ENDPOINT 1: GET SOAL (Untuk Form A) ===
    public function index()
    {
        $soal = SoalIA05::all();
        
        return response()->json([
            'status' => 'success',
            'message' => 'Data soal berhasil diambil',
            'data' => SoalIA05Resource::collection($soal)
        ], 200);
    }

    // === ENDPOINT 2: SUBMIT JAWABAN (Untuk Form C - Asesi) ===
    // Ini disesuaikan dengan `name="jawaban[...]"` di frontend kamu
    public function submitJawaban(Request $request)
    {
        // 1. Validasi Input
        // Frontend kamu mengirim array: name="jawaban[id_soal]"
        $validator = Validator::make($request->all(), [
            'jawaban' => 'required|array',
            'jawaban.*' => 'required|in:A,B,C,D', // Validasi harus A, B, C, atau D
            'id_asesi' => 'required|integer' // Butuh ID Asesi untuk simpan data
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // 2. Proses Simpan (Logika Form C)
        $dataJawaban = $request->input('jawaban');
        $idAsesi = $request->input('id_asesi');
        
        // Simpan ke database (Asumsi kamu punya model JawabanAsesi)
        // Contoh Logika Penyimpanan:
        /*
        foreach ($dataJawaban as $idSoal => $jawabanDipilih) {
            JawabanAsesi::updateOrCreate(
                ['asesi_id' => $idAsesi, 'soal_id' => $idSoal],
                ['jawaban_pilihan' => $jawabanDipilih]
            );
        }
        */

        return response()->json([
            'status' => 'success',
            'message' => 'Jawaban berhasil dikirim',
            'jumlah_terjawab' => count($dataJawaban)
        ], 200);
    }

    // === ENDPOINT 3: INPUT SOAL BARU (Untuk Admin) ===
    // Ini disesuaikan dengan `name="new_soal[...]"` di frontend kamu
    public function storeSoal(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'new_soal' => 'required|array',
            'new_soal.*.pertanyaan' => 'required|string',
            'new_soal.*.opsi_a' => 'required|string',
            'new_soal.*.opsi_b' => 'required|string',
            'new_soal.*.opsi_c' => 'required|string',
            'new_soal.*.opsi_d' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Loop array dari frontend dan simpan ke DB
        foreach ($request->new_soal as $item) {
            SoalIA05::create([
                'soal_ia05' => $item['pertanyaan'],
                'opsi_jawaban_a' => $item['opsi_a'],
                'opsi_jawaban_b' => $item['opsi_b'],
                'opsi_jawaban_c' => $item['opsi_c'],
                'opsi_jawaban_d' => $item['opsi_d'],
            ]);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Soal baru berhasil ditambahkan'
        ], 201);
    }
}
<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Schedule;
use Illuminate\Support\Facades\Validator;

class ScheduleController extends Controller
{
    /**
     * GET semua jadwal
     */
    public function index()
    {
        $jadwal = Schedule::with(['skema', 'tuk', 'asesor', 'jenisTuk'])->get();

        return response()->json([
            'status' => 'success',
            'data' => $jadwal
        ]);
    }

    /**
     * GET jadwal by ID
     */
    public function show($id)
    {
        $jadwal = Schedule::with(['skema', 'tuk', 'asesor', 'jenisTuk'])->find($id);

        if (!$jadwal) {
            return response()->json([
                'status' => 'error',
                'message' => 'Jadwal tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $jadwal
        ]);
    }

    /**
     * POST tambah jadwal
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_jenis_tuk' => 'required|exists:jenis_tuk,id_jenis_tuk',
            'id_tuk' => 'required|exists:master_tuk,id_tuk',
            'id_skema' => 'required|exists:skema,id_skema',
            'id_asesor' => 'required|exists:asesor,id_asesor',

            'kuota_maksimal' => 'required|integer|min:1',
            'kuota_minimal' => 'nullable|integer|min:1',
            'sesi' => 'required|integer|min:1',

            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',

            'tanggal_pelaksanaan' => 'required|date',
            'waktu_mulai' => 'required',

            'Status_jadwal' => 'required|in:Terjadwal,Selesai,Dibatalkan'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        $jadwal = Schedule::create($request->all());

        return response()->json([
            'status' => 'success',
            'message' => 'Jadwal berhasil ditambahkan',
            'data' => $jadwal
        ], 201);
    }

    /**
     * PUT update jadwal
     */
    public function update(Request $request, $id)
    {
        $jadwal = Schedule::find($id);

        if (!$jadwal) {
            return response()->json([
                'status' => 'error',
                'message' => 'Jadwal tidak ditemukan'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'id_jenis_tuk' => 'sometimes|exists:jenis_tuk,id_jenis_tuk',
            'id_tuk' => 'sometimes|exists:master_tuk,id_tuk',
            'id_skema' => 'sometimes|exists:skema,id_skema',
            'id_asesor' => 'sometimes|exists:asesor,id_asesor',

            'kuota_maksimal' => 'sometimes|integer|min:1',
            'kuota_minimal' => 'sometimes|integer|min:1',
            'sesi' => 'sometimes|integer|min:1',

            'tanggal_mulai' => 'sometimes|date',
            'tanggal_selesai' => 'sometimes|date',

            'tanggal_pelaksanaan' => 'sometimes|date',
            'waktu_mulai' => 'sometimes',

            'Status_jadwal' => 'sometimes|in:Terjadwal,Selesai,Dibatalkan'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        $jadwal->update($request->all());

        return response()->json([
            'status' => 'success',
            'message' => 'Jadwal berhasil diperbarui',
            'data' => $jadwal
        ]);
    }

    /**
     * DELETE hapus jadwal
     */
    public function destroy($id)
    {
        $jadwal = Schedule::find($id);

        if (!$jadwal) {
            return response()->json([
                'status' => 'error',
                'message' => 'Jadwal tidak ditemukan'
            ], 404);
        }

        $jadwal->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Jadwal berhasil dihapus'
        ]);
    }
}

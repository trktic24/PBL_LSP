<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Jadwal;
use Illuminate\Http\Request;

class JadwalController extends Controller
{
    // Ambil semua jadwal
    public function index()
    {
        $jadwals = Jadwal::with(['masterTuk', 'skema', 'asesor', 'jenisTuk'])->get();

        return response()->json([
            'success' => true,
            'data' => $jadwals
        ]);
    }

    // Detail satu jadwal
    public function show($id)
    {
        $jadwal = Jadwal::with(['masterTuk', 'skema', 'asesor', 'jenisTuk'])->find($id);

        if (!$jadwal) {
            return response()->json(['message' => 'Jadwal tidak ditemukan'], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $jadwal
        ]);
    }

    // Tambah jadwal
    public function store(Request $request)
    {
        $request->validate([
            'id_jenis_tuk' => 'required|exists:jenis_tuk,id_jenis_tuk',
            'id_tuk' => 'required|exists:master_tuk,id_tuk',
            'id_skema' => 'required|exists:skema,id_skema',
            'id_asesor' => 'required|exists:asesor,id_asesor',
            'sesi' => 'required|integer',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date',
            'tanggal_pelaksanaan' => 'required|date',
            'Status_jadwal' => 'required|in:Terjadwal,Selesai,Dibatalkan',
            'kuota_maksimal' => 'required|integer',
            'kuota_minimal' => 'nullable|integer'
        ]);

        $jadwal = Jadwal::create($request->all());

        return response()->json([
            'success' => true,
            'message' => "Jadwal berhasil ditambahkan",
            'data' => $jadwal
        ], 201);
    }

    // Update jadwal
    public function update(Request $request, $id)
    {
        $jadwal = Jadwal::find($id);

        if (!$jadwal) {
            return response()->json(['message' => 'Jadwal tidak ditemukan'], 404);
        }

        $jadwal->update($request->all());

        return response()->json([
            'success' => true,
            'message' => "Jadwal berhasil diperbarui",
            'data' => $jadwal
        ]);
    }

    // Hapus jadwal
    public function destroy($id)
    {
        $jadwal = Jadwal::find($id);

        if (!$jadwal) {
            return response()->json(['message' => 'Jadwal tidak ditemukan'], 404);
        }

        $jadwal->delete();

        return response()->json([
            'success' => true,
            'message' => "Jadwal berhasil dihapus"
        ]);
    }
}

<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Jadwal;
use Illuminate\Http\Request;

class JadwalControllerApi extends Controller
{
    public function index()
    {
    $data = Jadwal::with([
        'jenisTuk:id_jenis_tuk,jenis_tuk',
        'masterTuk:id_tuk,nama_lokasi,alamat_tuk,kontak_tuk',
        'skema:id_skema,nama_skema,kode_unit,harga',
        'asesor:id_asesor,nama_lengkap,nomor_regis',
        'asesi:id_asesi,nama_lengkap'
    ])->get(['id_jadwal','id_jenis_tuk','id_tuk','id_skema','id_asesor','kuota_maksimal','kuota_minimal','sesi','tanggal_mulai','tanggal_selesai','tanggal_pelaksanaan','waktu_mulai','Status_jadwal']);

    return response()->json([
        'success' => true,
        'message' => 'Data jadwal berhasil diambil',
        'data' => $data
    ]);
    }


    public function show($id)
    {
        $data = Jadwal::with([
            'jenisTuk',
            'masterTuk',
            'skema',
            'asesor',
            'asesi'
        ])->find($id);

        if (!$data) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_skema' => 'required|integer',
            'id_tuk' => 'required|integer',
            'id_jenis_tuk' => 'required|integer',
            'id_asesor' => 'required|integer',
            'tanggal' => 'required|date',
            'status_jadwal' => 'required|string',
        ]);

        $jadwal = Jadwal::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Jadwal berhasil dibuat',
            'data' => $jadwal
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $jadwal = Jadwal::find($id);

        if (!$jadwal) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan'
            ], 404);
        }

        $jadwal->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Jadwal berhasil diperbarui',
            'data' => $jadwal
        ]);
    }

    public function destroy($id)
    {
        $jadwal = Jadwal::find($id);

        if (!$jadwal) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan'
            ], 404);
        }

        $jadwal->delete();

        return response()->json([
            'success' => true,
            'message' => 'Jadwal berhasil dihapus'
        ]);
    }
}

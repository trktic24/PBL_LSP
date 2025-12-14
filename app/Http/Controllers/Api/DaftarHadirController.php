<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Jadwal;
use App\Models\Asesor;
use App\Models\DataSertifikasiAsesi;
use App\Models\DaftarHadirAsesi;
use Illuminate\Support\Facades\Auth;

class DaftarHadirController extends Controller
{
    public function showKehadiran(Request $request, $id_jadwal)
    {
        // 1. Ambil data Jadwal
        $jadwal = Jadwal::findOrFail($id_jadwal);

        // 2. Cek otorisasi
        $asesor = Asesor::where('id_user', Auth::id())->first();
        if (!$asesor || $jadwal->id_asesor != $asesor->id_asesor) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak berhak mengakses data ini.'
            ], 403);
        }

        // 3. Ambil daftar hadir
        $pendaftar = DataSertifikasiAsesi::with([
                'asesi.user',
                'asesi.dataPekerjaan',
                'presensi' => function($q) {
                    $q->select('id_data_sertifikasi_asesi', 'hadir', 'tanda_tangan_asesi');
                }
            ])
            ->where('id_jadwal', $id_jadwal)
            ->orderBy('id_data_sertifikasi_asesi', 'asc')
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Data kehadiran berhasil diambil.',
            'jadwal' => $jadwal,
            'pendaftar' => $pendaftar
        ]);
    }

    public function storeKehadiran(Request $request, $id_jadwal)
    {
        // Validasi input
        $request->validate([
            'data_presensi' => 'required|array',
            'data_presensi.*.id_data_sertifikasi_asesi' => 'required|integer',
            'data_presensi.*.hadir' => 'required|boolean',
        ]);

        // Cek jadwal
        $jadwal = Jadwal::find($id_jadwal);
        if (!$jadwal) {
            return response()->json([
                'status' => false,
                'message' => 'Jadwal tidak ditemukan.'
            ], 404);
        }

        // Cek asesor berdasarkan user login
        $asesor = Asesor::where('id_user', Auth::id())->first();
        if (!$asesor || $jadwal->id_asesor != $asesor->id_asesor) {
            return response()->json([
                'status' => false,
                'message' => 'Anda tidak memiliki akses untuk mengubah data ini.'
            ], 403);
        }

        $results = [];

        foreach ($request->data_presensi as $item) {
            // Cari data sertifikasi
            $dataSertifikasi = DataSertifikasiAsesi::with('asesi')
                ->find($item['id_data_sertifikasi_asesi']);

            if (!$dataSertifikasi) {
                $results[] = [
                    'id_data_sertifikasi_asesi' => $item['id_data_sertifikasi_asesi'],
                    'status' => 'Data sertifikasi tidak ditemukan'
                ];
                continue;
            }

            // Create or Update
            $presensi = DaftarHadirAsesi::updateOrCreate(
                [
                    'id_data_sertifikasi_asesi' => $item['id_data_sertifikasi_asesi']
                ],
                [
                    'hadir' => $item['hadir'] ? 1 : 0,
                    'tanda_tangan_asesi' => $dataSertifikasi->asesi->tanda_tangan ?? 'default.png'
                ]
            );

            $results[] = [
                'data_kehadiran' => $presensi,
                'status' => 'Berhasil disimpan'
            ];
        }

        return response()->json([
            'status' => true,
            'message' => 'Data kehadiran berhasil disimpan.',
            'results' => $results
        ]);
    }    
}

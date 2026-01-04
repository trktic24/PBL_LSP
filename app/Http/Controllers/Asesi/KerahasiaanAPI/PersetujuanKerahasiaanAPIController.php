<?php

namespace App\Http\Controllers\Asesi\KerahasiaanAPI;

use App\Models\BuktiAk01;
use Illuminate\Http\Request;
use App\Models\ResponBuktiAk01;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\DataSertifikasiAsesi;
use Illuminate\Support\Facades\Auth;

class PersetujuanKerahasiaanAPIController extends Controller
{
    // ... (Method show() buat view biarin aja) ...
    public function show($id_sertifikasi)
    {
        $sertifikasi = DataSertifikasiAsesi::findOrFail($id_sertifikasi);

        if (
            ($sertifikasi->status_sertifikasi == 'persetujuan_asesmen_disetujui' ||
                $sertifikasi->progres_level >= 70) &&
            !Auth::user()->hasRole('asesor')
        ) {

            return redirect()->route('asesi.persetujuan.selesai', ['id_sertifikasi' => $id_sertifikasi]);
        }
        try {
            $sertifikasi = DataSertifikasiAsesi::with('asesi')->findOrFail($id_sertifikasi);
            return view('asesi.persetujuan_assesmen_dan_kerahasiaan.fr_ak01', [
                'id_sertifikasi' => $id_sertifikasi,
                'asesi' => $sertifikasi->asesi,
                'sertifikasi' => $sertifikasi
            ]);
        } catch (\Exception $e) {
            return redirect('/tracker')->with('error', 'Data Pendaftaran tidak ditemukan.');
        }
    }

    /**
     * GET DATA: Tampilkan bukti yang "Wajib" (Dari database Respon/Factory)
     */
    public function getFrAk01Data($id_sertifikasi)
    {
        try {
            // 1. Ambil Data Sertifikasi
            $sertifikasi = DataSertifikasiAsesi::with([
                'asesi',
                'jadwal.asesor',
                'jadwal.jenisTuk'
            ])->findOrFail($id_sertifikasi);

            // 2. Ambil Master Bukti (Untuk list checkbox)
            $masterBukti = BuktiAk01::orderBy('id_bukti_ak01', 'asc')->get();

            // 3. Ambil "Settingan Bukti"
            // Kita anggap data yang sudah ada di tabel respon (hasil factory) adalah settingan admin.
            $responBukti = ResponBuktiAk01::where('id_data_sertifikasi_asesi', $id_sertifikasi)
                ->pluck('id_bukti_ak01')
                ->toArray();

            $jenisTuk = $sertifikasi->jadwal->jenisTuk->jenis_tuk ?? 'Sewaktu';

            return response()->json([
                'success' => true,
                'asesi' => $sertifikasi->asesi,
                'asesor' => $sertifikasi->jadwal->asesor ?? (object) ['nama_lengkap' => '-'],
                'tuk' => $jenisTuk,
                'master_bukti' => $masterBukti,
                'respon_bukti' => $responBukti,
                'status_sekarang' => $sertifikasi->status_sertifikasi,
                'tanda_tangan_valid' => !empty($sertifikasi->asesi->tanda_tangan)
            ], 200);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * POST: Cuma Update Status (Setuju)
     */
    public function simpanPersetujuan(Request $request, $id_sertifikasi)
    {
        try {
            $sertifikasi = DataSertifikasiAsesi::with('asesi')->findOrFail($id_sertifikasi);

            // Validasi Tanda Tangan
            if (empty($sertifikasi->asesi->tanda_tangan)) {
                return response()->json(['success' => false, 'message' => 'Tanda tangan belum ada.'], 422);
            }

            // Update Status
            $nextStatus = DataSertifikasiAsesi::STATUS_PERSETUJUAN_ASESMEN_OK;
            if ($sertifikasi->status_sertifikasi != $nextStatus) {
                $sertifikasi->status_sertifikasi = $nextStatus;
                $sertifikasi->save();
            }

            return response()->json([
                'success' => true,
                'message' => 'Persetujuan berhasil disimpan.',
                'id_jadwal' => $sertifikasi->id_jadwal
            ]);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function persetujuanSelesai($id_sertifikasi)
    {
        $user = Auth::user();

        // Ambil sertifikasi spesifik
        $sertifikasi = DataSertifikasiAsesi::with(['asesi', 'jadwal.skema'])
            ->where('id_asesi', $user->asesi->id_asesi)
            ->findOrFail($id_sertifikasi);

        // Pakai view universal yang tadi kita buat
        return view('asesi.tunggu_or_berhasil.berhasil', [
            'sertifikasi' => $sertifikasi,
            'asesi' => $sertifikasi->asesi
        ]);
    }
}

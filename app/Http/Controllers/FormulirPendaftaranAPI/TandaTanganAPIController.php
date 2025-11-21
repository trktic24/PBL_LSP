<?php

namespace App\Http\Controllers\FormulirPendaftaranAPI;

use App\Models\Asesi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use App\Http\Resources\AsesiResource;
use App\Http\Resources\AsesiTTDResource;
use Illuminate\Support\Facades\Validator;

class TandaTanganAPIController extends Controller
{
    /**
     * METHOD API 1: AMBIL DATA ASESI
     * (Ini udah bener, gak perlu diubah)
     */
    public function index()
    {
        $asesis = Asesi::all();
        return AsesiResource::collection($asesis);
    }

    public function show($id_asesi)
    {
        $data = Asesi::with('dataPekerjaan:id_pekerjaan,id_asesi,jabatan,nama_institusi_pekerjaan,alamat_institusi')->findOrFail($id_asesi);
        return new AsesiTTDResource($data);
    }
    public function storeAjax(Request $request, $id_asesi)
    {
        // 1. Validasi Input
        // 'data_tanda_tangan' kita buat 'nullable' (boleh kosong)
        $request->validate([
            'data_tanda_tangan' => 'nullable|string', 
            'id_data_sertifikasi_asesi' => 'required|integer'
        ]);

        try {
            $asesi = Asesi::findOrFail($id_asesi);

            // 2. Logika Simpan Gambar
            // Cek: Apakah ada data gambar baru dikirim?
            if ($request->filled('data_tanda_tangan')) {
                
                // --- KODE SIMPAN GAMBAR BARU (Tetap Sama) ---
                $image_parts = explode(";base64,", $request->data_tanda_tangan);
                $image_type_aux = explode("image/", $image_parts[0]);
                $image_type = $image_type_aux[1];
                $image_base64 = base64_decode($image_parts[1]);

                // Format nama: tanda_tangan_ID.png
                $fileName = 'tanda_tangan_' . $id_asesi . '.png'; 
                $folderPath = 'images/tanda_tangan/';
                $filePath = public_path($folderPath . $fileName);

                if (!File::exists(public_path($folderPath))) {
                    File::makeDirectory(public_path($folderPath), 0755, true);
                }
                
                // Hapus file lama (opsional, karena File::put akan menimpa)
                if ($asesi->tanda_tangan && File::exists(public_path($asesi->tanda_tangan))) {
                    File::delete(public_path($asesi->tanda_tangan));
                }

                File::put($filePath, $image_base64);
                
                $asesi->tanda_tangan = $folderPath . $fileName;
                $asesi->save();
                
            } else {
                // --- KODE PENGAMAN (Jika user kirim kosong) ---
                // Pastikan di database MEMANG SUDAH ADA tanda tangan
                if (empty($asesi->tanda_tangan)) {
                    return response()->json([
                        'success' => false, 
                        'message' => 'Tanda tangan belum ada. Silakan tanda tangan dulu!'
                    ], 422);
                }
                // Kalau sudah ada, lanjut aja (skip simpan gambar)
            }

            // ============================================================
            // 3. UPDATE STATUS SERTIFIKASI (Ini Tujuan Utamanya!)
            // ============================================================
            $sertifikasi = \App\Models\DataSertifikasiAsesi::find($request->id_data_sertifikasi_asesi);
            
            if ($sertifikasi) {
                // Update status hanya jika masih awal
                if ($sertifikasi->status_sertifikasi == \App\Models\DataSertifikasiAsesi::STATUS_SEDANG_MENDAFTAR || 
                    $sertifikasi->status_sertifikasi === null) {
                    
                    $sertifikasi->status_sertifikasi = \App\Models\DataSertifikasiAsesi::STATUS_PENDAFTARAN_SELESAI;
                    $sertifikasi->save();
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Data berhasil diperbarui!',
                'path' => $asesi->tanda_tangan
            ]);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}

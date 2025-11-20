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
        // Validasi input
        $request->validate([
            'data_tanda_tangan' => 'required|string',
            'id_data_sertifikasi_asesi' => 'required|integer' // Kita hapus exists dulu biar gak ribet validasinya
        ]);

        try {
            Log::info("API TTD: Mulai proses simpan untuk Asesi ID $id_asesi");

            // 1. Simpan Tanda Tangan ke Tabel Asesi (Logic Lama)
            $asesi = Asesi::findOrFail($id_asesi);
            
            $image_parts = explode(";base64,", $request->data_tanda_tangan);
            $image_type_aux = explode("image/", $image_parts[0]);
            $image_type = $image_type_aux[1];
            $image_base64 = base64_decode($image_parts[1]);

            $fileName = 'tanda_tangan_' . $id_asesi . '.png'; 
            $folderPath = 'images/tanda_tangan/';
            $filePath = public_path($folderPath . $fileName);

            if (!File::exists(public_path($folderPath))) {
                File::makeDirectory(public_path($folderPath), 0755, true);
            }
            
            // Hapus file lama jika ada
            if ($asesi->tanda_tangan && File::exists(public_path($asesi->tanda_tangan))) {
                File::delete(public_path($asesi->tanda_tangan));
            }

            File::put($filePath, $image_base64);
            
            // Update path di tabel asesi
            $asesi->tanda_tangan = $folderPath . $fileName;
            $asesi->save();

            Log::info("API TTD: Tanda tangan berhasil disimpan.");

            // ============================================================
            // [LOGIC UPDATE STATUS]
            // ============================================================
            $sertifikasi = \App\Models\DataSertifikasiAsesi::find($request->id_data_sertifikasi_asesi);
            
            if ($sertifikasi) {
                Log::info("API TTD: Data Sertifikasi ditemukan. Status saat ini: " . $sertifikasi->status_sertifikasi);

                // KITA PAKSA UPDATE jika statusnya 'sedang_mendaftar' ATAU 'null' (kosong)
                // Pastikan kamu sudah import model DataSertifikasiAsesi di atas
                if ($sertifikasi->status_sertifikasi == \App\Models\DataSertifikasiAsesi::STATUS_SEDANG_MENDAFTAR || 
                    $sertifikasi->status_sertifikasi === null) {
                    
                    $sertifikasi->status_sertifikasi = \App\Models\DataSertifikasiAsesi::STATUS_PENDAFTARAN_SELESAI;
                    $sertifikasi->save();
                    
                    Log::info("API TTD: Status BERHASIL diubah menjadi 'pendaftaran_selesai'");
                } else {
                    Log::info("API TTD: Status TIDAK diubah karena status saat ini bukan 'sedang_mendaftar' atau null.");
                }
            } else {
                Log::error("API TTD: Data Sertifikasi dengan ID {$request->id_data_sertifikasi_asesi} TIDAK DITEMUKAN.");
            }

            return response()->json([
                'success' => true,
                'message' => 'Tanda tangan disimpan & Status Diperbarui!',
                'path' => $asesi->tanda_tangan
            ]);

        } catch (\Exception $e) {
            Log::error("API TTD Error: " . $e->getMessage());
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}

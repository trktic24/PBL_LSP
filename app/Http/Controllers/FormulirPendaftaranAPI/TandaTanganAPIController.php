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
        $request->validate([
            'data_tanda_tangan' => 'required|string',
        ]);

        try {
            $asesi = Asesi::findOrFail($id_asesi);
            
            // 1. Proses Data Gambar Base64
            $image_parts = explode(";base64,", $request->data_tanda_tangan);
            $image_type_aux = explode("image/", $image_parts[0]);
            $image_type = $image_type_aux[1];
            $image_base64 = base64_decode($image_parts[1]);

            // 2. Tentukan Nama File & Path
            // Kita pakai uniqid() biar browser gak nge-cache gambar lama
            $fileName = 'ttd_' . $id_asesi . '_' . uniqid() . '.' . $image_type;
            $folderPath = 'images/tanda_tangan/';
            $filePath = public_path($folderPath . $fileName);

            // 3. Buat Folder kalau belum ada
            if (!File::exists(public_path($folderPath))) {
                File::makeDirectory(public_path($folderPath), 0755, true);
            }

            // ==========================================================
            // LOGIKA AUTO-REPLACE (HAPUS LAMA, SIMPAN BARU)
            // ==========================================================
            
            // Cek apakah di database sudah ada path tanda tangan lama
            if ($asesi->tanda_tangan) {
                $oldFile = public_path($asesi->tanda_tangan);
                // Cek apakah file fisiknya beneran ada, lalu hapus
                if (File::exists($oldFile)) {
                    File::delete($oldFile);
                }
            }

            // 4. Simpan File Baru
            File::put($filePath, $image_base64);

            // 5. Update Database
            // Kita simpan relative path: "images/tanda_tangan/namafile.png"
            $asesi->tanda_tangan = $folderPath . $fileName;
            $asesi->save();

            return response()->json([
                'success' => true,
                'message' => 'Tanda tangan berhasil diperbarui!',
                'path'    => $asesi->tanda_tangan // Kirim path baru ke frontend
            ]);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
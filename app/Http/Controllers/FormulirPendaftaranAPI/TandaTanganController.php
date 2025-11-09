<?php

namespace App\Http\Controllers\FormulirPendaftaranAPI;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use App\Models\Asesi;

class TandaTanganController extends Controller
{
    /**
     * METHOD API 1: AMBIL DATA ASESI
     * (Ini udah bener, gak perlu diubah)
     */
    public function getAsesiDataApi($id)
    {
        Log::info("API: Mencari data Asesi ID $id...");
        try {
            $asesi = Asesi::with('dataPekerjaan')->findOrFail($id);
            return response()->json($asesi);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['error' => 'Data Asesi tidak ditemukan'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Gagal mengambil data dari server'], 500);
        }
    }

    /**
     * METHOD API 2: SIMPAN TANDA TANGAN (AJAX)
     * (VERSI DINAMIS)
     */
    //           PERUBAHAN DI SINI vvvvvvvvvv
    public function storeAjax(Request $request, $id_asesi)
    {
        $validator = Validator::make($request->all(), [
            'data_tanda_tangan' => 'required|string',
        ]);
        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => 'Data tanda tangan tidak boleh kosong.'], 422);
        }

        $signatureData = $request->input('data_tanda_tangan');

        if (preg_match('/^data:image\/(\w+);base64,/', $signatureData, $type)) {
            Log::info("AJAX: Mendeteksi data Base64 baru untuk Asesi ID $id_asesi...");
            $extension = strtolower($type[1]);
            $base64Data = substr($signatureData, strpos($signatureData, ',') + 1);
            $decodedImage = base64_decode($base64Data);

            if ($decodedImage === false) {
                return response()->json(['success' => false, 'message' => 'Data Base64 tidak valid.'], 400);
            }

            // Hapus file lama (pake ID dinamis)
            $asesiLama = Asesi::find($id_asesi); // <-- PERUBAHAN
            if ($asesiLama && $asesiLama->tanda_tangan && File::exists(public_path($asesiLama->tanda_tangan))) {
                File::delete(public_path($asesiLama->tanda_tangan));
            }

            // Buat file baru (pake ID dinamis)
            // HAPUS: $asesiId = 1;
            $fileName = 'ttd_asesi_' . $id_asesi . '_' . time() . '.' . $extension; // <-- PERUBAHAN
            $directoryPath = public_path('images/tanda_tangan');
            $filePath = $directoryPath . '/' . $fileName;

            if (!File::isDirectory($directoryPath)) {
                File::makeDirectory($directoryPath, 0755, true, true);
            }

            try {
                File::put($filePath, $decodedImage);
            } catch (\Exception $e) {
                return response()->json(['success' => false, 'message' => 'Gagal menyimpan file di server (Izin Folder?).'], 500);
            }

            $dbPath = 'images/tanda_tangan/' . $fileName; 
        } else {
            return response()->json(['success' => false, 'message' => 'Format data tidak valid.'], 400);
        }

        try {
            $asesi = Asesi::find($id_asesi); // <-- PERUBAHAN
            if ($asesi) {
                $asesi->tanda_tangan = $dbPath; 
                $asesi->save();
            } else {
                return response()->json(['success' => false, 'message' => "Asesi ID $id_asesi tidak ditemukan."], 404);
            }
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal menyimpan ke database.'], 500);
        }

        return response()->json(['success' => true, 'message' => 'Tanda tangan berhasil disimpan!','path' => $dbPath]);
    }

    /**
     * METHOD API 3: HAPUS TANDA TANGAN (AJAX)
     * (VERSI DINAMIS)
     */
    public function deleteAjax($id_asesi)
    {
        // HAPUS: $asesiId = 1; 
        Log::info("AJAX: Mencoba menghapus TTD untuk Asesi ID $id_asesi"); // <-- PERUBAHAN

        try {
            $asesi = Asesi::find($id_asesi); // <-- PERUBAHAN

            if (!$asesi) {
                return response()->json(['success' => false, 'message' => "Asesi ID $id_asesi tidak ditemukan."], 404);
            }
            $path = $asesi->tanda_tangan;
            if ($path && File::exists(public_path($path))) {
                File::delete(public_path($path));
            }
            $asesi->tanda_tangan = null;
            $asesi->save();
            return response()->json(['success' => true, 'message' => 'Tanda tangan berhasil dihapus permanen!']);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal menghapus tanda tangan di server.'], 500);
        }
    }
}
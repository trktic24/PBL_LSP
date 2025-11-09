<?php

// 1. GANTI NAMESPACE-NYA
namespace App\Http\Controllers\FormulirPendaftaranAPI;

// 2. IMPORT SEMUA YANG DIBUTUHIN
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use App\Models\Asesi; // Pastiin ini bener

class TandaTanganController extends Controller
{
    /**
     * ==========================================================
     * METHOD API 1: AMBIL DATA ASESI
     * ==========================================================
     */
    public function getAsesiDataApi($id)
    {
        Log::info("API (Baru): Mencari data Asesi ID $id...");
        
        try {
            $asesi = Asesi::with('dataPekerjaan')->findOrFail($id);
            return response()->json($asesi);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::error("API (Baru): Asesi ID $id tidak ditemukan.");
            return response()->json(['error' => 'Data Asesi tidak ditemukan'], 404);
        } catch (\Exception $e) {
            Log::error('API (Baru): Gagal mengambil data - ' . $e->getMessage());
            return response()->json(['error' => 'Gagal mengambil data dari server'], 500);
        }
    }

    /**
     * ==========================================================
     * METHOD API 2: SIMPAN TANDA TANGAN (AJAX)
     * ==========================================================
     */
    public function storeAjax(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'data_tanda_tangan' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => 'Data tanda tangan tidak boleh kosong.'], 422);
        }

        $signatureData = $request->input('data_tanda_tangan');

        if (preg_match('/^data:image\/(\w+);base64,/', $signatureData, $type)) {
            
            Log::info('AJAX (Baru): Mendeteksi data Base64 baru...');
            $extension = strtolower($type[1]);
            $base64Data = substr($signatureData, strpos($signatureData, ',') + 1);
            $decodedImage = base64_decode($base64Data);

            if ($decodedImage === false) {
                return response()->json(['success' => false, 'message' => 'Data Base64 tidak valid.'], 400);
            }

            $asesiLama = Asesi::find(1); // (Asumsi masih pake ID 1)
            if ($asesiLama && $asesiLama->tanda_tangan && File::exists(public_path($asesiLama->tanda_tangan))) {
                File::delete(public_path($asesiLama->tanda_tangan));
            }

            $asesiId = 1; 
            $fileName = 'ttd_asesi_' . $asesiId . '_' . time() . '.' . $extension;
            $directoryPath = public_path('images/tanda_tangan');
            $filePath = $directoryPath . '/' . $fileName;

            if (!File::isDirectory($directoryPath)) {
                File::makeDirectory($directoryPath, 0755, true, true);
            }

            try {
                File::put($filePath, $decodedImage);
            } catch (\Exception $e) {
                Log::error('AJAX (Baru): Gagal simpan file: ' . $e->getMessage());
                return response()->json(['success' => false, 'message' => 'Gagal menyimpan file di server (Izin Folder?).'], 500);
            }

            $dbPath = 'images/tanda_tangan/' . $fileName; 
        } else {
            return response()->json(['success' => false, 'message' => 'Format data tidak valid.'], 400);
        }

        try {
            $asesi = Asesi::find(1); // (Asumsi masih pake ID 1)
            if ($asesi) {
                $asesi->tanda_tangan = $dbPath; 
                $asesi->save();
            } else {
                return response()->json(['success' => false, 'message' => 'Asesi ID 1 tidak ditemukan.'], 404);
            }
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal menyimpan ke database.'], 500);
        }

        return response()->json([
            'success' => true, 
            'message' => 'Tanda tangan berhasil disimpan!',
            'path' => $dbPath
        ]);
    }

    /**
     * ==========================================================
     * METHOD API 3: HAPUS TANDA TANGAN (AJAX)
     * ==========================================================
     * Ini dipanggil sama tombol "Hapus".
     */
    public function deleteAjax()
    {
        // Kita masih pake "hack" ID 1, sesuai setup lu
        $asesiId = 1; 
        Log::info("AJAX (Baru): Mencoba menghapus TTD untuk Asesi ID $asesiId");

        try {
            $asesi = Asesi::find($asesiId);

            if (!$asesi) {
                return response()->json(['success' => false, 'message' => 'Asesi ID 1 tidak ditemukan.'], 404);
            }

            $path = $asesi->tanda_tangan;

            // 1. Hapus file-nya dari folder public (kalo ada)
            if ($path && File::exists(public_path($path))) {
                Log::info("AJAX (Baru): Menghapus file lama: $path");
                File::delete(public_path($path));
            }

            // 2. Update database jadi NULL
            $asesi->tanda_tangan = null;
            $asesi->save();

            // 3. Kirim balasan sukses
            return response()->json(['success' => true, 'message' => 'Tanda tangan berhasil dihapus permanen!']);

        } catch (\Exception $e) {
            Log::error('AJAX (Baru): Gagal hapus TTD: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Gagal menghapus tanda tangan di server.'], 500);
        }
    }
}


<?php

namespace App\Http\Controllers\FormulirPendaftaranAPI;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use App\Models\DataSertifikasiAsesi;
use App\Models\Asesi;

class DataSertifikasiAsesiController extends Controller
{
    /**
     * ==========================================================
     * METHOD API 1: AMBIL DATA SERTIFIKASI ASESI
     * ==========================================================
     * Dipanggil oleh JavaScript untuk menampilkan data sertifikasi
     * dari asesi tertentu berdasarkan ID ASESI.
     */
    public function getDataSertifikasiAsesiApi($id)
    {
        Log::info("API (Baru): Mengambil data sertifikasi untuk Asesi ID $id...");

        try {
            // Ambil semua data sertifikasi milik Asesi tersebut
            $data = DataSertifikasiAsesi::where('id_asesi', $id)->get();

            if ($data->isEmpty()) {
                Log::warning("API (Baru): Tidak ada data sertifikasi untuk Asesi ID $id");
                return response()->json(['message' => 'Belum ada data sertifikasi.'], 200);
            }

            return response()->json($data, 200);

        } catch (\Exception $e) {
            Log::error("API (Baru): Gagal ambil data sertifikasi - " . $e->getMessage());
            return response()->json(['error' => 'Gagal mengambil data sertifikasi dari server'], 500);
        }
    }

    /**
     * ==========================================================
     * METHOD API 2: SIMPAN / UPDATE DATA SERTIFIKASI ASESI
     * ==========================================================
     * Dipanggil via AJAX dari form di Blade (metode POST atau PUT)
     */
    public function storeAjax(Request $request)
    {
        Log::info('API (Baru): Menerima permintaan simpan Data Sertifikasi Asesi...');

        $validator = Validator::make($request->all(), [
            'id_asesi' => 'required|integer',
            'nama_sertifikasi' => 'required|string|max:255',
            'nomor_sertifikat' => 'required|string|max:255',
            'tanggal_terbit' => 'required|date',
            'lembaga_penerbit' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            Log::warning('API (Baru): Validasi gagal saat simpan Data Sertifikasi.');
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            // Simpan data baru
            $sertifikasi = DataSertifikasiAsesi::create([
                'id_asesi' => $request->id_asesi,
                'nama_sertifikasi' => $request->nama_sertifikasi,
                'nomor_sertifikat' => $request->nomor_sertifikat,
                'tanggal_terbit' => $request->tanggal_terbit,
                'lembaga_penerbit' => $request->lembaga_penerbit,
            ]);

            Log::info("API (Baru): Data sertifikasi berhasil disimpan untuk Asesi ID {$request->id_asesi}");

            return response()->json([
                'success' => true,
                'message' => 'Data sertifikasi berhasil disimpan!',
                'data' => $sertifikasi
            ], 201);

        } catch (\Exception $e) {
            Log::error('API (Baru): Gagal simpan data sertifikasi - ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Gagal menyimpan data ke server.'], 500);
        }
    }

    /**
     * ==========================================================
     * METHOD API 3: HAPUS DATA SERTIFIKASI ASESI
     * ==========================================================
     * Dipanggil via tombol "Hapus" dari JavaScript (AJAX DELETE)
     */
    public function deleteAjax($id)
    {
        Log::info("API (Baru): Menerima permintaan hapus Data Sertifikasi ID $id...");

        try {
            $data = DataSertifikasiAsesi::find($id);

            if (!$data) {
                Log::warning("API (Baru): Data Sertifikasi ID $id tidak ditemukan.");
                return response()->json(['success' => false, 'message' => 'Data sertifikasi tidak ditemukan.'], 404);
            }

            $data->delete();

            Log::info("API (Baru): Data Sertifikasi ID $id berhasil dihapus.");
            return response()->json(['success' => true, 'message' => 'Data sertifikasi berhasil dihapus.'], 200);

        } catch (\Exception $e) {
            Log::error('API (Baru): Gagal hapus data sertifikasi - ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Gagal menghapus data sertifikasi.'], 500);
        }
    }
}

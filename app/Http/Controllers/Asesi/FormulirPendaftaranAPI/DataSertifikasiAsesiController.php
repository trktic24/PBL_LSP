<?php

namespace App\Http\Controllers\Asesi\FormulirPendaftaranAPI;

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
            Log::error('API (Baru): Gagal ambil data sertifikasi - ' . $e->getMessage());
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

        // Validasi harus cocok dengan nilai ENUM di migration
        $validator = Validator::make($request->all(), [
            'id_data_sertifikasi_asesi' => 'required|integer|exists:data_sertifikasi_asesi,id_data_sertifikasi_asesi',
            'tujuan_asesmen' => 'required|in:sertifikasi,PKT,rekognisi pembelajaran sebelumnya,lainnya',
        ]);

        if ($validator->fails()) {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'Validasi gagal',
                    'errors' => $validator->errors(),
                ],
                422,
            );
        }

        try {
            // Cari data berdasarkan ID (Update, bukan Create baru)
            $sertifikasi = DataSertifikasiAsesi::findOrFail($request->id_data_sertifikasi_asesi);

            // Update kolom tujuan_asesmen
            $sertifikasi->tujuan_asesmen = $request->tujuan_asesmen;
            $sertifikasi->save();

            return response()->json(
                [
                    'success' => true,
                    'message' => 'Tujuan asesmen berhasil disimpan!',
                    'data' => $sertifikasi,
                ],
                200,
            );
        } catch (\Exception $e) {
            Log::error('API (Baru): Gagal simpan data sertifikasi - ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Gagal menyimpan data ke server.'], 500);
        }
    }

    public function getDetailSertifikasiApi($id)
    {
        Log::info("API: Mengambil detail sertifikasi ID $id...");

        try {
            // [PERUBAHAN] Tambahkan 'jadwal.skema.unitKompetensi' di dalam with()
            $data = DataSertifikasiAsesi::with([
                'jadwal.skema.unitKompetensi' 
            ])->findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => $data
            ], 200);
            
        } catch (\Exception $e) {
            Log::error('API: Gagal ambil detail sertifikasi - ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Data tidak ditemukan'], 404);
        }
    }

    /**
     * ==========================================================
     * METHOD VIEW: TAMPILKAN HALAMAN FORMULIR (BLADE)
     * ==========================================================
     * Ini yang mindahin logic dari Route::get tadi.
     */
    public function showFormulir($id_sertifikasi)
    {
        try {
            // Kita cari data pendaftarannya (sertifikasi) beserta asesinya
            $sertifikasi = DataSertifikasiAsesi::with('asesi')->findOrFail($id_sertifikasi);

            // Return ke View (Blade), bukan JSON
            return view('asesi.formulir_pendaftaran.data_sertifikasi', [
                'id_sertifikasi_untuk_js' => $sertifikasi->id_data_sertifikasi_asesi,
                'asesi' => $sertifikasi->asesi,
                'sertifikasi' => $sertifikasi,
            ]);

        } catch (\Exception $e) {
            // Log errornya biar tau kenapa
            Log::error('Gagal memuat halaman formulir: ' . $e->getMessage());

            // Balikin ke tracker kalo data gak ketemu
            return redirect('/tracker')->with('error', 'Data Pendaftaran tidak ditemukan.');
        }
    }
}

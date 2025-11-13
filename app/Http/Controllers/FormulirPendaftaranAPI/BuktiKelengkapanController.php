<?php

namespace App\Http\Controllers\FormulirPendaftaranAPI;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use App\Models\BuktiKelengkapan;

class BuktiKelengkapanController extends Controller
{
    /**
     * ==========================================================
     * METHOD API 1: AMBIL DATA BUKTI KELENGKAPAN PER DATA SERTIFIKASI
     * ==========================================================
     */
    public function getDataBuktiKelengkapanApi($id_data_sertifikasi_asesi)
    {
        Log::info("API (Bukti): Mengambil data bukti kelengkapan untuk Data Sertifikasi Asesi ID {$id_data_sertifikasi_asesi}...");

        try {
            $data = BuktiKelengkapan::where('id_data_sertifikasi_asesi', $id_data_sertifikasi_asesi)->get();

            if ($data->isEmpty()) {
                Log::warning("API (Bukti): Belum ada bukti kelengkapan untuk ID Data Sertifikasi {$id_data_sertifikasi_asesi}");
                return response()->json(['message' => 'Belum ada data bukti kelengkapan.'], 200);
            }

            return response()->json($data, 200);
        } catch (\Exception $e) {
            Log::error("API (Bukti): Gagal mengambil data - " . $e->getMessage());
            return response()->json(['error' => 'Gagal mengambil data dari server.'], 500);
        }
    }

    /**
     * ==========================================================
     * METHOD API 2: SIMPAN / UPDATE DATA BUKTI KELENGKAPAN
     * ==========================================================
     */
    public function storeAjax(Request $request)
    {
        Log::info('API (Bukti): Menerima permintaan simpan/update Bukti Kelengkapan...');

        $validator = Validator::make($request->all(), [
            'id_data_sertifikasi_asesi' => 'required|integer',
            'jenis_dokumen' => 'required|string|max:255',
            'keterangan' => 'nullable|string|max:255',
            'bukti_kelengkapan' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'status_kelengkapan' => 'nullable|string|max:50',
        ]);

        if ($validator->fails()) {
            Log::warning('API (Bukti): Validasi gagal saat upload.');
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            // Cek apakah sudah ada dokumen dengan jenis yang sama
            $existing = BuktiKelengkapan::where('id_data_sertifikasi_asesi', $request->id_data_sertifikasi_asesi)
                ->where('jenis_dokumen', $request->jenis_dokumen)
                ->first();

            $path = $existing ? $existing->bukti_kelengkapan : null;

            // Jika ada file baru
            if ($request->hasFile('bukti_kelengkapan')) {
                $file = $request->file('bukti_kelengkapan');
                $filename = time() . '_' . $file->getClientOriginalName();
                $path = $file->storeAs('uploads/bukti_kelengkapan', $filename, 'public');

                // Hapus file lama
                if ($existing && $existing->bukti_kelengkapan && Storage::disk('public')->exists($existing->bukti_kelengkapan)) {
                    Storage::disk('public')->delete($existing->bukti_kelengkapan);
                }
            }

            if ($existing) {
                // Update data lama
                $existing->update([
                    'keterangan' => $request->keterangan ?? $existing->keterangan,
                    'bukti_kelengkapan' => $path,
                    'status_kelengkapan' => $request->status_kelengkapan ?? $existing->status_kelengkapan,
                ]);

                Log::info("API (Bukti): Update dokumen {$request->jenis_dokumen} untuk Data Sertifikasi Asesi ID {$request->id_data_sertifikasi_asesi}");

                return response()->json([
                    'success' => true,
                    'message' => 'Data bukti kelengkapan berhasil diperbarui.',
                    'data' => $existing
                ], 200);
            } else {
                // Simpan baru
                $newData = BuktiKelengkapan::create([
                    'id_data_sertifikasi_asesi' => $request->id_data_sertifikasi_asesi,
                    'jenis_dokumen' => $request->jenis_dokumen,
                    'keterangan' => $request->keterangan,
                    'bukti_kelengkapan' => $path,
                    'status_kelengkapan' => $request->status_kelengkapan ?? 'Belum Diverifikasi',
                ]);

                Log::info("API (Bukti): Simpan dokumen baru {$request->jenis_dokumen} untuk Data Sertifikasi Asesi ID {$request->id_data_sertifikasi_asesi}");

                return response()->json([
                    'success' => true,
                    'message' => 'Data bukti kelengkapan berhasil disimpan.',
                    'data' => $newData
                ], 201);
            }
        } catch (\Exception $e) {
            Log::error('API (Bukti): Gagal menyimpan data - ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan server.'], 500);
        }
    }

    /**
     * ==========================================================
     * METHOD API 3: HAPUS DATA BUKTI KELENGKAPAN
     * ==========================================================
     */
    public function deleteAjax($id)
    {
        Log::info("API (Bukti): Permintaan hapus Bukti Kelengkapan ID {$id}...");

        try {
            $data = BuktiKelengkapan::find($id);

            if (!$data) {
                return response()->json(['success' => false, 'message' => 'Data tidak ditemukan.'], 404);
            }

            if ($data->bukti_kelengkapan && Storage::disk('public')->exists($data->bukti_kelengkapan)) {
                Storage::disk('public')->delete($data->bukti_kelengkapan);
            }

            $data->delete();

            Log::info("API (Bukti): Data ID {$id} berhasil dihapus.");
            return response()->json(['success' => true, 'message' => 'Data berhasil dihapus.'], 200);
        } catch (\Exception $e) {
            Log::error('API (Bukti): Gagal hapus data - ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Gagal menghapus data.'], 500);
        }
    }
}

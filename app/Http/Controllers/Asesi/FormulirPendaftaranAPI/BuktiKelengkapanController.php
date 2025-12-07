<?php

namespace App\Http\Controllers\Asesi\FormulirPendaftaranAPI;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File; 
use App\Models\BuktiDasar; // Asumsi nama model kamu BuktiDasar atau BuktiDasar (sesuaikan)
use App\Models\BuktiKelengkapan; // Pakai ini jika modelnya BuktiKelengkapan
use App\Models\DataSertifikasiAsesi;

class BuktiKelengkapanController extends Controller
{
    // ... (Fungsi getDataBuktiKelengkapanApi TETAP SAMA, gak perlu diubah) ...
    public function getDataBuktiKelengkapanApi($id_data_sertifikasi_asesi)
    {
        Log::info("API (Bukti): Get data ID {$id_data_sertifikasi_asesi}...");
        try {
            // Pastikan pakai Model yang benar (BuktiKelengkapan atau BuktiDasar)
            $data = BuktiDasar::where('id_data_sertifikasi_asesi', $id_data_sertifikasi_asesi)->get();
            return response()->json(['success' => true, 'data' => $data], 200);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal server.'], 500);
        }
    }

    public function storeAjax(Request $request)
    {
        // 1. Validasi
        $validator = Validator::make($request->all(), [
            'id_data_sertifikasi_asesi' => 'required|integer',
            'jenis_dokumen' => 'required|string|max:255',
            'keterangan' => 'nullable|string|max:255',
            'file' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => 'Validasi gagal', 'errors' => $validator->errors()], 422);
        }

        try {
            // 2. Cari ID Asesi untuk nama folder
            $sertifikasi = DataSertifikasiAsesi::find($request->id_data_sertifikasi_asesi);
            if (!$sertifikasi) {
                return response()->json(['success' => false, 'message' => 'Data sertifikasi tidak valid.'], 404);
            }
            $idAsesi = $sertifikasi->id_asesi;

            // 3. Cek Data Lama (Logic 'LIKE' ini OK untuk struktur tabelmu sekarang)
            // Pastikan pakai Model yang benar
            $existing = BuktiDasar::where('id_data_sertifikasi_asesi', $request->id_data_sertifikasi_asesi)
                ->where('keterangan', 'LIKE', "%{$request->jenis_dokumen}%") 
                ->first();

            $pathDatabase = $existing ? $existing->bukti_dasar : null; // Sesuai kolom DB 'bukti_dasar'

            // 4. Proses Upload File
            if ($request->hasFile('file')) {
                $file = $request->file('file');
                
                // [PERBAIKAN] Nama file jadi konsisten sesuai jenis dokumen
                $extension = $file->getClientOriginalExtension();
                $safeName = str_replace(' ', '_', $request->jenis_dokumen); 
                $filename = "{$safeName}.{$extension}"; 
                
                $folderPath = "images/bukti_dasar/{$idAsesi}";
                $destinationPath = public_path($folderPath);
                
                // [PERBAIKAN PENTING DI SINI]
                // Hapus file lama DULU sebelum upload yang baru
                // Cek apakah ada file lama di database DAN filenya ada di folder
                if ($existing && $existing->bukti_dasar && File::exists(public_path($existing->bukti_dasar))) {
                    File::delete(public_path($existing->bukti_dasar));
                }

                // Buat folder jika belum ada
                if (!File::exists($destinationPath)) {
                    File::makeDirectory($destinationPath, 0755, true);
                }

                // Baru pindahkan file baru
                $file->move($destinationPath, $filename);
                
                // Update path database
                $pathDatabase = "{$folderPath}/{$filename}";
            }

            // 5. Simpan ke Database
            // Format keterangan: "Jenis Dokumen - Keterangan User"
            $finalKeterangan = $request->jenis_dokumen . ($request->keterangan ? " - " . $request->keterangan : "");

            if ($existing) {
                // Update
                $existing->update([
                    'bukti_dasar' => $pathDatabase,
                    'status_kelengkapan' => 'memenuhi',
                    'keterangan' => $finalKeterangan, // Update keterangan juga biar sinkron
                ]);
            } else {
                // Create Baru
                $existing = BuktiDasar::create([
                    'id_data_sertifikasi_asesi' => $request->id_data_sertifikasi_asesi,
                    'bukti_dasar' => $pathDatabase,
                    'status_kelengkapan' => 'memenuhi',
                    'keterangan' => $finalKeterangan,
                    'status_validasi' => false
                ]);
            }

            return response()->json([
                'success' => true, 
                'message' => 'Berhasil diupload!',
                'path' => $pathDatabase,
                'data' => $existing
            ], 200);

        } catch (\Exception $e) {
            Log::error('API (Bukti): Error - ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Server Error: ' . $e->getMessage()], 500);
        }
    }

    // ... (Fungsi deleteAjax TETAP SAMA) ...
     public function deleteAjax($id)
    {
         try {
            $data = BuktiDasar::find($id);
            if (!$data) return response()->json(['success'=>false], 404);
            
            if ($data->bukti_dasar && File::exists(public_path($data->bukti_dasar))) {
                File::delete(public_path($data->bukti_dasar));
            }
            $data->delete();
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false], 500);
        }
    }

    public function showBuktiPemohon($id_sertifikasi)
    {
         // Load 'asesi' buat sidebar
        $sertifikasi = DataSertifikasiAsesi::with('asesi')->findOrFail($id_sertifikasi);

        return view('asesi.formulir_pendaftaran.bukti_pemohon', [
            'sertifikasi' => $sertifikasi, // Data pendaftaran
            'asesi' => $sertifikasi->asesi, // Data orangnya
        ]);
    }
}
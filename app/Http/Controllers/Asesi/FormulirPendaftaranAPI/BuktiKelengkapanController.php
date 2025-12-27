<?php

namespace App\Http\Controllers\Asesi\FormulirPendaftaranAPI;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str; // Tambahin ini buat string helper
use App\Models\BuktiDasar; 
use App\Models\DataSertifikasiAsesi;

class BuktiKelengkapanController extends Controller
{
    // GET LIST (Tetap Sama)
    public function getDataBuktiKelengkapanApi($id_data_sertifikasi_asesi)
    {
        try {
            $data = BuktiDasar::where('id_data_sertifikasi_asesi', $id_data_sertifikasi_asesi)->get();
            return response()->json(['success' => true, 'data' => $data], 200);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal mengambil data.'], 500);
        }
    }

    // STORE (UPLOAD) - LOGIC BARU
    public function storeAjax(Request $request)
    {
        // 1. Validasi
        $validator = Validator::make($request->all(), [
            'id_data_sertifikasi_asesi' => 'required|integer',
            'jenis_dokumen' => 'required|string|max:255',
            'keterangan' => 'nullable|string|max:255', // Ini gabungan "Jenis - Ket User"
            'file' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120', // Max 5MB biar lega dikit
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => 'Validasi gagal', 'errors' => $validator->errors()], 422);
        }

        try {
            $sertifikasi = DataSertifikasiAsesi::find($request->id_data_sertifikasi_asesi);
            if (!$sertifikasi) {
                return response()->json(['success' => false, 'message' => 'Data sertifikasi tidak valid.'], 404);
            }
            $idAsesi = $sertifikasi->id_asesi;

            // 2. Tentukan Tipe Dokumen: SINGLE (Update) atau MULTI (Create)
            $singleUploadTypes = ['Foto Background Merah', 'KTP', 'Ijazah']; 
            
            // Cek apakah jenis dokumen ini masuk kategori SINGLE
            // Kita pakai str_contains jaga-jaga kalau stringnya agak beda dikit
            $isSingle = false;
            foreach ($singleUploadTypes as $type) {
                if (str_contains($request->jenis_dokumen, $type)) {
                    $isSingle = true;
                    break;
                }
            }

            $existing = null;

            // HANYA cari data lama kalau ini tipe SINGLE
            if ($isSingle) {
                $existing = BuktiDasar::where('id_data_sertifikasi_asesi', $request->id_data_sertifikasi_asesi)
                    ->where('keterangan', 'LIKE', "%{$request->jenis_dokumen}%") 
                    ->first();
            }

            // 3. Proses Upload File
            $pathDatabase = $existing ? $existing->bukti_dasar : null;

            if ($request->hasFile('file')) {
                $file = $request->file('file');
                
                // [PENTING] Tambahkan UniqID/Time biar file baru GAK NIMPA file lama di folder
                $extension = $file->getClientOriginalExtension();
                $safeName = str_replace(' ', '_', $request->jenis_dokumen); 
                $uniqueSuffix = time() . '_' . Str::random(5); // Tambah unik
                $filename = "{$safeName}_{$uniqueSuffix}.{$extension}"; 
                
                $folderPath = "bukti_dasar/{$idAsesi}";
                $relPath = $folderPath . '/' . $filename;
                
                // Kalau UPDATE (Single) dan ada file lama, hapus dulu file fisiknya
                if ($existing && $existing->bukti_dasar) {
                     if (Storage::disk('private_docs')->exists($existing->bukti_dasar)) {
                        Storage::disk('private_docs')->delete($existing->bukti_dasar);
                     }
                }

                // Simpan File Baru
                Storage::disk('private_docs')->putFileAs($folderPath, $file, $filename);
                $pathDatabase = $relPath;
            }

            // 4. Simpan ke Database
            // Keterangan diambil dari input frontend (sudah digabung di JS: "Jenis - Input User")
            // Kalau kosong, fallback ke jenis_dokumen
            $finalKeterangan = $request->keterangan ?? $request->jenis_dokumen; 

            if ($existing) {
                // UPDATE (Khusus KTP, Foto, Ijazah)
                $existing->update([
                    'bukti_dasar' => $pathDatabase,
                    'keterangan' => $finalKeterangan, 
                    'status_kelengkapan' => 'memenuhi', // Default status saat upload
                ]);
                $data = $existing;
            } else {
                // CREATE BARU (Sertifikasi, Surat Kerja, ATAU KTP baru)
                $data = BuktiDasar::create([
                    'id_data_sertifikasi_asesi' => $request->id_data_sertifikasi_asesi,
                    'bukti_dasar' => $pathDatabase,
                    'keterangan' => $finalKeterangan,
                    'status_kelengkapan' => 'memenuhi',
                    'status_validasi' => false
                ]);
            }

            return response()->json([
                'success' => true, 
                'message' => 'Berhasil disimpan!',
                'data' => $data
            ], 200);

        } catch (\Exception $e) {
            Log::error('API Upload Error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Server Error.'], 500);
        }
    }

    // DELETE (HAPUS FILE)
    public function deleteAjax($id)
    {
         try {
            $data = BuktiDasar::find($id);
            if (!$data) {
                return response()->json(['success' => false, 'message' => 'Data tidak ditemukan'], 404);
            }
            
            // Hapus File Fisik
            if ($data->bukti_dasar && Storage::disk('private_docs')->exists($data->bukti_dasar)) {
                Storage::disk('private_docs')->delete($data->bukti_dasar);
            }

            // Hapus Record Database
            $data->delete();

            return response()->json(['success' => true, 'message' => 'Dokumen berhasil dihapus.']);
        } catch (\Exception $e) {
            Log::error('API Delete Error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Gagal menghapus data.'], 500);
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
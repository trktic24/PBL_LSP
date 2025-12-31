<?php

namespace App\Http\Controllers\Asesi\FormulirPendaftaranAPI;

use App\Models\BuktiDasar; 
use Illuminate\Http\Request;
use App\Models\DataPortofolio;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\DataSertifikasiAsesi;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str; // Tambahin ini buat string helper

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
            'jenis_dokumen' => 'required|string',
            'keterangan' => 'nullable|string',
            'file' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => 'Validasi gagal', 'errors' => $validator->errors()], 422);
        }

        try {
            $sertifikasi = DataSertifikasiAsesi::find($request->id_data_sertifikasi_asesi);
            if (!$sertifikasi) return response()->json(['success' => false, 'message' => 'Data tidak valid.'], 404);
            
            $idAsesi = $sertifikasi->id_asesi;
            $jenisDokumen = $request->jenis_dokumen; // Ini string panjang dari Frontend

            // ====================================================
            // A. TENTUKAN KATEGORI (Dasar vs Administratif)
            // ====================================================
            // Cek keyword untuk menentukan dia masuk "Dasar"
            $isDasar = false;
            $dasarKeywords = ['Foto Background Merah', 'KTP'];
            
            foreach ($dasarKeywords as $keyword) {
                if (str_contains($jenisDokumen, $keyword)) {
                    $isDasar = true;
                    break;
                }
            }
            $kategori = $isDasar ? 'dasar' : 'administratif';

            // ====================================================
            // B. CEK MULTI UPLOAD
            // ====================================================
            $multiKeywords = [
                'Pengalaman Kerja', 
                'Curriculum Vitae',
                'Surat Keterangan Kerja', 
                'Portofolio', 
                'Sertifikat Pelatihan',
                'Sertifikat Kompetensi'
            ];
            
            $isMultiUpload = false;
            foreach ($multiKeywords as $keyword) {
                if (str_contains($jenisDokumen, $keyword)) {
                    $isMultiUpload = true;
                    break;
                }
            }

            // ====================================================
            // C. LOGIC PENAMAAN (FULL NAME - JANGAN DIPOTONG)
            // ====================================================
            $finalNamaDokumen = $jenisDokumen;

            if ($isMultiUpload) {
                // Hitung dokumen sejenis yang sudah ada di database
                // Kita pakai 'LIKE' query dengan awalan string biar akurat
                // Ambil 15 karakter pertama aja buat pencocokan biar aman (ex: "Sertifikat Pela...")
                $prefix = substr($jenisDokumen, 0, 15); 

                $count = DataPortofolio::where('id_data_sertifikasi_asesi', $request->id_data_sertifikasi_asesi)
                            ->where('persyaratan_administratif', 'LIKE', "{$prefix}%")
                            ->count();
                
                $nextIndex = $count + 1;
                
                // [REVISI] Gunakan Nama Full + Index
                // Contoh Hasil: "Sertifikat Pelatihan / Sertifikat Kompetensi 1"
                $finalNamaDokumen = "{$jenisDokumen} {$nextIndex}";
                
                // Tambahan keterangan user (jika ada)
                if ($request->keterangan) {
                    $finalNamaDokumen .= " ({$request->keterangan})";
                }
            }

            // ====================================================
            // D. CEK UPDATE ATAU CREATE (Khusus Single)
            // ====================================================
            $existingBukti = null;
            $existingPortofolio = null;

            if (!$isMultiUpload) {
                // Cari di Portofolio
                $existingPortofolio = DataPortofolio::where('id_data_sertifikasi_asesi', $request->id_data_sertifikasi_asesi)
                    ->where(function($q) use ($jenisDokumen) {
                        $q->where('persyaratan_dasar', $jenisDokumen)
                          ->orWhere('persyaratan_administratif', $jenisDokumen);
                    })->first();

                // Cari di BuktiDasar
                $existingBukti = BuktiDasar::where('id_data_sertifikasi_asesi', $request->id_data_sertifikasi_asesi)
                    ->where('keterangan', $jenisDokumen)
                    ->first();
            }

            // ====================================================
            // E. PROSES UPLOAD FILE
            // ====================================================
            $pathDatabase = $existingBukti ? $existingBukti->bukti_dasar : null;

            if ($request->hasFile('file')) {
                $file = $request->file('file');
                $extension = $file->getClientOriginalExtension();
                
                // [PENTING] Nama File Fisik HARUS Aman (Ganti tanda baca aneh jadi underscore)
                // Tapi nama di Database tetep cantik sesuai $finalNamaDokumen
                $safeName = Str::slug(substr($jenisDokumen, 0, 50), '_'); 
                $filename = "{$safeName}_" . time() . "_" . Str::random(3) . ".{$extension}";
                
                $folderPath = "bukti_dasar/{$idAsesi}";
                $relPath = $folderPath . '/' . $filename;

                if ($existingBukti && $existingBukti->bukti_dasar) {
                     if (Storage::disk('private_docs')->exists($existingBukti->bukti_dasar)) {
                        Storage::disk('private_docs')->delete($existingBukti->bukti_dasar);
                     }
                }

                Storage::disk('private_docs')->putFileAs($folderPath, $file, $filename);
                $pathDatabase = $relPath;
            }

            // ====================================================
            // F. SIMPAN KE DATABASE
            // ====================================================

            // 1. TABEL BUKTI DASAR (Simpan Path & Keterangan Full)
            if ($existingBukti) {
                $existingBukti->update([
                    'bukti_dasar' => $pathDatabase,
                    'keterangan' => $isMultiUpload ? $finalNamaDokumen : $jenisDokumen,
                    'status_kelengkapan' => 'memenuhi'
                ]);
                $buktiRecord = $existingBukti;
            } else {
                $buktiRecord = BuktiDasar::create([
                    'id_data_sertifikasi_asesi' => $request->id_data_sertifikasi_asesi,
                    'bukti_dasar' => $pathDatabase,
                    'keterangan' => $isMultiUpload ? $finalNamaDokumen : $jenisDokumen,
                    'status_kelengkapan' => 'memenuhi',
                    'status_validasi' => false
                ]);
            }

            // 2. TABEL PORTOFOLIO (Simpan Nama Dokumen Full, Path NULL)
            $kolomDasar = null;
            $kolomAdmin = null;

            if ($kategori == 'dasar') {
                $kolomDasar = $isMultiUpload ? $finalNamaDokumen : $jenisDokumen;
            } else {
                $kolomAdmin = $isMultiUpload ? $finalNamaDokumen : $jenisDokumen;
            }

            if ($existingPortofolio) {
                $existingPortofolio->update([
                    'persyaratan_dasar' => $kolomDasar,
                    'persyaratan_administratif' => $kolomAdmin
                ]);
            } else {
                DataPortofolio::create([
                    'id_data_sertifikasi_asesi' => $request->id_data_sertifikasi_asesi,
                    'persyaratan_dasar' => $kolomDasar,
                    'persyaratan_administratif' => $kolomAdmin
                ]);
            }

            return response()->json([
                'success' => true, 
                'message' => 'Berhasil disimpan!',
                'data' => $buktiRecord
            ], 200);

        } catch (\Exception $e) {
            Log::error('API Error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Server Error: ' . $e->getMessage()], 500);
        }
    }

    // DELETE AJAX
    public function deleteAjax($id)
    {
         try {
            $bukti = BuktiDasar::find($id);
            if (!$bukti) return response()->json(['success' => false, 'message' => 'Data tidak ditemukan'], 404);
            
            $namaDokumen = $bukti->keterangan; 
            $idSertifikasi = $bukti->id_data_sertifikasi_asesi;

            // Hapus Fisik
            if ($bukti->bukti_dasar && Storage::disk('private_docs')->exists($bukti->bukti_dasar)) {
                Storage::disk('private_docs')->delete($bukti->bukti_dasar);
            }

            // Hapus Portofolio (Match NULLABLE columns)
            DataPortofolio::where('id_data_sertifikasi_asesi', $idSertifikasi)
                ->where(function($query) use ($namaDokumen) {
                    $query->where('persyaratan_dasar', $namaDokumen)
                          ->orWhere('persyaratan_administratif', $namaDokumen);
                })->delete();

            // Hapus BuktiDasar
            $bukti->delete();

            return response()->json(['success' => true, 'message' => 'Dokumen berhasil dihapus.']);
        } catch (\Exception $e) {
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
<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Asesi;
use App\Models\DataSertifikasiAsesi;
use App\Models\BuktiDasar;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class AsesiProfileController extends Controller
{
    // Helper function untuk mengambil data asesi
    // [PERBAIKAN] Tambahkan relasi dataSertifikasi agar sidebar tidak perlu query ulang
    private function getAsesi($id)
    {
        return Asesi::with(['user', 'dataPekerjaan', 'dataSertifikasi.jadwal.skema'])->findOrFail($id);
    }

    // [PERBAIKAN] Helper function untuk menentukan Sertifikasi Acuan
    // Fungsi ini menangkap 'sertifikasi_id' dari URL, atau fallback ke yang terbaru
    private function getSertifikasiAcuan($asesi)
    {
        $sertifikasiId = request('sertifikasi_id');
        
        if ($sertifikasiId) {
            // Jika ada di URL, cari yang spesifik
            return $asesi->dataSertifikasi->where('id_data_sertifikasi_asesi', $sertifikasiId)->first();
        }

        // Fallback: Ambil yang paling baru dibuat
        return $asesi->dataSertifikasi->sortByDesc('created_at')->first();
    }

    // --- MENU 1: SETTINGS ---
    public function settings($id_asesi)
    {
        $asesi = $this->getAsesi($id_asesi);
        
        // [PERBAIKAN] Ambil sertifikasi acuan
        $sertifikasiAcuan = $this->getSertifikasiAcuan($asesi);

        return view('Admin.profile_asesi.asesi_profile_settings', compact('asesi', 'sertifikasiAcuan'));
    }

    // --- MENU 2: FORM ---
    public function form($id_asesi)
    {
        $asesi = $this->getAsesi($id_asesi);

        // [PERBAIKAN] Gunakan Helper untuk mendapatkan sertifikasi acuan
        // Jadi tidak lagi hardcode ->latest()->first() di sini
        $sertifikasiAcuan = $this->getSertifikasiAcuan($asesi);
        
        $activeForms = [];
        $namaSkema = 'Belum mendaftar skema';

        // 2. Cek Validitas Data Pendaftaran -> Jadwal -> Skema
        // Gunakan $sertifikasiAcuan yang sudah didapat
        if ($sertifikasiAcuan && $sertifikasiAcuan->jadwal && $sertifikasiAcuan->jadwal->skema) {
            
            $skema = $sertifikasiAcuan->jadwal->skema;
            $namaSkema = $skema->nama_skema;

            // 3. Mapping Kode Form
            $map = [
                'apl_01' => 'FR.APL.01', 'apl_02' => 'FR.APL.02',
                'fr_mapa_01' => 'FR.MAPA.01', 'fr_mapa_02' => 'FR.MAPA.02',
                'fr_ak_01' => 'FR.AK.01', 'fr_ak_04' => 'FR.AK.04',
                'fr_ia_01' => 'FR.IA.01', 'fr_ia_02' => 'FR.IA.02', 'fr_ia_03' => 'FR.IA.03',
                'fr_ia_04' => 'FR.IA.04', 'fr_ia_05' => 'FR.IA.05', 'fr_ia_06' => 'FR.IA.06',
                'fr_ia_07' => 'FR.IA.07', 'fr_ia_08' => 'FR.IA.08', 'fr_ia_09' => 'FR.IA.09',
                'fr_ia_10' => 'FR.IA.10', 'fr_ia_11' => 'FR.IA.11',
                'fr_ak_02' => 'FR.AK.02', 'fr_ak_03' => 'FR.AK.03', 'fr_ak_05' => 'FR.AK.05',
                'fr_ak_06' => 'FR.AK.06',
            ];

            // 4. Cek Konfigurasi di Database (list_form)
            if ($skema->listForm) {
                $config = $skema->listForm;
                $formsFound = false;

                foreach ($map as $dbColumn => $displayCode) {
                    if ($config->$dbColumn == 1) {
                        $activeForms[] = $displayCode;
                        $formsFound = true;
                    }
                }

                if (!$formsFound) {
                     $activeForms = array_values($map); 
                }

            } else {
                $activeForms = array_values($map); 
            }
        } 

        // [PERBAIKAN] Kirim $sertifikasiAcuan ke View
        return view('Admin.profile_asesi.asesi_profile_form', compact('asesi', 'activeForms', 'namaSkema', 'sertifikasiAcuan'));
    }
    
    // --- MENU 3: BUKTI ---
    public function bukti($id_asesi)
    {
        // [PERBAIKAN] Ambil asesi dengan relasi yang dibutuhkan
        $asesi = Asesi::with(['buktiDasar', 'dataPekerjaan', 'dataSertifikasi.jadwal.skema'])->findOrFail($id_asesi);
        
        // [PERBAIKAN] Ambil sertifikasi acuan
        $sertifikasiAcuan = $this->getSertifikasiAcuan($asesi);

        $persyaratan = [
            ['jenis' => 'Pas Foto (Background Merah)', 'desc' => 'Format: JPG/PNG, Maks 2MB.'],
            ['jenis' => 'Kartu Tanda Penduduk (KTP)', 'desc' => 'Format: JPG/PDF. Pastikan NIK terlihat jelas.'],
            ['jenis' => 'Ijazah Terakhir', 'desc' => 'Scan Ijazah Terakhir Legalisir'],
            ['jenis' => 'Sertifikat Pelatihan / Kompetensi', 'desc' => 'Sertifikat pendukung yang relevan.'],
            ['jenis' => 'Surat Keterangan Kerja / Portofolio', 'desc' => 'Dokumen Hasil Pekerjaan (Min. 2 tahun).']
        ];

        // [PERBAIKAN] Kirim $sertifikasiAcuan ke View
        return view('Admin.profile_asesi.asesi_profile_bukti', compact('asesi', 'persyaratan', 'sertifikasiAcuan'));
    }

    // --- MENU 4: TRACKER ---
    public function tracker($id_asesi)
    {
        // [PERBAIKAN] Ambil data asesi beserta relasi yang dibutuhkan
        $asesi = Asesi::with([
            'user', 
            'dataPekerjaan', 
            'dataSertifikasi' => function($q) {
                $q->with([
                    'jadwal.masterTuk',
                    'jadwal.asesor',
                    'jadwal.skema'
                ])->latest(); 
            }
        ])->findOrFail($id_asesi);

        // [PERBAIKAN] Ambil sertifikasi acuan
        $sertifikasiAcuan = $this->getSertifikasiAcuan($asesi);

        // [PERBAIKAN] Kirim $sertifikasiAcuan ke View
        return view('Admin.profile_asesi.asesi_profile_tracker', compact('asesi', 'sertifikasiAcuan'));
    }

    // --- FUNGSI UPLOAD & MANAGEMEN FILE (Semua menggunakan disk 'private_docs') ---

    public function storeBukti(Request $request, $id_asesi)
    {
        $request->validate([
            'file' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'jenis_dokumen' => 'required|string',
            'keterangan' => 'nullable|string',
        ]);

        try {
            // [CATATAN] Logika ini mengambil sertifikasi terbaru untuk dikaitkan dengan bukti
            // Jika ingin lebih spesifik, bisa gunakan request('sertifikasi_id') juga di sini
            // Tapi biasanya bukti dasar itu menempel ke Asesi (general) atau Sertifikasi Terakhir.
            // Biarkan default logic ini untuk sementara.
            $sertifikasi = DataSertifikasiAsesi::where('id_asesi', $id_asesi)
                                            ->latest('created_at')->first();

            if (!$sertifikasi) return response()->json(['success' => false, 'message' => 'Sertifikasi tidak ditemukan'], 404);

            if ($request->hasFile('file')) {
                $file = $request->file('file');
                // Format nama file: TIMESTAMP_NamaAsliFile
                $filename = time() . '_' . str_replace(' ', '_', $file->getClientOriginalName());
                
                // Folder: bukti_asesi/ID_Asesi
                $path = 'bukti_asesi/' . $id_asesi;
                
                // Simpan ke PRIVATE DISK
                $storedPath = Storage::disk('private_docs')->putFileAs($path, $file, $filename);
                
                $bukti = BuktiDasar::create([
                    'id_data_sertifikasi_asesi' => $sertifikasi->id_data_sertifikasi_asesi,
                    'bukti_dasar' => $storedPath, // Simpan path lengkap
                    'status_kelengkapan' => 'memenuhi',
                    'status_validasi' => 0,
                    'keterangan' => $request->jenis_dokumen . ($request->keterangan ? ' - ' . $request->keterangan : ''),
                ]);

                return response()->json(['success' => true, 'message' => 'Berhasil disimpan', 'data' => $bukti]);
            }
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    public function updateBukti(Request $request, $id_asesi, $id_bukti)
    {
        $request->validate([
            'file' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'keterangan' => 'nullable|string',
        ]);

        try {
            $bukti = BuktiDasar::findOrFail($id_bukti);

            if ($request->hasFile('file')) {
                // 1. Hapus File Lama dari Private Disk
                if (Storage::disk('private_docs')->exists($bukti->bukti_dasar)) {
                    Storage::disk('private_docs')->delete($bukti->bukti_dasar);
                }

                // 2. Upload File Baru ke Private Disk
                $file = $request->file('file');
                $filename = time() . '_' . str_replace(' ', '_', $file->getClientOriginalName());
                $path = 'bukti_asesi/' . $id_asesi;
                
                $storedPath = Storage::disk('private_docs')->putFileAs($path, $file, $filename);

                // 3. Update Database
                $jenisDokumenLama = explode(' - ', $bukti->keterangan)[0];

                $bukti->update([
                    'bukti_dasar' => $storedPath,
                    'status_kelengkapan' => 'memenuhi',
                    'status_validasi' => 0, 
                    'keterangan' => $jenisDokumenLama . ($request->keterangan ? ' - ' . $request->keterangan : ''),
                ]);

                return response()->json(['success' => true, 'message' => 'Dokumen berhasil diperbarui', 'data' => $bukti]);
            }
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    public function deleteBukti($id_asesi, $id_bukti)
    {
        $bukti = BuktiDasar::where('id_bukti_dasar', $id_bukti)->first();

        if ($bukti) {
            // Hapus File Fisik dari Private Disk
            if (Storage::disk('private_docs')->exists($bukti->bukti_dasar)) {
                Storage::disk('private_docs')->delete($bukti->bukti_dasar);
            }
            
            $bukti->delete();
            return response()->json(['success' => true, 'message' => 'Dokumen berhasil dihapus']);
        }

        return response()->json(['success' => false, 'message' => 'Data tidak ditemukan'], 404);
    }

    public function storeTandaTangan(Request $request, $id_asesi)
    {
        $request->validate([
            'file_ttd' => 'required|image|mimes:png,jpg,jpeg|max:2048', 
        ]);

        try {
            $asesi = Asesi::findOrFail($id_asesi);

            if ($request->hasFile('file_ttd')) {
                // Hapus file lama jika ada (dari private disk)
                if ($asesi->tanda_tangan && Storage::disk('private_docs')->exists($asesi->tanda_tangan)) {
                    Storage::disk('private_docs')->delete($asesi->tanda_tangan);
                }

                $file = $request->file('file_ttd');
                $filename = 'ttd_' . time() . '_' . $id_asesi . '.' . $file->getClientOriginalExtension();
                $path = 'ttd_asesi'; 
                
                // Simpan ke Private Disk
                $storedPath = Storage::disk('private_docs')->putFileAs($path, $file, $filename);
                
                $asesi->update([
                    'tanda_tangan' => $storedPath
                ]);

                return response()->json([
                    'success' => true, 
                    'message' => 'Tanda tangan berhasil diupload',
                    // Gunakan route secure.file agar bisa diakses di view
                    'url' => route('secure.file', ['path' => $storedPath])
                ]);
            }
            
            return response()->json(['success' => false, 'message' => 'File tidak valid'], 400);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    public function deleteTandaTangan($id_asesi)
    {
        try {
            $asesi = Asesi::findOrFail($id_asesi);

            if ($asesi->tanda_tangan) {
                // Hapus File Fisik dari Private Disk
                if (Storage::disk('private_docs')->exists($asesi->tanda_tangan)) {
                    Storage::disk('private_docs')->delete($asesi->tanda_tangan);
                }
                
                $asesi->update(['tanda_tangan' => null]);
                
                return response()->json(['success' => true, 'message' => 'Tanda tangan dihapus']);
            }

            return response()->json(['success' => false, 'message' => 'Tidak ada tanda tangan'], 404);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error: ' . $e->getMessage()], 500);
        }
    }
}
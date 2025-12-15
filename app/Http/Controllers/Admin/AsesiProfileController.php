<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Asesi;
use App\Models\DataSertifikasiAsesi;
use App\Models\BuktiDasar;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;

class AsesiProfileController extends Controller
{
    // Helper function untuk mengambil data asesi
    private function getAsesi($id)
    {
        return Asesi::with(['user', 'dataPekerjaan'])->findOrFail($id);
    }

    public function settings($id_asesi)
    {
        $asesi = $this->getAsesi($id_asesi);
        return view('admin.profile_asesi.asesi_profile_settings', compact('asesi'));
    }

   public function form($id_asesi)
    {
        // 1. Cari Data Sertifikasi (Pendaftaran) milik Asesi ini
        // Kita cari yang paling baru dibuat (latest)
        $pendaftaran = \App\Models\DataSertifikasiAsesi::with(['jadwal.skema.listForm'])
                            ->where('id_asesi', $id_asesi)
                            ->latest() // Urutkan dari yang terbaru
                            ->first();
        
        $activeForms = [];
        $namaSkema = 'Belum mendaftar skema';

        // 2. Cek Validitas Data Pendaftaran -> Jadwal -> Skema
        if ($pendaftaran && $pendaftaran->jadwal && $pendaftaran->jadwal->skema) {
            
            $skema = $pendaftaran->jadwal->skema;
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
                    // Pastikan nilainya 1 (True)
                    if ($config->$dbColumn == 1) {
                        $activeForms[] = $displayCode;
                        $formsFound = true;
                    }
                }

                // FALLBACK: Jika config ada tapi isinya 0 semua (Admin belum centang)
                if (!$formsFound) {
                     $activeForms = array_values($map); // Tampilkan semua
                }

            } else {
                // FALLBACK: Jika Admin belum pernah simpan config sama sekali
                $activeForms = array_values($map); // Tampilkan semua
            }
        } 

        // 5. Ambil Data Asesi untuk Sidebar (Optional, jika view butuh object $asesi)
        $asesi = \App\Models\Asesi::findOrFail($id_asesi);

        // DEBUGGING SEMENTARA (Hapus tanda komentar // di bawah jika masih kosong)
        // dd($activeForms, $namaSkema, $pendaftaran);

        return view('admin.profile_asesi.asesi_profile_form', compact('asesi', 'activeForms', 'namaSkema'));
    }
    
    /**
     * Menampilkan Halaman Bukti Kelengkapan
     */
    public function bukti($id_asesi)
    {
        // Ambil asesi beserta bukti dasarnya (lewat relasi HasManyThrough yang baru dibuat)
        $asesi = Asesi::with(['buktiDasar', 'dataPekerjaan'])->findOrFail($id_asesi);
        
        $persyaratan = [
            ['jenis' => 'Pas Foto (Background Merah)', 'desc' => 'Format: JPG/PNG, Maks 2MB.'],
            ['jenis' => 'Kartu Tanda Penduduk (KTP)', 'desc' => 'Format: JPG/PDF. Pastikan NIK terlihat jelas.'],
            ['jenis' => 'Ijazah Terakhir', 'desc' => 'Scan Ijazah Terakhir Legalisir'],
            ['jenis' => 'Sertifikat Pelatihan / Kompetensi', 'desc' => 'Sertifikat pendukung yang relevan.'],
            ['jenis' => 'Surat Keterangan Kerja / Portofolio', 'desc' => 'Dokumen Hasil Pekerjaan (Min. 2 tahun).']
        ];

        return view('admin.profile_asesi.asesi_profile_bukti', compact('asesi', 'persyaratan'));
    }

    public function storeBukti(Request $request, $id_asesi)
    {
        $request->validate([
            'file' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'jenis_dokumen' => 'required|string',
            'keterangan' => 'nullable|string',
        ]);

        try {
            $sertifikasi = DataSertifikasiAsesi::where('id_asesi', $id_asesi)
                                            ->latest('created_at')->first();

            if (!$sertifikasi) return response()->json(['success' => false, 'message' => 'Sertifikasi tidak ditemukan'], 404);

            if ($request->hasFile('file')) {
                $file = $request->file('file');
                $filename = time() . '_' . str_replace(' ', '_', $file->getClientOriginalName());
                $path = 'uploads/bukti_asesi/' . $id_asesi;
                
                if (!File::exists(public_path($path))) File::makeDirectory(public_path($path), 0755, true);
                
                $file->move(public_path($path), $filename);
                
                $bukti = BuktiDasar::create([
                    'id_data_sertifikasi_asesi' => $sertifikasi->id_data_sertifikasi_asesi,
                    'bukti_dasar' => $path . '/' . $filename,
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

    // FUNGSI 2: MURNI UPDATE (GANTI FILE)
    public function updateBukti(Request $request, $id_asesi, $id_bukti)
    {
        $request->validate([
            'file' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
            // Jenis dokumen tidak perlu validasi strict karena sudah ada
            'keterangan' => 'nullable|string',
        ]);

        try {
            // Cari data bukti berdasarkan ID
            $bukti = BuktiDasar::findOrFail($id_bukti);

            if ($request->hasFile('file')) {
                // 1. Hapus File Lama
                if (File::exists(public_path($bukti->bukti_dasar))) {
                    File::delete(public_path($bukti->bukti_dasar));
                }

                // 2. Upload File Baru
                $file = $request->file('file');
                $filename = time() . '_' . str_replace(' ', '_', $file->getClientOriginalName());
                $path = 'uploads/bukti_asesi/' . $id_asesi;
                $file->move(public_path($path), $filename);

                // 3. Update Database
                // Ambil jenis dokumen lama dari keterangan agar konsisten
                $jenisDokumenLama = explode(' - ', $bukti->keterangan)[0];

                $bukti->update([
                    'bukti_dasar' => $path . '/' . $filename,
                    'status_kelengkapan' => 'memenuhi',
                    'status_validasi' => 0, // Reset validasi karena file berubah
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
        // Cari bukti berdasarkan Primary Key yang ada di Model (id_bukti_dasar)
        $bukti = BuktiDasar::where('id_bukti_dasar', $id_bukti)->first();

        if ($bukti) {
            // Hapus File Fisik
            if (File::exists(public_path($bukti->bukti_dasar))) {
                File::delete(public_path($bukti->bukti_dasar));
            }
            
            $bukti->delete();
            return response()->json(['success' => true, 'message' => 'Dokumen berhasil dihapus']);
        }

        return response()->json(['success' => false, 'message' => 'Data tidak ditemukan'], 404);
    }

    public function storeTandaTangan(Request $request, $id_asesi)
    {
        $request->validate([
            'file_ttd' => 'required|image|mimes:png,jpg,jpeg|max:2048', // Max 2MB
        ]);

        try {
            $asesi = Asesi::findOrFail($id_asesi);

            if ($request->hasFile('file_ttd')) {
                // Hapus file lama jika ada
                if ($asesi->tanda_tangan && File::exists(public_path($asesi->tanda_tangan))) {
                    File::delete(public_path($asesi->tanda_tangan));
                }

                $file = $request->file('file_ttd');
                $filename = 'ttd_' . time() . '_' . $id_asesi . '.' . $file->getClientOriginalExtension();
                
                // Simpan di folder ttd_asesi
                $path = 'images/ttd_asesi';
                if (!File::exists(public_path($path))) {
                    File::makeDirectory(public_path($path), 0755, true);
                }
                
                $file->move(public_path($path), $filename);
                
                // Simpan Path ke Database
                $asesi->update([
                    'tanda_tangan' => $path . '/' . $filename
                ]);

                return response()->json([
                    'success' => true, 
                    'message' => 'Tanda tangan berhasil diupload',
                    'url' => asset($path . '/' . $filename)
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
                // Hapus File Fisik
                if (File::exists(public_path($asesi->tanda_tangan))) {
                    File::delete(public_path($asesi->tanda_tangan));
                }
                
                // Hapus path di DB
                $asesi->update(['tanda_tangan' => null]);
                
                return response()->json(['success' => true, 'message' => 'Tanda tangan dihapus']);
            }

            return response()->json(['success' => false, 'message' => 'Tidak ada tanda tangan'], 404);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    public function tracker($id_asesi)
    {
        // 1. Ambil data asesi beserta relasi yang dibutuhkan oleh halaman Tracker
        $asesi = Asesi::with([
            'user', 
            'dataPekerjaan', 
            // Ambil semua data sertifikasi asesi (untuk sidebar)
            'dataSertifikasi' => function($q) {
                // Eager Load relasi mendalam yang dibutuhkan Blade
                $q->with([
                    'jadwal.masterTuk',
                    'jadwal.asesor',
                    // 'jadwal.waktuMulai', // Jika Anda punya accessor/relasi khusus untuk waktu
                    // 'jadwal.waktuSelesai', // Jika Anda punya accessor/relasi khusus untuk waktu
                ])->latest(); // Ambil yang paling baru (yang utama dilacak)
            }
        ])->findOrFail($id_asesi);

        // 2. Kirim ke View
        return view('admin.profile_asesi.asesi_profile_tracker', compact('asesi'));
    }

}
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

    /**
     * Helper untuk mendapatkan objek DataSertifikasiAcuan
     */
    private function getSertifikasiAcuan($id_asesi, $id_sertifikasi = null)
    {
        $query = DataSertifikasiAsesi::with(['jadwal.skema.listForm', 'jadwal.masterTuk', 'jadwal.asesor'])
                                    ->where('id_asesi', $id_asesi);
                                    
        if ($id_sertifikasi) {
            // Prioritas 1: Berdasarkan ID Sertifikasi yang dikirim
            return $query->where('id_data_sertifikasi_asesi', $id_sertifikasi)->first();
        }

        // Prioritas 2: Ambil yang terbaru (Jika tidak ada konteks spesifik)
        return $query->latest('created_at')->first();
    }

    public function settings($id_asesi, $id_sertifikasi = null) 
    {
        $asesi = $this->getAsesi($id_asesi);
        // Ambil sertifikasi acuan untuk Sidebar
        $sertifikasiAcuan = $this->getSertifikasiAcuan($id_asesi, $id_sertifikasi);

        // Kirim $sertifikasiAcuan agar View dapat membuat link Sidebar yang benar
        return view('admin.profile_asesi.asesi_profile_settings', compact('asesi', 'sertifikasiAcuan'));
    }

    // app/Http/Controllers/Admin/AsesiProfileController.php @form

    // ... (kode awal)

    public function form($id_asesi, $id_sertifikasi = null)
    {
        // 1. Cari Data Sertifikasi (Ganti $pendaftaran menjadi $sertifikasi)
        $sertifikasiAcuan = $this->getSertifikasiAcuan($id_asesi, $id_sertifikasi);

        $activeForms = [];
        $namaSkema = 'Belum mendaftar skema';

        // 2. Cek Validitas
        if ($sertifikasiAcuan && $sertifikasiAcuan->jadwal && $sertifikasiAcuan->jadwal->skema) {

            $skema = $sertifikasiAcuan->jadwal->skema;
            $namaSkema = $skema->nama_skema;

            // 3. Mapping Kode Form
            $map = [
                'apl_01' => 'FR.APL.01',
                'apl_02' => 'FR.APL.02',
                'fr_mapa_01' => 'FR.MAPA.01',
                'fr_mapa_02' => 'FR.MAPA.02',
                'fr_ak_01' => 'FR.AK.01',
                'fr_ak_04' => 'FR.AK.04',
                'fr_ia_01' => 'FR.IA.01',
                'fr_ia_02' => 'FR.IA.02',
                'fr_ia_03' => 'FR.IA.03',
                'fr_ia_04' => 'FR.IA.04',
                'fr_ia_05' => 'FR.IA.05',
                'fr_ia_06' => 'FR.IA.06',
                'fr_ia_07' => 'FR.IA.07',
                'fr_ia_08' => 'FR.IA.08',
                'fr_ia_09' => 'FR.IA.09',
                'fr_ia_10' => 'FR.IA.10',
                'fr_ia_11' => 'FR.IA.11',
                'fr_ak_02' => 'FR.AK.02',
                'fr_ak_03' => 'FR.AK.03',
                'fr_ak_05' => 'FR.AK.05',
                'fr_ak_06' => 'FR.AK.06',
            ];

            // 4. Cek Konfigurasi di Database (list_form)
            if ($skema->listForm) {
                $config = $skema->listForm;

                // Loop dan hanya masukkan form yang Dicentang (bernilai 1)
                foreach ($map as $dbColumn => $displayCode) {
                    // Gunakan strict checking == 1 atau (bool) $config->$dbColumn
                    if ((bool)$config->$dbColumn === true) {
                        $activeForms[] = $displayCode;
                    }
                }

            } else {
                // [REVISI FALLBACK] Jika ternyata LIST FORM BELUM DIBUAT (WALAU SEHARUSNYA TIDAK TERJADI)
                // Asumsi default skema baru adalah semua form aktif (TRUE)
                $activeForms = array_values($map);
            }
        }

        // 5. Ambil Data Asesi untuk Sidebar
        $asesi = \App\Models\Asesi::findOrFail($id_asesi);

        // [PERBAIKAN] Kirim objek $sertifikasi ke view (seperti saran sebelumnya)
        return view('admin.profile_asesi.asesi_profile_form', compact('asesi', 'activeForms', 'namaSkema', 'sertifikasiAcuan'));
    }

    /**
     * Menampilkan Halaman Bukti Kelengkapan
     */
    public function bukti($id_asesi, $id_sertifikasi = null)
    {
        $asesi = Asesi::with(['buktiDasar', 'dataPekerjaan'])->findOrFail($id_asesi);
        
        // Ambil Sertifikasi Acuan untuk konteks Skema di Sidebar/Bukti
        $sertifikasiAcuan = $this->getSertifikasiAcuan($id_asesi, $id_sertifikasi);

        $persyaratan = [
            ['jenis' => 'Pas Foto (Background Merah)', 'desc' => 'Format: JPG/PNG, Maks 2MB.'],
            ['jenis' => 'Kartu Tanda Penduduk (KTP)', 'desc' => 'Format: JPG/PDF. Pastikan NIK terlihat jelas.'],
            ['jenis' => 'Ijazah Terakhir', 'desc' => 'Scan Ijazah Terakhir Legalisir'],
            ['jenis' => 'Sertifikat Pelatihan / Kompetensi', 'desc' => 'Sertifikat pendukung yang relevan.'],
            ['jenis' => 'Surat Keterangan Kerja / Portofolio', 'desc' => 'Dokumen Hasil Pekerjaan (Min. 2 tahun).']
        ];

        return view('admin.profile_asesi.asesi_profile_bukti', compact('asesi', 'persyaratan', 'sertifikasiAcuan'));
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

    public function tracker($id_asesi, $id_sertifikasi = null)
    {
        // 1. Ambil data asesi dasar (tanpa relasi dalam, karena sertifikasi diurus helper)
        $asesi = $this->getAsesi($id_asesi); 
        
        // 2. Ambil Sertifikasi Acuan dengan semua relasi yang dibutuhkan Sidebar/Tracker
        // Helper getSertifikasiAcuan sudah memuat relasi: jadwal.skema.listForm, jadwal.masterTuk, jadwal.asesor
        $sertifikasiAcuan = $this->getSertifikasiAcuan($id_asesi, $id_sertifikasi);

        // 3. Kirim ke View
        // Di view tracker, Anda akan menggunakan $sertifikasiAcuan (menggantikan $sertifikasi)
        return view('admin.profile_asesi.asesi_profile_tracker', compact('asesi', 'sertifikasiAcuan'));
    }
}

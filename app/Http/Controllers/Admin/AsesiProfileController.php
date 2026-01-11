<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Asesi;
use App\Models\DataSertifikasiAsesi;
use App\Models\BuktiDasar;
use App\Models\DataPortofolio;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Str;

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

    // --- MENU 1: SETTINGS (Now: Laporan Asesi) ---
    public function settings($id_asesi)
    {
        $asesi = $this->getAsesi($id_asesi);

        // [PERBAIKAN] Ambil sertifikasi acuan
        $sertifikasiAcuan = $this->getSertifikasiAcuan($asesi);
        
        // Logic to get Active Forms (Copied from form method)
        $activeForms = [];
        $namaSkema = 'Belum mendaftar skema';

        if ($sertifikasiAcuan && $sertifikasiAcuan->jadwal && $sertifikasiAcuan->jadwal->skema) {

            $skema = $sertifikasiAcuan->jadwal->skema;
            $namaSkema = $skema->nama_skema;

            // 3. Mapping Kode Form - Definition only
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

        return view('Admin.profile_asesi.asesi_profile_settings', compact('asesi', 'sertifikasiAcuan', 'activeForms', 'namaSkema'));
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
    public function tracker(Request $request, $id_asesi)
    {
        // 1. Ambil data asesi
        $asesi = Asesi::with([
            'user',
            'dataPekerjaan',
            'dataSertifikasi' => function ($q) {
                $q->with([
                    'jadwal.masterTuk',
                    'jadwal.asesor',
                    'jadwal.skema'
                ])->latest();
            }
        ])->findOrFail($id_asesi);

        // 2. Ambil sertifikasi yang sedang aktif/terbaru
        // Pastikan function getSertifikasiAcuan mengembalikan object sertifikasi
        $sertifikasi = $this->getSertifikasiAcuan($asesi);

        // Jika tidak ada sertifikasi, handle error atau return view kosong
        if (!$sertifikasi) {
            return back()->with('error', 'Data sertifikasi tidak ditemukan.');
        }

        // ==========================================================
        // LOGIC STEP 2: PEMBAYARAN
        // ==========================================================
        $paymentVerified = $sertifikasi->rekomendasi_apl01 == 'diterima';
        $paymentRejected = $sertifikasi->rekomendasi_apl01 === 'tidak diterima';
        $isMidtransProcess = $sertifikasi->status_sertifikasi == 'menunggu_verifikasi_bayar';

        // ==========================================================
        // LOGIC STEP 3: PRA-ASESMEN (APL-02)
        // ==========================================================
        // Kuning = Menunggu Asesi, Biru = Menunggu Asesor, Hijau = Selesai, Merah = Ditolak

        $apl02Diterima = $sertifikasi->rekomendasi_apl02 == 'diterima';       // Status dari Asesor
        $apl02Ditolak  = $sertifikasi->rekomendasi_apl02 === 'tidak diterima'; // Status dari Asesor

        // Asumsi Level: 
        // 30 = Pembayaran Lunas (Asesi belum isi form)
        // 40 = Asesi Sudah Submit APL-02 (Menunggu Asesor)
        // 50 = Asesor Sudah Verifikasi
        $sudahSubmitAPL02 = $sertifikasi->progres_level >= 40;

        // Default Status
        $statusStep3 = 'locked';

        if ($paymentVerified) {
            if ($apl02Diterima) {
                $statusStep3 = 'selesai';         // HIJAU (Download Aktif)
            } elseif ($apl02Ditolak) {
                $statusStep3 = 'ditolak';         // MERAH (Silang & Tali Merah)
            } elseif ($sudahSubmitAPL02) {
                $statusStep3 = 'menunggu_asesor'; // BIRU (Admin/Asesor memantau)
            } else {
                $statusStep3 = 'menunggu_asesi';  // KUNING (Asesi belum isi)
            }
        }
        // Jika pembayaran ditolak, statusStep3 tetap 'locked' (atau bisa dianggap stop)

        // ==========================================================
        // LOGIC STEP 6: ASESMEN & WAKTU (FIX ERROR DISINI)
        // ==========================================================

        // 1. Hasil Akhir
        $hasilAK02 = $sertifikasi->rekomendasi_hasil_asesmen_AK02;
        $isFinished = !is_null($hasilAK02); // Sudah ada nilai

        // 2. Logic Waktu (Time Constraints)
        $jadwal = $sertifikasi->jadwal;
        $now = Carbon::now();

        // [FIX] Ambil tanggalnya saja dan jamnya saja secara eksplisit
        $tgl = Carbon::parse($jadwal->tanggal_pelaksanaan)->format('Y-m-d');
        $jamMulai = Carbon::parse($jadwal->waktu_mulai)->format('H:i:s');
        $jamSelesai = Carbon::parse($jadwal->waktu_selesai)->format('H:i:s');

        // Baru digabungkan dengan format yang bersih
        $startDateTime = Carbon::parse($tgl . ' ' . $jamMulai);
        $endDateTime   = Carbon::parse($tgl . ' ' . $jamSelesai);

        // Variable Flag Waktu
        $hasStarted = $now->greaterThanOrEqualTo($startDateTime); // Sudah lewat jam mulai
        $hasEnded   = $now->greaterThan($endDateTime);            // Sudah lewat jam selesai

        // Format Teks Tanggal untuk View
        $startDateFormatted = $startDateTime->translatedFormat('d M Y, H:i');

        // Cek Hasil Akhir AK.02
        $hasilAK02 = $sertifikasi->rekomendasi_hasil_asesmen_AK02;

        // Jika belum ada logic ini, kita default true atau cek manual
        $skemaId = $sertifikasi->id_skema ?? $sertifikasi->jadwal->id_skema;

        // Misal: $showIA05 = Ia05::where('id_skema', $skemaId)->exists();
        $showIA02 = true; // Praktik
        $showIA05 = true; // Pilihan Ganda
        $showIA06 = true; // Essay
        $showIA07 = true; // Lisan
        $showIA09 = true; // Wawancara

        // ==========================================================
        // KIRIM KE VIEW
        // ==========================================================
        // Kita kirim variable $sertifikasi (bukan sertifikasiAcuan agar konsisten dengan view)
        return view('Admin.profile_asesi.asesi_profile_tracker', compact(
            'asesi',
            'sertifikasi',
            'paymentVerified',
            'paymentRejected',
            'isMidtransProcess',
            'statusStep3',
            'hasilAK02',
            'isFinished',
            'hasStarted',
            'hasEnded',
            'startDateFormatted', // Kirim format tanggal ke view
            'showIA02',
            'showIA05',
            'showIA06',
            'showIA07',
            'showIA09'
        ));
    }

    /**
     * 1. Upload Sertifikat (PRIVATE PATH)
     */
    public function uploadSertifikatAsesi(Request $request, $id_asesi, $id)
    {
        $request->validate([
            'sertifikat' => 'required|mimes:pdf|max:2048',
        ]);

        $sertifikasi = DataSertifikasiAsesi::where('id_data_sertifikasi_asesi', $id)
            ->where('id_asesi', $id_asesi)
            ->firstOrFail();

        if ($request->hasFile('sertifikat')) {
            // Hapus file lama (Gunakan disk default/local)
            if ($sertifikasi->sertifikat && Storage::exists($sertifikasi->sertifikat)) {
                Storage::delete($sertifikasi->sertifikat);
            }

            // Simpan ke: storage/app/private_uploads/sertifikat
            // Kita tidak memakai parameter 'public' di sini
            $path = $request->file('sertifikat')->store('private_uploads/sertifikat');

            $sertifikasi->sertifikat = $path;
            $sertifikasi->save();

            return redirect()->back()->with('success', 'Sertifikat berhasil diunggah.');
        }

        return redirect()->back()->with('error', 'Gagal mengunggah file.');
    }

    /**
     * 2. Download Sertifikat (SERVING PRIVATE FILE)
     */
    public function downloadSertifikat(Request $request, $id_asesi, $id)
    {
        $sertifikasi = DataSertifikasiAsesi::where('id_data_sertifikasi_asesi', $id)
            ->where('id_asesi', $id_asesi)
            ->firstOrFail();

        // Cek file di storage local (private)
        if (empty($sertifikasi->sertifikat) || !Storage::exists($sertifikasi->sertifikat)) {
            return redirect()->back()->with('error', 'File sertifikat tidak ditemukan di server.');
        }

        $namaFile = 'Sertifikat_Kompetensi_' . str_replace(' ', '_', $sertifikasi->asesi->nama_lengkap ?? 'Asesi') . '.pdf';
        if ($request->query('mode') == 'preview') {
            // Menampilkan file (Preview)
            return response()->file(storage_path('app/' . $sertifikasi->sertifikat));
        }
        // Download dari storage local (private)
        return Storage::download($sertifikasi->sertifikat, $namaFile);
    }

    // --- VERIFIKASI PEMBAYARAN (STEP 2) ---
    public function verifikasiPembayaran(Request $request, $id_asesi, $id_sertifikasi)
    {
        $sertifikasi = DataSertifikasiAsesi::findOrFail($id_sertifikasi);
        $status = $request->input('status');

        $updateData = [];
        $pesan = '';

        if ($status == 'diterima') {
            $dbStatus = 'diterima';
            $updateData['progres_level'] = 30; // Level Lunas
            $pesan = 'Pembayaran berhasil diverifikasi.';
        } elseif ($status == 'ditolak') {
            // Menggunakan spasi sesuai request Anda
            $dbStatus = 'tidak diterima';
            $updateData['progres_level'] = 10; // Level Reset
            $pesan = 'Pembayaran ditolak.';
        } elseif ($status == 'menunggu') {
            // Tombol Batal
            $dbStatus = null;
            $updateData['progres_level'] = 20; // Level Menunggu
            $pesan = 'Verifikasi dibatalkan.';
        }

        $sertifikasi->update([
            'rekomendasi_apl01' => $dbStatus,
            'progres_level' => $updateData['progres_level'] ?? $sertifikasi->progres_level
        ]);

        return back()->with('success', $pesan);
    }

    public function updateKeputusan(Request $request, $id_asesi, $id)
    {
        // Tambahkan 'menunggu' ke validasi
        $request->validate([
            'status' => 'required|in:direkomendasikan,tidak_direkomendasikan,menunggu',
        ]);

        $sertifikasi = DataSertifikasiAsesi::where('id_data_sertifikasi_asesi', $id)
            ->where('id_asesi', $id_asesi)
            ->firstOrFail();
        
        // Jika status 'menunggu' (Batal), kembalikan ke status sebelumnya (misal: umpan_balik_selesai)
        if ($request->status == 'menunggu') {
            $statusDb = 'umpan_balik_selesai'; // Sesuaikan dengan status flow Anda sebelumnya
            $msg = 'Keputusan dibatalkan.';
        } else {
            $statusDb = $request->status;
            $msg = 'Keputusan berhasil disimpan.';
        }

        $sertifikasi->update([
            'status_sertifikasi' => $statusDb,
        ]);

        return back()->with('success', $msg);
    }

    // --- FUNGSI UPLOAD & MANAGEMEN FILE (Semua menggunakan disk 'private_docs') ---

    /**
     * Store Bukti (Admin Upload) - Logic Diperbaiki
     */
    public function storeBukti(Request $request, $id_asesi)
    {
        // 1. Validasi Input
        $validator = Validator::make($request->all(), [
            'file' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'jenis_dokumen' => 'required|string',
            'keterangan' => 'nullable|string',
            // Tambahan: Wajib kirim ID Sertifikasi dari Frontend (hidden input)
            'id_data_sertifikasi_asesi' => 'required|exists:data_sertifikasi_asesi,id_data_sertifikasi_asesi' 
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()->first()], 422);
        }

        try {
            // 2. Ambil Data Sertifikasi
            // Gunakan ID yang dikirim dari form, jangan ambil 'latest' sembarangan
            $idSertifikasi = $request->id_data_sertifikasi_asesi;
            $jenisDokumen = $request->jenis_dokumen;

            // ====================================================
            // A. TENTUKAN KATEGORI (Dasar vs Administratif)
            // ====================================================
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
                'Pengalaman Kerja', 'Curriculum Vitae', 'Surat Keterangan Kerja', 
                'Portofolio', 'Sertifikat Pelatihan', 'Sertifikat Kompetensi'
            ];
            
            $isMultiUpload = false;
            foreach ($multiKeywords as $keyword) {
                if (str_contains($jenisDokumen, $keyword)) {
                    $isMultiUpload = true;
                    break;
                }
            }

            // ====================================================
            // C. LOGIC PENAMAAN (FULL NAME + INDEX)
            // ====================================================
            $finalNamaDokumen = $jenisDokumen;

            if ($isMultiUpload) {
                // Hitung dokumen sejenis di sertifikasi INI
                $prefix = substr($jenisDokumen, 0, 15); 

                $count = DataPortofolio::where('id_data_sertifikasi_asesi', $idSertifikasi)
                            ->where('persyaratan_administratif', 'LIKE', "{$prefix}%")
                            ->count();
                
                $nextIndex = $count + 1;
                $finalNamaDokumen = "{$jenisDokumen} {$nextIndex}";
                
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
                $existingPortofolio = DataPortofolio::where('id_data_sertifikasi_asesi', $idSertifikasi)
                    ->where(function($q) use ($jenisDokumen) {
                        $q->where('persyaratan_dasar', $jenisDokumen)
                          ->orWhere('persyaratan_administratif', $jenisDokumen);
                    })->first();

                // Cari di BuktiDasar
                $existingBukti = BuktiDasar::where('id_data_sertifikasi_asesi', $idSertifikasi)
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
                
                // Nama File Fisik Aman
                $safeName = Str::slug(substr($jenisDokumen, 0, 50), '_'); 
                $filename = "{$safeName}_" . time() . "_" . Str::random(3) . ".{$extension}";
                
                // Gunakan ID Sertifikasi agar folder terpisah per pendaftaran
                $folderPath = "bukti_dasar/{$idSertifikasi}";
                $relPath = $folderPath . '/' . $filename;

                // Hapus file lama jika ada
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

            // 1. TABEL BUKTI DASAR
            if ($existingBukti) {
                $existingBukti->update([
                    'bukti_dasar' => $pathDatabase,
                    'keterangan' => $isMultiUpload ? $finalNamaDokumen : $jenisDokumen,
                    'status_kelengkapan' => 'memenuhi'
                ]);
                $buktiRecord = $existingBukti;
            } else {
                $buktiRecord = BuktiDasar::create([
                    'id_data_sertifikasi_asesi' => $idSertifikasi,
                    'bukti_dasar' => $pathDatabase,
                    'keterangan' => $isMultiUpload ? $finalNamaDokumen : $jenisDokumen,
                    'status_kelengkapan' => 'memenuhi',
                    'status_validasi' => 1 // Auto Valid karena Admin yg upload
                ]);
            }

            // 2. TABEL PORTOFOLIO
            $kolomDasar = ($kategori == 'dasar') ? ($isMultiUpload ? $finalNamaDokumen : $jenisDokumen) : null;
            $kolomAdmin = ($kategori != 'dasar') ? ($isMultiUpload ? $finalNamaDokumen : $jenisDokumen) : null;

            if ($existingPortofolio) {
                $existingPortofolio->update([
                    'persyaratan_dasar' => $kolomDasar,
                    'persyaratan_administratif' => $kolomAdmin
                ]);
            } else {
                DataPortofolio::create([
                    'id_data_sertifikasi_asesi' => $idSertifikasi,
                    'persyaratan_dasar' => $kolomDasar,
                    'persyaratan_administratif' => $kolomAdmin
                ]);
            }

            return response()->json([
                'success' => true, 
                'message' => 'Dokumen berhasil diunggah oleh Admin.', 
                'data' => $buktiRecord
            ]);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Delete Bukti (Admin Action) - Update Logic
     */
    public function deleteBukti($id_asesi, $id_bukti)
    {
        try {
            $bukti = BuktiDasar::findOrFail($id_bukti);
            $namaDokumen = $bukti->keterangan;
            $idSertifikasi = $bukti->id_data_sertifikasi_asesi;

            // 1. Hapus File Fisik
            if ($bukti->bukti_dasar && Storage::disk('private_docs')->exists($bukti->bukti_dasar)) {
                Storage::disk('private_docs')->delete($bukti->bukti_dasar);
            }

            // 2. Hapus Data Portofolio Terkait
            DataPortofolio::where('id_data_sertifikasi_asesi', $idSertifikasi)
                ->where(function($query) use ($namaDokumen) {
                    $query->where('persyaratan_dasar', $namaDokumen)
                          ->orWhere('persyaratan_administratif', $namaDokumen);
                })->delete();

            // 3. Hapus Bukti Dasar
            $bukti->delete();

            return response()->json(['success' => true, 'message' => 'Dokumen berhasil dihapus oleh Admin.']);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error: ' . $e->getMessage()], 500);
        }
    }


    /**
     * Update Bukti Kelengkapan (Admin Action) - Logic Sinkron Portofolio
     */
    public function updateBukti(Request $request, $id_asesi, $id_bukti)
    {
        // 1. Validasi Input
        $validator = Validator::make($request->all(), [
            'file' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120', // File opsional saat update
            'keterangan' => 'nullable|string', // Catatan tambahan baru
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()->first()], 422);
        }

        try {
            // 2. Ambil Data Eksisting
            $bukti = BuktiDasar::findOrFail($id_bukti);
            $namaDokumenLama = $bukti->keterangan; // Simpan nama lama untuk pencarian di Portofolio
            $idSertifikasi = $bukti->id_data_sertifikasi_asesi;

            // 3. Proses Nama Baru (Jika ada perubahan keterangan)
            // Format di DB biasanya: "Nama Dokumen" ATAU "Nama Dokumen - Keterangan User"
            // Kita pisahkan dulu nama dasar dokumennya
            $parts = explode(' (', $namaDokumenLama); // Cek format "Nama (Keterangan)"
            if (count($parts) < 2) {
                $parts = explode(' - ', $namaDokumenLama); // Cek format "Nama - Keterangan"
            }
            $namaDasarDokumen = trim($parts[0]);

            // Susun Nama Baru
            $namaBaruFull = $namaDasarDokumen;
            if ($request->filled('keterangan')) {
                $namaBaruFull .= " ({$request->keterangan})";
            } elseif (isset($parts[1])) {
                // Jika tidak ada input keterangan baru, tapi di lama ada, pertahankan yg lama? 
                // Atau reset? Disini logicnya: Jika input kosong, hilangkan keterangan.
                // Jika ingin mempertahankan, uncomment baris bawah:
                // $namaBaruFull = $namaDokumenLama; 
            }

            // 4. Update File (Jika ada upload baru)
            if ($request->hasFile('file')) {
                // A. Hapus File Lama Fisik
                if ($bukti->bukti_dasar && Storage::disk('private_docs')->exists($bukti->bukti_dasar)) {
                    Storage::disk('private_docs')->delete($bukti->bukti_dasar);
                }

                // B. Upload File Baru
                $file = $request->file('file');
                $extension = $file->getClientOriginalExtension();
                
                // Nama File Fisik Aman
                $safeName = Str::slug(substr($namaDasarDokumen, 0, 50), '_'); 
                $filename = "{$safeName}_" . time() . "_" . Str::random(3) . ".{$extension}";
                
                // Gunakan ID Sertifikasi agar folder terpisah per pendaftaran
                $path = "bukti_dasar/{$idSertifikasi}";
                $storedPath = Storage::disk('private_docs')->putFileAs($path, $file, $filename);

                // Update Path di Object
                $bukti->bukti_dasar = $storedPath;
            }

            // 5. Update Record BUKTI DASAR
            $bukti->keterangan = $namaBaruFull;
            $bukti->status_kelengkapan = 'memenuhi'; // Admin update = auto memenuhi
            $bukti->status_validasi = 1; // Auto valid
            $bukti->save();

            // 6. Sinkronisasi Update ke Tabel PORTOFOLIO
            // Cari record portofolio yang namanya sama dengan nama LAMA
            $portofolio = DataPortofolio::where('id_data_sertifikasi_asesi', $idSertifikasi)
                ->where(function($q) use ($namaDokumenLama) {
                    $q->where('persyaratan_dasar', $namaDokumenLama)
                      ->orWhere('persyaratan_administratif', $namaDokumenLama);
                })->first();

            if ($portofolio) {
                // Tentukan kolom mana yang harus diupdate
                $updateData = [];
                if ($portofolio->persyaratan_dasar == $namaDokumenLama) {
                    $updateData['persyaratan_dasar'] = $namaBaruFull;
                }
                if ($portofolio->persyaratan_administratif == $namaDokumenLama) {
                    $updateData['persyaratan_administratif'] = $namaBaruFull;
                }
                
                if (!empty($updateData)) {
                    $portofolio->update($updateData);
                }
            }

            return response()->json([
                'success' => true, 
                'message' => 'Dokumen berhasil diperbarui.', 
                'data' => $bukti
            ]);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error: ' . $e->getMessage()], 500);
        }
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

    /**
     * Helper to show tracker by Data Sertifikasi ID (used in Master View)
     */
    public function showTrackerBySertifikasi($id_data_sertifikasi_asesi)
    {
        $sertifikasi = \App\Models\DataSertifikasiAsesi::findOrFail($id_data_sertifikasi_asesi);
        
        // Inject sertifikasi_id into request so getSertifikasiAcuan picks it up
        request()->merge(['sertifikasi_id' => $id_data_sertifikasi_asesi]);
        
        return $this->tracker(request(), $sertifikasi->id_asesi);
    }
}

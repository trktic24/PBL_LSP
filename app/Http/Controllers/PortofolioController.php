<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Asesi;
use App\Models\BuktiDasar;
use App\Models\DataSertifikasiAsesi; 
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Carbon;
use App\Models\DataPortofolio;

class PortofolioController extends Controller
{
    /**
     * Menampilkan data Asesi di halaman Portofolio (PORTOFOLIO.blade.php).
     */
    public function index()
    {
        // ASUMSI: Mengambil data Asesi dengan ID 1 (Ganti dengan logic Auth::id() jika sudah implementasi)
        $asesi = Asesi::find(1);
        $sertifikasi = null; 
        
        if (!$asesi) {
            $asesi = (object) ['nama_lengkap' => 'Data Asesi Tidak Ditemukan', 'id_asesi' => null];
        }

        // Cari ID data sertifikasi asesi yang berlaku untuk Asesi ini
        $id_data_sertifikasi_asesi = null;
        if (isset($asesi->id_asesi)) {
            $dataSertifikasi = DataSertifikasiAsesi::where('id_asesi', $asesi->id_asesi)->first();
            if ($dataSertifikasi) {
                $id_data_sertifikasi_asesi = $dataSertifikasi->id_data_sertifikasi_asesi;
                $sertifikasi = $dataSertifikasi;
            }
        }

        // Ambil semua path dokumen yang pernah diunggah untuk sertifikasi ini
        $buktiDokumen = BuktiDasar::where('id_data_sertifikasi_asesi', $id_data_sertifikasi_asesi ?? 0)
                                 ->get();
        
        // Kita kirimkan semua data dan ID ke view
        return view('PORTOFOLIO', compact('asesi', 'buktiDokumen', 'sertifikasi'));
    }

    /**
     * Memproses upload file dan menyimpan path ke tabel portofolio.
     */
    public function store(Request $request)
    {
        // 1. Validasi File Upload
        $request->validate([
            'ktp' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'ijazah' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'pendukung_dasar' => 'nullable|file|mimes:pdf,zip|max:5120',
            'bukti_pembayaran' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'formulir_pendaftaran' => 'nullable|file|mimes:pdf|max:2048',
            'cv' => 'nullable|file|mimes:pdf|max:2048',
            'pendukung_admin' => 'nullable|file|mimes:pdf,zip|max:5120',
        ]);
        
        // *ASUMSI PENTING*: Mendapatkan id_data_sertifikasi_asesi yang VALID
        $asesi = Asesi::find(1); 
        $dataSertifikasi = DataSertifikasiAsesi::where('id_asesi', optional($asesi)->id_asesi)->first();

        if (!$asesi || !$dataSertifikasi) {
            return redirect()->route('portofolio.index')->with('error', 'Data Asesi atau Sertifikasi tidak ditemukan. Gagal menyimpan.');
        }

        $id_data_sertifikasi_asesi = $dataSertifikasi->id_data_sertifikasi_asesi;

        // 2. Definisi Grup File untuk disimpan
        $fileGroups = [
            'persyaratan_dasar' => ['ktp', 'ijazah', 'pendukung_dasar'],
            'persyaratan_administratif' => ['bukti_pembayaran', 'formulir_pendaftaran', 'cv', 'pendukung_admin'],
        ];

        $uploadedPaths = [
            'persyaratan_dasar' => null,
            'persyaratan_administratif' => null,
        ];

        $uploadedCount = 0;
        $fileUploads = $request->allFiles();
        $baseFolder = 'portofolio/';

        try {
            // 3. Proses Upload dan Simpan Path per Grup
            foreach ($fileGroups as $groupName => $fileNames) {
                
                foreach ($fileNames as $fieldName) {
                    if (isset($fileUploads[$fieldName])) {
                        $file = $fileUploads[$fieldName];
                        $extension = $file->getClientOriginalExtension();
                        
                        $fileNameToStore = $id_data_sertifikasi_asesi . '_' . strtoupper($fieldName) . '_' . time() . '.' . $extension;
                        
                        // Menyimpan file fisik
                        $path = $file->storeAs('public/portofolio', $fileNameToStore);
                        $file_path_db = $baseFolder . $fileNameToStore;
                        
                        // Menyimpan path file terakhir di grup ini untuk tabel 'portofolio'
                        $uploadedPaths[$groupName] = $file_path_db;
                        $uploadedCount++;

                        // Simpan di tabel bukti_dasar (Tambahan kolom 'keterangan' untuk menghindari error 1364)
                        BuktiDasar::create([
                            'id_data_sertifikasi_asesi' => $id_data_sertifikasi_asesi,
                            'bukti_dasar' => $file_path_db, 
                            'status_kelengkapan' => 'memenuhi', 
                            'status_validasi' => false,
                            'keterangan' => 'Dokumen ' . strtoupper($fieldName) . ' diunggah melalui form Portofolio.',
                            'created_at' => Carbon::now(),
                            'updated_at' => Carbon::now(),
                        ]);
                    }
                }
            }

            // 4. Update/Create baris di tabel `portofolio`
            
            $portofolio = DataPortofolio::firstOrNew(['id_data_sertifikasi_asesi' => $id_data_sertifikasi_asesi]);

            // Jika record baru, kita perlu memberikan nilai untuk kedua kolom
            // Jika record lama, kita hanya perlu update kolom yang baru di-upload.
            
            $updateData = [];
            
            // Periksa apakah ini adalah INSERT baru atau UPDATE
            if (!$portofolio->exists) {
                // INSERT: Kita harus menyediakan nilai untuk SEMUA kolom NOT NULL (kecuali PK)
                $updateData['persyaratan_dasar'] = $uploadedPaths['persyaratan_dasar'] ?? 'Sertakan dokumen'; // Default dari migration
                $updateData['persyaratan_administratif'] = $uploadedPaths['persyaratan_administratif'] ?? 'Sertakan dokumen'; // Default dari migration
            } else {
                // UPDATE: Kita hanya menyediakan kolom yang baru diupload
                if ($uploadedPaths['persyaratan_dasar']) {
                    $updateData['persyaratan_dasar'] = $uploadedPaths['persyaratan_dasar'];
                }
                if ($uploadedPaths['persyaratan_administratif']) {
                    $updateData['persyaratan_administratif'] = $uploadedPaths['persyaratan_administratif'];
                }
            }

            // Gabungkan updateData dengan objek portofolio dan simpan
            if (!empty($updateData)) {
                $portofolio->fill($updateData)->save();
            } else if (!$portofolio->exists && $uploadedCount == 0) {
                 // Kasus tidak ada upload sama sekali dan ini record baru, tetap save dengan nilai default
                 $portofolio->persyaratan_dasar = 'Sertakan dokumen';
                 $portofolio->persyaratan_administratif = 'Sertakan dokumen';
                 $portofolio->save();
            }
            
            
            // 5. Berikan feedback
            if ($uploadedCount > 0) {
                return redirect()->route('portofolio.index')->with('success', $uploadedCount . ' dokumen Portofolio berhasil diunggah dan data ringkasan disimpan!');
            } else {
                return redirect()->route('portofolio.index')->with('info', 'Tidak ada dokumen baru yang diunggah.');
            }

        } catch (\Exception $e) {
            return redirect()->route('portofolio.index')->with('error', 'Gagal menyimpan dokumen. Error: ' . $e->getMessage());
        }
    }

    /**
     * Logika untuk membatalkan/menghapus file yang sudah ada dari database dan storage.
     */
    public function destroyBukti(Request $request)
    {
        $id_bukti_dasar = $request->input('id_bukti_dasar_to_delete');
        
        $bukti = BuktiDasar::find($id_bukti_dasar);

        if (!$bukti) {
            return redirect()->back()->with('error', 'Dokumen tidak ditemukan.');
        }
        
        $filePath = $bukti->bukti_dasar;

        // Hapus entri dari database
        $bukti->delete();

        // Hapus file fisik dari storage (Pastikan Anda menggunakan 'public/' di path storage)
        if (Storage::exists('public/' . $filePath)) {
            Storage::delete('public/' . $filePath);
        }

        return redirect()->back()->with('success', 'Dokumen berhasil dibatalkan dan dihapus.');
    }
}
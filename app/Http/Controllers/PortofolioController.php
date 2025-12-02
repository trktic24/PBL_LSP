<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Asesi; 
use App\Models\BuktiDasar; // Pastikan Anda sudah membuat Model ini
use App\Models\DataSertifikasiAsesi; // Asumsikan Anda memiliki Model ini
use Illuminate\Support\Facades\Storage; 
use Illuminate\Support\Carbon;

class PortofolioController extends Controller
{
    /**
     * Menampilkan data Asesi di halaman Portofolio.
     */
    public function index()
    {
        // ASUMSI: Mengambil data Asesi dengan ID 1 (karena tidak menggunakan Auth)
        $asesi = Asesi::find(1); 
        
        if (!$asesi) {
            $asesi = (object) ['nama_lengkap' => 'Data Asesi Tidak Ditemukan', 'id_asesi' => null];
        }

        // Cari ID data sertifikasi asesi yang berlaku untuk Asesi ini
        $id_data_sertifikasi_asesi = null;
        if ($asesi->id_asesi) {
            $dataSertifikasi = DataSertifikasiAsesi::where('id_asesi', $asesi->id_asesi)->first();
            if ($dataSertifikasi) {
                $id_data_sertifikasi_asesi = $dataSertifikasi->id_data_sertifikasi_asesi;
            }
        }

        // Ambil semua path dokumen yang pernah diunggah untuk sertifikasi ini
        // KARENA TIDAK ADA KOLOM TIPE, kita hanya bisa mengambil data mentah.
        // Untuk membedakan KTP/Ijazah dll di view, diperlukan sedikit trik.
        $buktiDokumen = BuktiDasar::where('id_data_sertifikasi_asesi', $id_data_sertifikasi_asesi ?? 0)
                                ->get();
        
        // Kita kirimkan semua data dan ID ke view
        return view('frontend.PORTOFOLIO', compact('asesi', 'buktiDokumen', 'id_data_sertifikasi_asesi'));
    }

    /**
     * Memproses upload file dan menyimpan path ke tabel bukti_dasar.
     */
    public function store(Request $request)
    {
        // 1. Validasi File Upload
        // Kita tidak bisa menggunakan 'required' karena user mungkin hanya mengupdate 1 file
        $request->validate([
            'ktp' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048', 
            'ijazah' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048', 
            // ... (validasi lainnya)
        ]);
        
        // **ASUMSI PENTING**: Mendapatkan id_data_sertifikasi_asesi yang VALID
        $asesi = Asesi::find(1);
        if (!$asesi) {
            return redirect()->route('PORTOFOLIO')->with('error', 'Asesi tidak ditemukan.');
        }

        $dataSertifikasi = DataSertifikasiAsesi::where('id_asesi', $asesi->id_asesi)->first();
        if (!$dataSertifikasi) {
            return redirect()->route('PORTOFOLIO')->with('error', 'Data Sertifikasi Asesi tidak ditemukan untuk Asesi ini. Pastikan tabel data_sertifikasi_asesi memiliki relasi ke Asesi ID 1.');
        }

        $id_data_sertifikasi_asesi = $dataSertifikasi->id_data_sertifikasi_asesi;

        // 2. Definisi semua field file yang akan diproses
        // Tipe dokumen ini HANYA digunakan untuk penamaan file di storage, bukan di DB
        $fileFields = [
            'ktp', 'ijazah', 'pendukung_dasar', 
            'bukti_pembayaran', 'formulir_pendaftaran', 'cv', 'pendukung_admin'
        ];

        $uploadedCount = 0;

        try {
            foreach ($fileFields as $fieldName) {
                if ($request->hasFile($fieldName)) {
                    
                    $file = $request->file($fieldName);
                    
                    $extension = $file->getClientOriginalExtension();
                    
                    // Membuat nama file unik berdasarkan TIPE DOKUMEN (untuk identifikasi mudah di storage)
                    $fileNameToStore = $id_data_sertifikasi_asesi . '_' . strtoupper($fieldName) . '_' . time() . '.' . $extension;
                    
                    // Menyimpan file ke storage
                    $path = $file->storeAs('public/portofolio', $fileNameToStore);
                    
                    // Path yang akan disimpan di DB
                    $file_path_db = 'portofolio/' . $fileNameToStore;
                    
                    // 3. Simpan baris BARU ke Database
                    // KARENA TIDAK ADA KOLOM TIPE, kita selalu INSERT BARU (ini kelemahan solusi tanpa perubahan migration)
                    BuktiDasar::create([
                        'id_data_sertifikasi_asesi' => $id_data_sertifikasi_asesi,
                        // Kolom ini akan menyimpan nama file/path
                        'bukti_dasar' => $file_path_db, 
                        'status_kelengkapan' => 'memenuhi', // Kalo di-upload, statusnya memenuhi
                        'status_validasi' => false,
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                    ]);

                    $uploadedCount++;
                }
            }
            
            if ($uploadedCount > 0) {
                return redirect()->route('PORTOFOLIO')->with('success', $uploadedCount . ' dokumen Portofolio berhasil diunggah dan disimpan!');
            } else {
                 return redirect()->route('PORTOFOLIO')->with('info', 'Tidak ada dokumen baru yang diunggah.');
            }

        } catch (\Exception $e) {
            // Jika terjadi error DB (misal FK constraint)
            return redirect()->route('PORTOFOLIO')->with('error', 'Gagal menyimpan data ke database. Cek relasi ID Sertifikasi Asesi Anda. Pesan Error: ' . $e->getMessage());
        }
    }

    /**
     * Logika untuk membatalkan/menghapus file yang sudah ada dari database dan storage.
     * Ini memerlukan route POST/DELETE terpisah.
     */
    public function destroyBukti(Request $request)
    {
        // Ambil ID Bukti Dasar dari form (hidden input di form view)
        $id_bukti_dasar = $request->input('id_bukti_dasar_to_delete');
        
        $bukti = BuktiDasar::find($id_bukti_dasar);

        if (!$bukti) {
            return redirect()->back()->with('error', 'Dokumen tidak ditemukan.');
        }
        
        $filePath = $bukti->bukti_dasar;

        // Hapus entri dari database
        $bukti->delete();

        // Hapus file fisik dari storage
        if (Storage::exists('public/' . $filePath)) {
            Storage::delete('public/' . $filePath);
        }

        return redirect()->back()->with('success', 'Dokumen berhasil dibatalkan dan dihapus.');
    }
}
<?php

namespace App\Http\Controllers\Asesi\IA02;

use Carbon\Carbon;
use App\Models\Ia02;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\DataSertifikasiAsesi;
use Illuminate\Support\Facades\Validator;

class Ia02Controller extends Controller
{
    /**
     * ============================================================
     * [WEB] Menampilkan halaman IA.02 (ASESI - READ ONLY)
     * ============================================================
     */
   public function index($id_data_sertifikasi_asesi)
    {
        // 1. Ambil data lengkap sertifikasi asesi + relasi-relasi yang dibutuhkan
        // KITA HAPUS BLOK TRY-CATCH DAN BIARKAN LARAVEL HANDLE findOrFail(404)
        $dataSertifikasi = DataSertifikasiAsesi::with([
            'asesi:id_asesi,nama_lengkap,tanda_tangan',
            'jadwal.asesor:id_asesor,nama_lengkap,nomor_regis,tanda_tangan',
            'jadwal.skema:id_skema,nama_skema,nomor_skema,gambar',
            'jadwal.skema.unitKompetensi',
            'jadwal.jenisTuk:id_jenis_tuk,jenis_tuk',
            'ia02:id_ia02,id_data_sertifikasi_asesi,skenario,peralatan,waktu',
            'jadwal:id_jadwal,id_asesor,id_skema,id_jenis_tuk,tanggal_pelaksanaan',
        ])->findOrFail($id_data_sertifikasi_asesi); // Akan throw 404 jika ID tidak ada

        // 2. Persiapan data untuk view
        $daftarUnitKompetensi = $dataSertifikasi->jadwal?->skema?->unitKompetensi ?? collect();
        $ia02 = $dataSertifikasi->ia02->first(); 
        
        // 3. Tambahkan Variabel $asesi
        // Variabel ini diperlukan view (lihat snippet IA_02.blade.php Anda: $asesi->id_asesi)
        $asesi = $dataSertifikasi->asesi;

        // 4. Periksa variabel yang hilang yang menyebabkan error (misalnya $isAdmin)
        // Jika view Anda memanggil $isAdmin, tentukan nilainya di sini:
        // $isAdmin = auth()->check() ? auth()->user()->isAdmin() : false; 
        // Anda mungkin perlu menambahkan variabel lain yang hilang di sini

        return view('asesi.IA_02.IA_02', [
            'sertifikasi' => $dataSertifikasi,
            'asesi' => $asesi, // Tambahkan variabel asesi
            'daftarUnitKompetensi' => $daftarUnitKompetensi,
            'ia02' => $ia02,
            // Jika ada variabel lain yang dibutuhkan view (misalnya $isAdmin), tambahkan di sini.
        ]);

    }
    /**
     * ============================================================
     * [WEB] Menyimpan/Memperbarui data IA.02 (ASESOR - FORM SUBMISSION)
     * ============================================================
     */
    public function store(Request $request, $id_data_sertifikasi_asesi)
    {
        // 1. Validasi
        $validator = Validator::make($request->all(), [
            'skenario' => 'required|string',
            'peralatan' => 'required|string',
            'waktu' => 'required|date_format:H:i', // Format waktu HH:ii
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput()->with('error', 'Gagal menyimpan: Harap isi semua kolom wajib.');
        }

        try {
            // Ambil DataSertifikasiAsesi untuk relasi
            $dataSertifikasi = DataSertifikasiAsesi::findOrFail($id_data_sertifikasi_asesi);
            
            // Karena IA.02 adalah one-to-many, kita asumsikan Asesor akan menambahkan skenario baru setiap kali submit.
            // Jika tujuannya hanya memperbarui skenario terakhir/tertentu, logikanya perlu disesuaikan.
            // Untuk skenario multi, Asesor idealnya menggunakan form array input.
            
            // Logika Sederhana: Buat record IA02 baru
            Ia02::create([
                'id_data_sertifikasi_asesi' => $id_data_sertifikasi_asesi,
                'skenario' => $request->skenario,
                'peralatan' => $request->peralatan,
                // Tambahkan detik (":00") agar sesuai dengan tipe TIME di database (H:i:s)
                'waktu' => $request->waktu . ':00', 
            ]);

            return back()->with('success', 'Instruksi Tugas Praktik (IA.02) berhasil disimpan/diperbarui.');

        } catch (\Exception $e) {
            Log::error('Gagal menyimpan IA02: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Gagal menyimpan data IA.02: ' . $e->getMessage());
        }
    }

    /**
     * ============================================================
     * [API] Mengambil data IA.02 (JSON) untuk di-load via JS di Blade
     * ============================================================
     */
    public function apiDetail($id_data_sertifikasi_asesi)
    {
        // Kode ini sudah ada di snippet Anda, saya hanya memastikan strukturnya benar.
        try {
            $dataSertifikasi = DataSertifikasiAsesi::with([
                'asesi:id_asesi,nama_lengkap,tanda_tangan',
                'jadwal.asesor:id_asesor,nama_lengkap,nomor_regis,tanda_tangan',
                'jadwal.skema:id_skema,nama_skema,nomor_skema',
                'jadwal.jenisTuk:id_jenis_tuk,jenis_tuk',
                'ia02', // Ambil semua record IA02
            ])->findOrFail($id_data_sertifikasi_asesi);

            $tanggalAssesmen = Carbon::parse($dataSertifikasi->jadwal->tanggal_assesmen)->isoFormat('D MMMM YYYY');
            $jenisTukName = $dataSertifikasi->jadwal->jenisTuk->jenis_tuk ?? '-';

            return response()->json([
                'success' => true,
                'data' => [
                    'asesi' => [
                        'nama_lengkap' => $dataSertifikasi->asesi->nama_lengkap,
                        'tanda_tangan' => $dataSertifikasi->asesi->tanda_tangan,
                    ],
                    'asesor' => [
                        'nama_lengkap' => $dataSertifikasi->jadwal->asesor->nama_lengkap ?? '-',
                        'nomor_regis' => $dataSertifikasi->jadwal->asesor->nomor_regis ?? '-',
                        'tanda_tangan' => $dataSertifikasi->jadwal->asesor->tanda_tangan ?? '-',
                    ],
                    'skema' => $dataSertifikasi->jadwal->skema,
                    'tanggal_assesmen' => $tanggalAssesmen,
                    'tuk' => $jenisTukName,
                    'ia02' => $dataSertifikasi->ia02, // Data record IA02 untuk di-render JS
                ],
            ], 200);

        } catch (\Exception $e) {
            Log::error('API IA02 GAGAL: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat data IA02: ' . $e->getMessage(),
            ], 500);
        }
    }

    // Metode 'next' yang ada di snippet Anda
    public function next($id_data_sertifikasi_asesi)
    {
        // Ini adalah tombol navigasi, fungsinya hanya redirect ke IA03
        return redirect()->route('ia03.index', $id_data_sertifikasi_asesi);
    }
}
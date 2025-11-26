<?php

namespace App\Http\Controllers\Ak04API; // Pastikan namespace Anda benar

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\DataSertifikasiAsesi;
use App\Models\ResponAk04;
use Illuminate\Support\Facades\Log; // Gunakan Log jika diperlukan untuk debugging

class APIBandingController extends Controller
{
    /**
     * [WEB] Menampilkan View Banding (AK.04) (Method show di controller web/view).
     */
    public function show(string $id_sertifikasi)
    {
        try {
            // 1. Ambil Data Sertifikasi DENGAN RELASI yang diperlukan
            $dataSertifikasi = DataSertifikasiAsesi::with(['asesi', 'jadwal.asesor'])->findOrFail($id_sertifikasi);

            // --- Ekstraksi data untuk Sidebar ---

            // 2. Ekstrak data Asesor
            // Asumsi: relasi jadwal->asesor mengembalikan objek Asesor
            $asesor = $dataSertifikasi->jadwal->asesor;
            
            // Siapkan array Asesor sesuai format yang diharapkan oleh x-sidebar2
            $asesorData = [
                'nama'   => $asesor->nama_lengkap ?? 'Nama Asesor Tidak Tersedia', // Asumsi field di model Asesor
                'no_reg' => $asesor->no_reg ?? '-', // Asumsi field di model Asesor
            ];

            // 3. Ekstrak id Asesi
            $idAsesi = $dataSertifikasi->asesi->id_asesi; // Asumsi field di model Asesi
            
            // --- Kirim data ke View ---
            
            return view('banding.banding', [ // Sesuaikan path view Blade Anda
                'id_sertifikasi' => $id_sertifikasi,
                'sertifikasi'    => $dataSertifikasi,
                // VARIABEL HILANG YANG DIPERLUKAN SIDEBAR (WAJIB ADA)
                'asesor'         => $asesorData,
                'idAsesi'        => $idAsesi, 
            ]);
            
        } catch (\Exception $e) {
            // Log::error("Error loading Banding view: " . $e->getMessage()); // Opsional untuk debugging
            return redirect('/tracker')->with('error', 'Data Sertifikasi tidak ditemukan untuk Banding.')->with('exception', $e->getMessage());
        }
    }

    /**
     * [API GET] Mengambil Data Read-Only (TUK, Asesor, Asesi, Skema) dan Respon Banding lama.
     * Penamaan disesuaikan dengan pola getFrAk01Data.
     */
    public function getBandingData(string $id_sertifikasi)
    {
        try {
            // Panggil data dengan nested eager loading
            $data = DataSertifikasiAsesi::with([
                'asesi:id_asesi,nama_lengkap,tanda_tangan',
                'jadwal.asesor:id_asesor,nama_lengkap',
                'jadwal.skema:id_skema,nama_skema,nomor_skema',
                'jadwal.jenisTuk:id_jenis_tuk,jenis_tuk', // <--- KRITIS: Relasi JenisTuk
                'responAk04' => function($query) {
                    $query->latest()->limit(1); // Ambil respon banding terbaru
                },
            ])
            // Select hanya kolom yang ada di tabel data_sertifikasi_asesi
            ->select('id_data_sertifikasi_asesi', 'id_asesi', 'id_jadwal', 'status_sertifikasi')
            ->findOrFail($id_sertifikasi);

            
            // KRITIS: Ambil nama TUK dari relasi bertingkat
            // Fallback ke 'Sewaktu' jika data TUK di jadwal belum diatur
            $jenisTukName = $data->jadwal->jenisTuk->jenis_tuk ?? 'Sewaktu';

            $asesor = $data->jadwal->asesor;

            return response()->json([
                'success' => true,
                'data' => [
                    'id_sertifikasi' => $data->id_data_sertifikasi_asesi,
                    'tuk_lokasi' => $jenisTukName, // <--- Data TUK sudah terambil dari relasi
                    'asesi' => [
                        'nama_lengkap' => $data->asesi->nama_lengkap,
                        'tanda_tangan' => $data->asesi->tanda_tangan,
                    ],
                    'asesor' => [
                        'nama_lengkap' => $asesor->nama_lengkap ?? '-',
                    ],
                    'jadwal' => [
                        'id_jadwal' => $data->jadwal->id_jadwal,
                        'skema' => [
                            'nama_skema' => $data->jadwal->skema->nama_skema ?? '-',
                            'nomor_skema' => $data->jadwal->skema->nomor_skema ?? '-',
                        ]
                    ],
                    'respon_ak04' => $data->responAk04->first(), 
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * [API POST] Menyimpan Respon Banding (AK.04) dan update status sertifikasi.
     * Penamaan disesuaikan dengan pola simpanPersetujuan.
     */
    public function simpanBanding(Request $request, $id_sertifikasi)
    {
        $request->validate([
            'id_data_sertifikasi_asesi' => 'required|integer|in:' . $id_sertifikasi,
            'penjelasan_banding' => 'required|boolean', 
            'diskusi_dengan_asesor' => 'required|boolean',
            'melibatkan_orang_lain' => 'required|boolean',
            'alasan_banding' => 'required|string|max:1000',
        ]);

        try {
            DB::beginTransaction();
            
            // 1. Simpan record BARU Respon Ak04
            $dataAk04 = ResponAk04::create($request->all());

            // 2. Update status sertifikasi ke "BANDING_SELESAI"
            // Ambil data sertifikasi dari relasi Model ResponAk04
            $sertifikasi = $dataAk04->dataSertifikasiAsesi; 
            
            // Ganti 'BANDING_SELESAI' dengan konstanta status yang Anda gunakan (jika ada)
            $sertifikasi->status_sertifikasi = 'BANDING_SELESAI'; 
            $sertifikasi->save();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Data Banding berhasil disimpan.',
                // KRITIS: Kirim id_jadwal untuk redirect ke tracker
                'id_jadwal' => $sertifikasi->id_jadwal 
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error Simpan Banding: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan data banding: Terjadi kesalahan server.'
            ], 500);
        }
    }
}
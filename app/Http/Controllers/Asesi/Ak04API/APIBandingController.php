<?php

namespace App\Http\Controllers\Asesi\Ak04API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\DataSertifikasiAsesi;
use App\Models\ResponAk04;
use Illuminate\Support\Facades\Log;
// [TAMBAHAN PENTING] Import Auth
use Illuminate\Support\Facades\Auth;

class APIBandingController extends Controller
{
    /**
     * [WEB] Menampilkan View Banding (AK.04).
     * Di sini kita cek dulu, kalau sudah pernah isi, jangan tampilkan formnya.
     */
    public function show(string $id_sertifikasi)
    {
        $user = Auth::user();
        $asesi = $user->asesi;

        if (!$asesi) {
            return redirect('/tracker')->with('error', 'Akses ditolak.');
        }

        try {
            // 1. [PINDAH KE ATAS] Ambil Data Sertifikasi Lengkap Dulu
            // Kita butuh data ini untuk Sidebar di halaman 'berhasil' maupun halaman 'form'
            $dataSertifikasi = DataSertifikasiAsesi::with([
                    'asesi', 
                    'jadwal.asesor', 
                    'jadwal.skema',    // Penting buat sidebar/header
                    'jadwal.jenisTuk'
                ])
                ->where('id_asesi', $asesi->id_asesi) // Cek kepemilikan
                ->findOrFail($id_sertifikasi);

            // 2. Cek apakah sudah pernah mengisi banding
            $sudahIsi = ResponAk04::where('id_data_sertifikasi_asesi', $id_sertifikasi)->exists();

            if ($sudahIsi) {
                // Return view 'berhasil' dengan DATA LENGKAP
                return view('asesi.tunggu_or_berhasil.berhasil', [
                    'id_sertifikasi'     => $id_sertifikasi,
                    'id_jadwal_redirect' => $dataSertifikasi->id_jadwal,
                    'asesi'              => $asesi,            // <--- INI SOLUSI ERRORNYA
                    'sertifikasi'        => $dataSertifikasi   // <--- INI JUGA WAJIB
                ]); 
            }

            // 3. Jika BELUM mengisi, siapkan data untuk Form Banding
            $asesor = $dataSertifikasi->jadwal->asesor;
            
            $asesorData = [
                'nama'   => $asesor->nama_lengkap ?? 'Nama Asesor Tidak Tersedia',
                'no_reg' => $asesor->no_reg ?? '-',
            ];

            return view('asesi.banding.banding', [
                'id_sertifikasi' => $id_sertifikasi,
                'sertifikasi'    => $dataSertifikasi,
                'asesor'         => $asesorData,
                'idAsesi'        => $asesi->id_asesi,
                'asesi'          => $asesi,
            ]);
            
        } catch (\Exception $e) {
            return redirect('/asesi/tracker')->with('error', 'Data Sertifikasi tidak ditemukan atau Anda tidak memiliki akses.');
        }
    }

    /**
     * [API GET] Mengambil Data Read-Only.
     * Karena pengecekan "sudah isi" sudah dilakukan di method show(),
     * method ini bisa dikembalikan seperti semula (fokus hanya mengambil data).
     */
    public function getBandingData(string $id_sertifikasi)
    {
        try {
            // Panggil data dengan nested eager loading
            $data = DataSertifikasiAsesi::with([
                'asesi:id_asesi,nama_lengkap,tanda_tangan',
                'jadwal.asesor:id_asesor,nama_lengkap',
                'jadwal.skema:id_skema,nama_skema,nomor_skema',
                'jadwal.jenisTuk:id_jenis_tuk,jenis_tuk',
                // Kita tetap load responnya jika ada, tapi tidak krusial lagi untuk logika 'sekali isi'
                'responAk04' => function($query) {
                    $query->latest()->limit(1); 
                },
            ])
            ->select('id_data_sertifikasi_asesi', 'id_asesi', 'id_jadwal', 'status_sertifikasi')
            ->findOrFail($id_sertifikasi);

            
            $jenisTukName = $data->jadwal->jenisTuk->jenis_tuk ?? 'Sewaktu';
            $asesor = $data->jadwal->asesor;

            return response()->json([
                'success' => true,
                'data' => [
                    'id_sertifikasi' => $data->id_data_sertifikasi_asesi,
                    'tuk_lokasi' => $jenisTukName,
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
     * [API POST] Menyimpan Respon Banding (AK.04).
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
            // [TETAP PERTAHANKAN INI] Cek Keamanan di Backend (Double Submit Prevention)
            // Ini penting jika ada yang mencoba "nembak" API langsung tanpa lewat halaman web
            $sudahAda = ResponAk04::where('id_data_sertifikasi_asesi', $id_sertifikasi)->exists();
            if ($sudahAda) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda sudah pernah mengajukan banding. Data tidak dapat dikirim ulang.',
                ], 409);
            }

            DB::beginTransaction();
            
            // 1. Simpan record BARU Respon Ak04
            $dataAk04 = ResponAk04::create($request->all());

            // 2. Update status sertifikasi
            $sertifikasi = $dataAk04->dataSertifikasiAsesi; 
            
            // [REVISI STATUS] Gunakan huruf kecil sesuai request sebelumnya
            $sertifikasi->status_sertifikasi = 'banding_selesai'; 
            $sertifikasi->save();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Data Banding berhasil disimpan.',
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
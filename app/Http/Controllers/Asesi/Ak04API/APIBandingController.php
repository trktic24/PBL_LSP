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
        // [TAMBAHAN KEAMANAN] Ambil asesi yang sedang login
        $user = Auth::user();
        $asesi = $user->asesi;

        if (!$asesi) {
             // Redirect atau error jika yang akses bukan asesi
            return redirect('/tracker')->with('error', 'Akses ditolak.');
        }

        try {
            // ------------------------------------------------------------------
            // [REVISI UTAMA DI SINI]
            // Cek apakah sudah pernah mengisi banding untuk sertifikasi ini
            // ------------------------------------------------------------------
            $sudahIsi = ResponAk04::where('id_data_sertifikasi_asesi', $id_sertifikasi)->exists();

            if ($sudahIsi) {
                // Jika sudah, langsung tampilkan halaman universal "Sudah Mengisi"
                // Kita gunakan view yang sama dengan umpan balik seperti permintaan
                return view('asesi.tunggu_or_berhasil.berhasil', [
                    'id_sertifikasi' => $id_sertifikasi,
                    // Opsional: bisa kirim variabel judul agar halaman universalnya lebih spesifik
                    // 'page_title' => 'Banding Asesmen' 
                ]); 
            }
            // ------------------------------------------------------------------

            // Jika BELUM mengisi, baru kita lanjut ambil data untuk menampilkan form

            // [TAMBAHAN KEAMANAN] Pastikan mengambil data milik asesi yang login saja
            $dataSertifikasi = DataSertifikasiAsesi::with(['asesi', 'jadwal.asesor'])
                ->where('id_asesi', $asesi->id_asesi) // Cek kepemilikan
                ->findOrFail($id_sertifikasi);

            // --- Ekstraksi data untuk Sidebar (Kode Asli) ---
            $asesor = $dataSertifikasi->jadwal->asesor;
            // $asesi = $dataSertifikasi->asesi; // Tidak perlu lagi karena sudah ada $asesi di atas
            
            $asesorData = [
                'nama'   => $asesor->nama_lengkap ?? 'Nama Asesor Tidak Tersedia',
                'no_reg' => $asesor->no_reg ?? '-',
            ];

            $idAsesi = $asesi->id_asesi;
            
            // --- Kirim data ke View Form Banding ---
            return view('asesi.banding.banding', [
                'id_sertifikasi' => $id_sertifikasi,
                'sertifikasi'    => $dataSertifikasi,
                'asesor'         => $asesorData,
                'idAsesi'        => $idAsesi,
                'asesi'          => $asesi,
            ]);
            
        } catch (\Exception $e) {
            // Log::error(...)
            // Jika data tidak ditemukan atau bukan miliknya, lempar ke tracker
            return redirect('/tracker')->with('error', 'Data Sertifikasi tidak ditemukan atau Anda tidak memiliki akses.');
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
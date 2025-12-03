<?php

namespace App\Http\Controllers;

use App\Models\DataSertifikasiAsesi;
use App\Models\Ia02;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class Ia02Controller extends Controller
{
    /**
     * ============================================================
     * [WEB] Menampilkan halaman IA.02 (ASESI - READ ONLY)
     * ============================================================
     */
    public function index($id_data_sertifikasi_asesi)
    {
        // Ambil data lengkap sertifikasi asesi + relasi-relasi yang dibutuhkan
        try {
            $dataSertifikasi = DataSertifikasiAsesi::with([
                'asesi:id_asesi,nama_lengkap,tanda_tangan',
                // Ambil 'no_reg' Asesor jika dibutuhkan oleh sidebar/view
                'jadwal.asesor:id_asesor,nama_lengkap,nomor_regis,', 
                'jadwal.skema:id_skema,nama_skema,nomor_skema',
                // KRITIS: Tambahkan relasi unitKompetensi agar data terambil
                'jadwal.skema.unitKompetensi', 
                'jadwal.jenisTuk:id_jenis_tuk,jenis_tuk',
                'ia02:id_ia02,id_data_sertifikasi_asesi,skenario,peralatan,waktu',// Data IA.02 untuk pre-load atau pengecekan
                'jadwal:id_jadwal,id_asesor,id_skema,id_jenis_tuk,tanggal_assesmen'
            ])
            ->select('id_data_sertifikasi_asesi', 'id_asesi', 'id_jadwal', 'status_sertifikasi')
            ->findOrFail($id_data_sertifikasi_asesi);
            
            // --- Siapkan Data Asesor dalam bentuk array (Seperti BandingController) ---
            $asesor = $dataSertifikasi->jadwal->asesor;
            $asesorData = [
                'nama'   => $asesor->nama_lengkap ?? 'Nama Asesor Tidak Tersedia',
                'no_reg' => $asesor->nomor_regis ?? '-', 
            ];

            // --- Kirim data ke View ---
            return view('IA_02.IA_02', [
                'asesi'                 => $dataSertifikasi->asesi,
                'asesor'                => $asesorData, // Menggunakan format array asesor
                'skema'                 => $dataSertifikasi->jadwal->skema,
                'sertifikasi'           => $dataSertifikasi, 
                'idAsesi'               => $dataSertifikasi->id_asesi,
                'idSertifikasi'         => $id_data_sertifikasi_asesi,
                'ia02Records'           => $dataSertifikasi->ia02, 
                // Relasi ini sekarang dijamin sudah dimuat (eager loaded)
                'daftarUnitKompetensi'  => $dataSertifikasi->jadwal->skema->unitKompetensi,
                'jenisTuk'              => $dataSertifikasi->jadwal->jenisTuk->jenis_tuk ?? 'Sewaktu',
                'tanggalAssesmen'       => $dataSertifikasi->jadwal->tanggal_assesmen ?? '-',
            ]);

        } catch (\Exception $e) {
            Log::error('Gagal Memuat View IA02: ' . $e->getMessage());
            // Handle jika data sertifikasi tidak ditemukan
            // Anda bisa ganti redirect ini sesuai kebutuhan aplikasi Anda
            return redirect('/')->with('error', 'Data Sertifikasi IA.02 tidak ditemukan.')->with('exception', $e->getMessage());
        }
    }


    /**
     * ============================================================
     * [API - GET] Ambil data IA.02 untuk frontend JS
     * ============================================================
        */
   public function getData($id_data_sertifikasi_asesi)
    {
        try {
            // Ambil data sertifikasi LENGKAP dengan semua relasi yang dibutuhkan JS
            $dataSertifikasi = DataSertifikasiAsesi::with([
                'asesi:id_asesi,nama_lengkap,tanda_tangan', // Tanda tangan Asesi
                'jadwal.asesor:id_asesor,nama_lengkap,nomor_regis,tanda_tangan', // No regis dan Tanda tangan Asesor
                'jadwal.skema:id_skema,nama_skema,nomor_skema',
                'jadwal.jenisTuk:id_jenis_tuk,jenis_tuk',
                'ia02:id_ia02,id_data_sertifikasi_asesi,skenario,peralatan,waktu',
                'jadwal:id_jadwal,id_asesor,id_skema,id_jenis_tuk,tanggal_assesmen' // Tanggal asesmen
            ])
            ->select('id_data_sertifikasi_asesi', 'id_asesi', 'id_jadwal')
            ->findOrFail($id_data_sertifikasi_asesi);

            // Tentukan nama TUK dan tanggal assesmen
            $jenisTukName = $dataSertifikasi->jadwal->jenisTuk->jenis_tuk ?? 'Sewaktu';
            $tanggalAssesmen = $dataSertifikasi->jadwal->tanggal_assesmen ?? null;

            // Mengembalikan data sesuai struktur yang Diharapkan oleh JavaScript
            // Catatan: JavaScript Anda mengharapkan data asesor dan asesi sebagai objek tunggal
            return response()->json([
                'success' => true,
                'data' => [
                    // Data Asesi
                    'asesi' => [
                        'nama_lengkap' => $dataSertifikasi->asesi->nama_lengkap,
                        'tanda_tangan' => $dataSertifikasi->asesi->tanda_tangan,
                    ],
                    // Data Asesor
                    'asesor' => [
                        'nama_lengkap' => $dataSertifikasi->jadwal->asesor->nama_lengkap ?? '-',
                        'nomor_regis' => $dataSertifikasi->jadwal->asesor->nomor_regis ?? '-',
                        'tanda_tangan' => $dataSertifikasi->jadwal->asesor->tanda_tangan ?? '-',
                    ],
                    'skema' => $dataSertifikasi->jadwal->skema,
                    'tanggal_assesmen' => $tanggalAssesmen,
                    'tuk' => $jenisTukName, // Digunakan untuk radio button
                    'ia02' => $dataSertifikasi->ia02, // Data record IA02
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
    /**
     * ============================================================
     * [WEB] Tombol "Selanjutnya" → redirect ke IA03
     * ============================================================
     */
    public function next($id_data_sertifikasi_asesi)
    {
        return redirect()->route('ia03.index', $id_data_sertifikasi_asesi);
    }
}
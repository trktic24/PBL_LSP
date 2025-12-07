<?php

namespace App\Http\Controllers\Asesi\JadwalTukAPI;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\DataSertifikasiAsesi;

class JadwalTukAPIController extends Controller
{
    /**
     * METHOD WEB: Menampilkan Halaman View (Blade)
     */
    public function show($id_sertifikasi)
    {
        // try {
            // Cek data (Validasi ID)
            $sertifikasi = DataSertifikasiAsesi::with([
                'asesi', 
                'jadwal.skema', 
                'jadwal.asesor'
            ])->findOrFail($id_sertifikasi);
            
            // Return View
            return view('asesi.jadwal_dan_tuk.jadwal_dan_tuk', [
                'id_sertifikasi' => $id_sertifikasi,
                'asesi'          => $sertifikasi->asesi,
                'sertifikasi'    => $sertifikasi
            ]);
        // }
        // } catch (\Exception $e) {
        //     return redirect('/tracker')->with('error', 'Data Pendaftaran tidak ditemukan.');
        // }
    }   

    /**
     * METHOD API: Ambil Data JSON untuk JavaScript
     */
    public function getJadwalData($id_sertifikasi)
    {
        Log::info("API Jadwal TUK: Mengambil data untuk Sertifikasi ID $id_sertifikasi");
        
        try {
            $sertifikasi = DataSertifikasiAsesi::with([
                'jadwal.tuk',
                'jadwal.jenisTuk'
            ])->findOrFail($id_sertifikasi);

            $jadwal = $sertifikasi->jadwal;
            
            if (!$jadwal) {
                return response()->json(['success' => false, 'message' => 'Data Jadwal belum ditentukan.'], 404);
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'jenis_tuk' => $jadwal->jenisTuk->jenis_tuk ?? 'Sewaktu',

                    'lokasi'      => $jadwal->tuk->nama_lokasi ?? '-',
                    'alamat'      => $jadwal->tuk->alamat_tuk ?? '-',
                    'kontak'      => $jadwal->tuk->kontak_tuk ?? '-',
                    
                    // [PERBAIKAN] Ambil langsung dari kolom 'foto_tuk' di database
                    // Asumsi di database isinya: "gedung1.jpg"
                    // Outputnya jadi: "images/gedung1.jpg"
                    'foto_gedung' => 'images/' . ($jadwal->tuk->foto_tuk ?? 'default.jpg'), 

                    'link_gmap'   => $jadwal->tuk->link_gmap ?? null,

                    'tanggal' => $jadwal->tanggal_pelaksanaan, 
                    'waktu'   => $jadwal->waktu_mulai, 
                    
                    'status_sertifikasi' => $sertifikasi->status_sertifikasi,
                ]
            ], 200);

        } catch (\Exception $e) {
            Log::error("API Jadwal Error: " . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Gagal mengambil data jadwal.'], 500);
        }
    }
    
    /**
     * METHOD POST: Konfirmasi / Lanjut (Cuma Update Status)
     */
    public function konfirmasiJadwal(Request $request, $id_sertifikasi)
    {
        try {
            $sertifikasi = DataSertifikasiAsesi::findOrFail($id_sertifikasi);
            
            // Update Status (Naik Level ke Pra-Asesmen)
            // Dari 'verifikasi_tuk_selesai' (kalau ada status ini) -> 'pra_asesmen_siap'
            // Atau biarkan saja statusnya tetap, karena halaman ini lebih ke Informasi.
            
            // Kita kembalikan ID Jadwal biar bisa redirect
            return response()->json([
                'success' => true, 
                'message' => 'Jadwal dikonfirmasi.',
                'id_jadwal' => $sertifikasi->id_jadwal
            ]);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DataSertifikasiAsesi;

class FrAk01Controller extends Controller
{
    /**
     * Menampilkan Form FR.AK.01 Khusus Asesor
     */
    public function index($id_sertifikasi)
    {
        // 1. Ambil Data (Sistematika sama seperti sebelumnya)
        $sertifikasi = DataSertifikasiAsesi::with([
            'jadwal.asesor', 
            'asesi'
        ])->findOrFail($id_sertifikasi);

        $asesi = $sertifikasi->asesi;

        // 2. Return ke View Baru (frontend.asesor.FR_AK_01_Asesor)
        // Pastikan Anda membuat folder 'asesor' di dalam resources/views/frontend/
        return view('frontend.FR_AK_01_Asesor', compact('sertifikasi', 'asesi'));
    }

    /**
     * Menyimpan Data FR.AK.01 (Aksi oleh Asesor)
     */
    public function store(Request $request, $id_sertifikasi)
    {
        $sertifikasi = DataSertifikasiAsesi::findOrFail($id_sertifikasi);

        // 1. Ambil Input Checkbox
        $bukti = $request->input('bukti_kelengkapan', []);

        // 2. Logika "Lainnya" (Jika dicentang, ambil teksnya)
        if (in_array('Lainnya', $bukti)) {
            $textLainnya = $request->input('bukti_lainnya_text');
            
            // Hapus kata "Lainnya" dari array
            $bukti = array_diff($bukti, ['Lainnya']);
            
            // Masukkan teks ketikan user
            if (!empty($textLainnya)) {
                array_push($bukti, $textLainnya);
            }
        }

        // 3. Update Data ke Database
        // Simpan Bukti (Array -> String)
        $sertifikasi->respon_bukti_ak01 = implode(', ', $bukti);

        // Simpan Jenis TUK (Jika Asesor mengubahnya)
        if ($request->has('jenis_tuk')) {
            // Opsional: Jika Anda ingin menyimpan perubahan TUK ke tabel jadwal, 
            // tambahkan logika update jadwal di sini.
            // Saat ini kita fokus simpan bukti AK.01 saja.
        }

        $sertifikasi->save();

        return redirect()->back()->with('success', 'FR.AK.01 Berhasil Disimpan oleh Asesor.');
    }
}
<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DataSertifikasiAsesi;
use App\Models\ResponBuktiAk01;
use App\Models\BuktiAk01;
use Illuminate\Support\Facades\DB; // Wajib import ini

class FrAk01Controller extends Controller
{
    public function index($id_sertifikasi)
    {
        $sertifikasi = DataSertifikasiAsesi::with([
            'jadwal.asesor',
            'jadwal.masterTuk',
            'asesi',
            'responbuktiAk01' 
        ])->findOrFail($id_sertifikasi);

        $asesi = $sertifikasi->asesi;
        $masterBukti = BuktiAk01::all();

        // Sesuaikan folder view Anda jika ada di folder 'asesor'
        return view('frontend.FR_AK_01_Asesor', compact('sertifikasi', 'asesi', 'masterBukti'));
    }

    public function store(Request $request, $id_sertifikasi)
    {
        DB::beginTransaction();
        try {
            // 1. Ambil Input
            $selectedIds = $request->input('bukti_kelengkapan', []);
            $textLainnya = $request->input('bukti_lainnya_text');

            // 2. Reset Data Lama (Respon Bukti)
            ResponBuktiAk01::where('id_data_sertifikasi_asesi', $id_sertifikasi)->delete();

            // 3. Simpan Data Baru (Respon Bukti)
            foreach ($selectedIds as $buktiId) {
                $data = [
                    'id_data_sertifikasi_asesi' => $id_sertifikasi,
                    'id_bukti_ak01' => $buktiId,
                    'respon' => null
                ];

                if ($buktiId == 9) { 
                    $data['respon'] = $textLainnya;
                }

                ResponBuktiAk01::create($data);
            }

            // ========================================================
            // 4. [BARU] UPDATE STATUS REKOMENDASI JADI 'DITERIMA'
            // ========================================================
            // Kita cari data sertifikasi, lalu update kolom rekomendasi_ak01
            $sertifikasi = DataSertifikasiAsesi::findOrFail($id_sertifikasi);
            $sertifikasi->rekomendasi_ak01 = 'diterima';
            $sertifikasi->save();
            // ========================================================

            // 5. Simpan Permanen
            DB::commit(); 

            // Redirect Back agar Popup muncul
            return redirect()->back()->with('success', 'Data FR.AK.01 Berhasil Disimpan & Status Diterima.'); 

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal menyimpan: ' . $e->getMessage());
        }
    }
}
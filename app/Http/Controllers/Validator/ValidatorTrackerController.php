<?php

namespace App\Http\Controllers\Validator;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DataSertifikasiAsesi;

class ValidatorTrackerController extends Controller
{
    // 1. Menampilkan Tracker Mode "Read-Only"
    public function show($id)
    {
        // 1. Ambil data sertifikasi beserta relasinya
        $dataSertifikasi = DataSertifikasiAsesi::with([
            'asesi.user', 
            'jadwal.skema', // Penting: load jadwal
            'ia10', 'ia02', 'ia07', 'ia06Answers', 'lembarJawabIa05'
        ])->findOrFail($id);

        // 2. AMBIL VARIABEL PENTING UNTUK SIDEBAR (INI YANG KURANG TADI)
        $jadwal = $dataSertifikasi->jadwal;
        $asesi  = $dataSertifikasi->asesi;

        // 3. Cek Level (Opsional, sesuaikan kebutuhan)
        // if ($dataSertifikasi->level_status < 100) { ... }

        // 4. Kirim SEMUA variabel ke view (dataSertifikasi, jadwal, asesi)
        return view('validator.tracker', compact('dataSertifikasi', 'jadwal', 'asesi'));
    }

    // 2. Action Validasi (Tombol Paling Bawah)
   public function validasi(Request $request, $id)
    {
        $data = DataSertifikasiAsesi::findOrFail($id);
        
        // Simpan status
        $data->status_validasi = 'valid';
        $data->save();

        // REVISI DISINI: Gunakan back() agar tetap di halaman tracker
        return redirect()->back()->with('success', 'Validasi Berhasil!');
    }
}
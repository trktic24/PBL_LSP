<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DataSertifikasiAsesi;
use App\Models\Admin;
use Barryvdh\DomPDF\Facade\Pdf;

class APL01Controller extends Controller
{
// --- HALAMAN 1 (Data Pribadi / APL 01 Bagian 1) ---
    public function step1($id)
    {
        $sertifikasi = DataSertifikasiAsesi::with(['asesi', 'jadwal.skema'])->findOrFail($id);
        
        // Return View Bagian 1
        return view('frontend.APL_01.APL_01_1', [
            'sertifikasi' => $sertifikasi,
            'asesi'       => $sertifikasi->asesi,
            'skema'       => $sertifikasi->jadwal->skema,
            'jadwal'      => $sertifikasi->jadwal,
        ]);
    }

    // --- HALAMAN 2 (Data Pekerjaan / APL 01 Bagian 2) ---
    public function step2($id)
    {
        $sertifikasi = DataSertifikasiAsesi::with(['asesi', 'jadwal.skema'])->findOrFail($id);
        
        // Return View Bagian 2 (Pastikan file blade-nya ada: frontend/APL_01/APL_01_2.blade.php)
        return view('frontend.APL_01.APL_01_2', [
            'sertifikasi' => $sertifikasi,
            'asesi'       => $sertifikasi->asesi,
            'skema'       => $sertifikasi->jadwal->skema,
            'jadwal'      => $sertifikasi->jadwal
        ]);
    }

    // --- HALAMAN 3 (Bukti Kelengkapan / APL 01 Bagian 3) ---
    public function step3($id)
    {
        $sertifikasi = DataSertifikasiAsesi::with(['asesi', 'jadwal.skema'])->findOrFail($id);
        
        // Return View Bagian 3
        return view('frontend.APL_01.APL_01_3', [
            'sertifikasi' => $sertifikasi,
            'asesi'       => $sertifikasi->asesi,
            'skema'       => $sertifikasi->jadwal->skema,
            'jadwal'      => $sertifikasi->jadwal
        ]);
    }

    public function storeStep1(Request $request) {
        // 1. Ambil ID dari input hidden form
        // Salah: findOrFail($request)
        // Benar: Ambil property 'id_data_sertifikasi_asesi' dari $request
        $id = $request->input('id_data_sertifikasi_asesi');


        $id_sertifikasi = DataSertifikasiAsesi::with(['asesi', 'jadwal.skema'])->findOrFail($id);        

        // 2. Redirect ke halaman selanjutnya (misal APL-02)
        return redirect()->route('APL_01_2', ['id' => $id_sertifikasi]); 
    }
    
    public function storeStep2(Request $request) {
        // 1. Ambil ID dari input hidden form
        // Salah: findOrFail($request)
        // Benar: Ambil property 'id_data_sertifikasi_asesi' dari $request
        $id = $request->input('id_data_sertifikasi_asesi');


        $id_sertifikasi = DataSertifikasiAsesi::with(['asesi', 'jadwal.skema'])->findOrFail($id);        

        // 2. Redirect ke halaman selanjutnya (misal APL-02)
        return redirect()->route('APL_01_3', ['id' => $id_sertifikasi]); 
    }   
    
    public function cetakPDF($id)
    {
        // 1. Ambil Data Lengkap (Asesi, Skema, Unit Kompetensi, Pekerjaan)
        $sertifikasi = DataSertifikasiAsesi::with([
            'asesi.user',
            'asesi.dataPekerjaan', // Pastikan relasi ini ada di model Asesi
            'jadwal.skema.unitKompetensi' // Ambil unit kompetensi dari skema
        ])->findOrFail($id);

        $admin = Admin::first();

        $asesi = $sertifikasi->asesi;
        $skema = $sertifikasi->jadwal->skema;
        $unitKompetensi = $skema->unitKompetensi ?? collect();

        // 2. Render PDF
        $pdf = Pdf::loadView('pdf.apl_01', [
            'sertifikasi'    => $sertifikasi,
            'asesi'          => $asesi,
            'skema'          => $skema,
            'unitKompetensi' => $unitKompetensi,
            'admin'          => $admin,
        ]);

        $pdf->setPaper('A4', 'portrait');

        return $pdf->stream('FR_APL_01_' . $asesi->nama_lengkap . '.pdf');
    }
}

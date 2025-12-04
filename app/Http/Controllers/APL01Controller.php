<?php

namespace App\Http\Controllers;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use App\Models\DataSertifikasiAsesi;

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
            'backUrl' => route('tracker', $sertifikasi->id_data_sertifikasi_asesi),
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
    
    // ... fungsi-fungsi lain di atas ...

    public function cetakPDF($id)
    {
        // 1. Ambil Data
        $dataSertifikasi = \App\Models\DataSertifikasiAsesi::with(['asesi', 'jadwal.skema'])->findOrFail($id);

        // 2. Siapkan Variabel untuk View
        $data = [
            'sertifikasi' => $dataSertifikasi,
            'asesi'       => $dataSertifikasi->asesi,
            'skema'       => $dataSertifikasi->jadwal->skema,
            'jadwal'      => $dataSertifikasi->jadwal
        ];

        // 3. Generate PDF
        // Load view yang sudah kamu buat tadi
        $pdf = Pdf::loadView('pdf.apl_01', $data);

        // Opsional: Atur ukuran kertas (Default biasanya A4)
        $pdf->setPaper('a4', 'portrait');

        // 4. TAMPILKAN (STREAM)
        // 'stream' akan membuka PDF di browser. 
        // Nama file di parameter itu nama default kalau user nge-klik tombol download.
        $namaFile = 'FR.APL.01 - ' . $dataSertifikasi->asesi->nama_lengkap . '.pdf';

        return $pdf->stream($namaFile);
    }
}
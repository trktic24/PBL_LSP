<?php

namespace App\Http\Controllers;

use App\Models\FrMapa01;
use App\Models\DataSertifikasiAsesi;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class FrMapa01Controller extends Controller
{
    // public function index()
    // {
    //     return view('frontend.FR_MAPA_01');
    // }

    public function index($id)
    {
        $sertifikasi = DataSertifikasiAsesi::with(['asesi', 'jadwal.skema'])->findOrFail($id);
        
        // Return View Bagian 1
        return view('frontend.FR_MAPA_01', [
            'sertifikasi' => $sertifikasi,
            'asesi'       => $sertifikasi->asesi,
            'skema'       => $sertifikasi->jadwal->skema,
            'jadwal'      => $sertifikasi->jadwal,            
        ]);
    }    

    public function store(Request $request)
    {
        // 1. Ambil semua data input
        // Kita buang _token dan _method karena tidak perlu disimpan di DB
        $data = $request->except(['_token', '_method']);

        // 2. Simpan ke database
        // Pastikan Model sudah di-set $guarded = ['id'] atau $fillable lengkap
        FrMapa01::create($data);

        // 3. Kembali ke halaman form dengan pesan sukses
        return redirect()->route('mapa01.index', $data['id_data_sertifikasi_asesi'])->with('success', 'Data FR.MAPA.01 berhasil disimpan!');
    }

    public function cetakPDF($idSertifikasi)
    {
        // 1. Ambil Data Sertifikasi
        $sertifikasi = DataSertifikasiAsesi::with([
            'jadwal.skema'
        ])->findOrFail($idSertifikasi);

        // 2. Ambil Data Form MAPA 01
        $mapa01 = FrMapa01::where('id_data_sertifikasi_asesi', $idSertifikasi)->first();

        // 3. Render PDF
        // Pastikan nama view sesuai dengan nama file yang kamu buat (misal: 'pdf.mapa01')
        $pdf = Pdf::loadView('pdf.mapa01', [
            'sertifikasi' => $sertifikasi,
            'mapa01'      => $mapa01
        ]);

        $pdf->setPaper('A4', 'portrait');

        return $pdf->stream('FR_MAPA_01_' . $sertifikasi->id_data_sertifikasi_asesi . '.pdf');
    }
}
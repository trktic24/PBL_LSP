<?php

namespace App\Http\Controllers;

use App\Models\FrMapa01;
use App\Models\DataSertifikasiAsesi;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage; // Wajib import ini

class FrMapa01Controller extends Controller
{
    public function index($id)
    {
        $sertifikasi = DataSertifikasiAsesi::with(['asesi', 'jadwal.skema.unitKompetensi'])->findOrFail($id);
        
        // Ambil data MAPA yang sudah ada (jika ada)
        $mapa01 = FrMapa01::where('id_data_sertifikasi_asesi', $id)->first();

        return view('frontend.FR_MAPA_01', [
            'sertifikasi' => $sertifikasi,
            'mapa01'      => $mapa01, // Kirim data agar form terisi otomatis
            'skema'       => $sertifikasi->jadwal->skema,     
        ]);
    }    

    public function store(Request $request)
    {
        // 1. Ambil data input selain file gambar
        $data = $request->except(['_token', '_method', 'ttd_penyusun_file', 'ttd_validator_file']);

        // 2. Logic Upload Tanda Tangan PENYUSUN
        if ($request->hasFile('ttd_penyusun_file')) {
            // Simpan gambar ke storage/public/signatures/mapa01
            $path = $request->file('ttd_penyusun_file')->store('signatures/mapa01', 'public');
            
            // Masukkan path gambar ke dalam array 'penyusun'
            // Kita ambil data penyusun dari form, lalu tambahkan key 'ttd_path'
            $penyusunData = $data['penyusun'] ?? [];
            $penyusunData[0]['ttd_path'] = $path; // Set path
            $data['penyusun'] = $penyusunData; // Update data utama
        } 
        // Jaga-jaga: Kalau tidak upload baru, tapi sudah ada di DB, jangan ditimpa null (Opsional, tergantung logic edit)
        
        // 3. Logic Upload Tanda Tangan VALIDATOR
        if ($request->hasFile('ttd_validator_file')) {
            $path = $request->file('ttd_validator_file')->store('signatures/mapa01', 'public');
            $validatorData = $data['validator'] ?? [];
            $validatorData[0]['ttd_path'] = $path;
            $data['validator'] = $validatorData;
        }

        // 4. Simpan ke Database (Update jika ada, Create jika baru)
        FrMapa01::updateOrCreate(
            ['id_data_sertifikasi_asesi' => $request->id_data_sertifikasi_asesi],
            $data
        );

        // 5. Redirect kembali
        return redirect()->back()->with('success', 'Data FR.MAPA.01 berhasil disimpan!');
    }
    
    // ... method cetakPDF tetap sama ...
}
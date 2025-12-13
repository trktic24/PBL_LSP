<?php

namespace App\Http\Controllers\Asesi\Apl02;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\DataSertifikasiAsesi;
use App\Models\ResponApl2Ia01;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Barryvdh\DomPDF\Facade\Pdf;

class PraasesmenController extends Controller
{
    /**
     * Menampilkan halaman Pra-Asesmen Mandiri (APL-02)
     */
    public function view($idDataSertifikasi)
    {
        // try {
            // 1. Ambil Data Sertifikasi dengan Relasi Lengkap
            // Kita butuh 'jadwal.asesor' untuk sidebar
            $sertifikasi = DataSertifikasiAsesi::with([
                'asesi.user',
                'jadwal.asesor', // <--- PENTING: Ambil data asesor
                'jadwal.skema.kelompokPekerjaan.unitKompetensi.elemen.kriteriaUnjukKerja'
            ])->findOrFail($idDataSertifikasi);

            $skema = $sertifikasi->jadwal->skema;
            
            // Ambil Data Asesor dari relasi
            $asesorObj = $sertifikasi->jadwal->asesor;

            // 2. Ambil Respon yang SUDAH ADA (History Jawaban)
            $existingResponses = ResponApl2Ia01::where('id_data_sertifikasi_asesi', $idDataSertifikasi)
                ->get()
                ->keyBy('id_kriteria'); 

            // Filter Role
            $mode = Auth::user()->role->id_role === 2 ? 'edit' : 'view';                

            // 3. Kirim Data ke View
            return view('frontend.apl02', [
                'sertifikasi'       => $sertifikasi, // Dikirim untuk Sidebar
                'skema'             => $skema,
                'asesi'             => $sertifikasi->asesi,
                'idDataSertifikasi' => $idDataSertifikasi,
                'existingResponses' => $existingResponses,
                'mode'              => $mode,
                
                // Kirim Data Asesor yang sudah dirapikan (Sesuai permintaanmu)
                'asesor' => [
                    'nama'   => $asesorObj->nama_lengkap ?? 'Belum Ditentukan',
                    'no_reg' => $asesorObj->nomor_regis ?? '-',
                ],
            ]);

        // } catch (\Exception $e) {
        //     return redirect('/tracker')->with('error', 'Data tidak ditemukan: ' . $e->getMessage());
        // }
    }

    /**
     * Menyimpan respon APL-02
     */
    public function store(Request $request, $idDataSertifikasi)
    {
        // ... (Bagian STORE ini TIDAK BERUBAH, tetap sama seperti sebelumnya) ...
        // Biar aman, saya sertakan lagi yang versi lengkap & benar

        $request->validate([
            'respon' => 'required|array',
            'respon.*.k' => 'required|in:1,0', 
            'respon.*.bukti' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120'
        ]);

        try {
            DB::beginTransaction();

            foreach ($request->respon as $idKriteria => $data) {
                
                $filePath = null;
                $existing = ResponApl2Ia01::where('id_data_sertifikasi_asesi', $idDataSertifikasi)
                                          ->where('id_kriteria', $idKriteria)
                                          ->first();

                // 1. Handle File Upload
                if ($request->hasFile("respon.$idKriteria.bukti")) {
                    $file = $request->file("respon.$idKriteria.bukti");
                    $ext = $file->getClientOriginalExtension();
                    $fileName = "bukti_apl02_{$idDataSertifikasi}_{$idKriteria}.{$ext}";
                    $destinationPath = public_path("images/bukti_apl02/{$idDataSertifikasi}");
                    
                    if (!File::exists($destinationPath)) {
                        File::makeDirectory($destinationPath, 0755, true);
                    }

                    if ($existing && $existing->bukti_asesi_apl02 && File::exists(public_path($existing->bukti_asesi_apl02))) {
                        File::delete(public_path($existing->bukti_asesi_apl02));
                    }

                    $file->move($destinationPath, $fileName);
                    $filePath = "images/bukti_apl02/{$idDataSertifikasi}/{$fileName}";

                } else {
                    $filePath = $existing ? $existing->bukti_asesi_apl02 : null;
                }

                // 2. Simpan ke Database
                ResponApl2Ia01::updateOrCreate(
                    [
                        'id_data_sertifikasi_asesi' => $idDataSertifikasi,
                        'id_kriteria' => $idKriteria
                    ],
                    [
                        'respon_asesi_apl02' => $data['k'],
                        'bukti_asesi_apl02'  => $filePath,
                        'pencapaian_ia01'       => 1, 
                        'penilaian_lanjut_ia01' => 0 
                    ]
                );
            }
            
            // 3. Update Status (Naik Level ke Pra-Asesmen Selesai)
            $sertifikasi = DataSertifikasiAsesi::find($idDataSertifikasi);
            // Cek level atau status string-nya
            // Pastikan status ini ada di enum database kamu
            if($sertifikasi->status_sertifikasi != 'pra_asesmen_selesai' && 
               $sertifikasi->progres_level < 40) { 
                 $sertifikasi->status_sertifikasi = 'pra_asesmen_selesai';
                 $sertifikasi->save();
            }

            DB::commit();
            
            // Return JSON
            return response()->json([
                'success' => true,
                'message' => 'Asesmen Mandiri berhasil disimpan!',
                'id_jadwal' => $sertifikasi->id_jadwal 
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Gagal simpan APL-02: " . $e->getMessage());
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function verifikasi(Request $request, $id_sertifikasi)
    {
        $data = DataSertifikasiAsesi::findOrFail($id_sertifikasi);
        $data->rekomendasi_apl02 = 'diterima';
        $data->save();

        return response()->json([
            'success' => true,
            'message' => "Rekomendasi APL-02 berhasil diisi 'diterima'."
        ]);
    }

   /**
     * Generate PDF APL-02 untuk Asesi
     */
    public function generatePDF($idDataSertifikasi)
    {
        // 1. Ambil Data Sertifikasi beserta relasi lengkap (Unit -> Elemen -> KUK)
        $sertifikasi = DataSertifikasiAsesi::with([
            'asesi.user',
            'jadwal.skema.kelompokPekerjaan.unitKompetensi.elemen.kriteriaUnjukKerja', // Deep nested relationship
            'jadwal.asesor',
        ])->findOrFail($idDataSertifikasi);

        $skema = $sertifikasi->jadwal->skema;

        // 2. Ambil Respon APL-02 Asesi (KeyBy ID Kriteria agar mudah dipanggil di view)
        $responses = ResponApl2Ia01::where('id_data_sertifikasi_asesi', $idDataSertifikasi)
            ->get()
            ->keyBy('id_kriteria'); // Pastikan nama kolom di DB adalah 'id_kriteria'

        // 3. Tentukan mode (view selalu untuk PDF)
        $mode = 'view';

        // 4. Buat data untuk dikirim ke view PDF
        $data = [
            'sertifikasi'       => $sertifikasi,
            'skema'             => $skema,
            'asesi'             => $sertifikasi->asesi,
            'existingResponses' => $responses,
            'mode'              => $mode,
        ];

        // 5. Load view blade untuk PDF
        // Pastikan nama file view sesuai: resources/views/pdf/apl02.blade.php
        $pdf = Pdf::loadView('pdf.apl02', $data);
        $pdf->setPaper('A4', 'portrait');

        // 6. Kirim file PDF langsung ke browser
        $fileName = 'APL02_' . $sertifikasi->asesi->nama_lengkap . '.pdf';
        return $pdf->stream($fileName); 
    }
}
<?php

namespace App\Http\Controllers\Asesi\Apl02;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DataSertifikasiAsesi;
use App\Models\ResponApl2Ia01;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class PraasesmenController extends Controller
{
    /**
     * Menampilkan halaman Pra-Asesmen Mandiri (APL-02)
     */
    public function index($idDataSertifikasi)
    {
        // try {
        // 1. Ambil Data Sertifikasi dengan Relasi Lengkap
        // Kita butuh 'jadwal.asesor' untuk sidebar
        $sertifikasi = DataSertifikasiAsesi::with([
            'asesi.user',
            'jadwal.asesor', // <--- PENTING: Ambil data asesor
            'jadwal.skema.kelompokPekerjaan.unitKompetensi.elemen.kriteria',
        ])->findOrFail($idDataSertifikasi);

        if ($sertifikasi->status_sertifikasi == 'pra_asesmen_selesai') {
            return redirect()->route('asesi.pra_asesmen.selesai', ['id_sertifikasi' => $idDataSertifikasi]);
        }

        $skema = $sertifikasi->jadwal->skema;

        // Ambil Data Asesor dari relasi
        $asesorObj = $sertifikasi->jadwal->asesor;

        // 2. Ambil Respon yang SUDAH ADA (History Jawaban)
        $existingResponses = ResponApl2Ia01::where('id_data_sertifikasi_asesi', $idDataSertifikasi)->get()->keyBy('id_kriteria');

        // 3. Kirim Data ke View
        return view('asesi.pra-assesmen.praasesmen', [
            'sertifikasi' => $sertifikasi, // Dikirim untuk Sidebar
            'skema' => $skema,
            'asesi' => $sertifikasi->asesi,
            'idDataSertifikasi' => $idDataSertifikasi,
            'existingResponses' => $existingResponses,

            // Kirim Data Asesor yang sudah dirapikan (Sesuai permintaanmu)
            'asesor' => [
                'nama' => $asesorObj->nama_lengkap ?? 'Belum Ditentukan',
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
            'respon.*.bukti' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ]);

        try {
            DB::beginTransaction();

            foreach ($request->respon as $idKriteria => $data) {
                $filePath = null;
                $existing = ResponApl2Ia01::where('id_data_sertifikasi_asesi', $idDataSertifikasi)->where('id_kriteria', $idKriteria)->first();

                // 1. Handle File Upload
                if ($request->hasFile("respon.$idKriteria.bukti")) {
                    $file = $request->file("respon.$idKriteria.bukti");
                    $ext = $file->getClientOriginalExtension();
                    $fileName = "bukti_apl02_{$idDataSertifikasi}_{$idKriteria}.{$ext}";
                    // Refactored to use private_docs disk
                    $folderName = "bukti_apl02/{$idDataSertifikasi}";
                    // Store file using private_docs disk
                    // putFileAs returns the path relative to the disk root
                    $filePath = Storage::disk('private_docs')->putFileAs($folderName, $file, $fileName);
                } else {
                    $filePath = $existing ? $existing->bukti_asesi_apl02 : null;
                }

                // 2. Simpan ke Database
                ResponApl2Ia01::updateOrCreate(
                    [
                        'id_data_sertifikasi_asesi' => $idDataSertifikasi,
                        'id_kriteria' => $idKriteria,
                    ],
                    [
                        'respon_asesi_apl02' => $data['k'],
                        'bukti_asesi_apl02' => $filePath,
                        'pencapaian_ia01' => 1,
                        'penilaian_lanjut_ia01' => 0,
                    ],
                );
            }

            // 3. Update Status (Naik Level ke Pra-Asesmen Selesai)
            $sertifikasi = DataSertifikasiAsesi::find($idDataSertifikasi);
            // Cek level atau status string-nya
            // Pastikan status ini ada di enum database kamu
            if ($sertifikasi->status_sertifikasi != 'pra_asesmen_selesai' && $sertifikasi->progres_level < 40) {
                $sertifikasi->status_sertifikasi = 'pra_asesmen_selesai';
                $sertifikasi->save();
            }

            DB::commit();

            // Return JSON
            return response()->json([
                'success' => true,
                'message' => 'Asesmen Mandiri berhasil disimpan!',
                'id_jadwal' => $sertifikasi->id_jadwal,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal simpan APL-02: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
    /**
     * Generate PDF APL-02
     */
    public function generatePDF($idDataSertifikasi)
    {
        // 1. Ambil Data Sertifikasi dengan Relasi Lengkap
        $sertifikasi = DataSertifikasiAsesi::with([
            'asesi.user',
            'jadwal.asesor',
            'jadwal.skema.kelompokPekerjaan.unitKompetensi.elemen.kriteria',
        ])->findOrFail($idDataSertifikasi);

        $skema = $sertifikasi->jadwal->skema;
        $asesorObj = $sertifikasi->jadwal->asesor;

        // 2. Ambil Respon yang SUDAH ADA
        $existingResponses = ResponApl2Ia01::where('id_data_sertifikasi_asesi', $idDataSertifikasi)->get()->keyBy('id_kriteria');

        // 3. Load View PDF
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.APL_02', [
            'sertifikasi' => $sertifikasi,
            'skema' => $skema,
            'asesi' => $sertifikasi->asesi,
            'idDataSertifikasi' => $idDataSertifikasi,
            'existingResponses' => $existingResponses,
            'asesor' => [
                'nama' => $asesorObj->nama_lengkap ?? 'Belum Ditentukan',
                'no_reg' => $asesorObj->nomor_regis ?? '-',
            ],
        ]);

        $pdf->setPaper('A4', 'portrait');

        return $pdf->stream('FR.APL.02 ' . $sertifikasi->asesi->nama_lengkap . '.pdf');
    }
}
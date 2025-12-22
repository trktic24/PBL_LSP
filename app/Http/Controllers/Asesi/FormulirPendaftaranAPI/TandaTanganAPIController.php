<?php

namespace App\Http\Controllers\Asesi\FormulirPendaftaranAPI;

use App\Models\Asesi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\DataSertifikasiAsesi;
use Illuminate\Support\Facades\File;
use App\Http\Resources\AsesiResource;
use App\Http\Resources\AsesiTTDResource;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class TandaTanganAPIController extends Controller
{
    /**
     * METHOD API 1: AMBIL DATA ASESI
     * (Ini udah bener, gak perlu diubah)
     */
    public function index()
    {
        $asesis = Asesi::all();
        return AsesiResource::collection($asesis);
    }

    public function show($id_asesi)
    {
        $data = Asesi::with('dataPekerjaan:id_pekerjaan,id_asesi,jabatan,nama_institusi_pekerjaan,alamat_institusi')->findOrFail($id_asesi);
        return new AsesiTTDResource($data);
    }
    public function storeAjax(Request $request, $id_asesi)
    {
        // 1. Validasi Input
        $request->validate([
            'data_tanda_tangan' => 'nullable|string', 
            'id_data_sertifikasi_asesi' => 'required|integer'
        ]);

        try {
            $asesi = Asesi::findOrFail($id_asesi);

            // 2. Logika Simpan Gambar (Jika ada kiriman gambar baru)
            if ($request->filled('data_tanda_tangan')) {
                
                $image_parts = explode(";base64,", $request->data_tanda_tangan);
                $image_type_aux = explode("image/", $image_parts[0]);
                $image_type = $image_type_aux[1];
                $image_base64 = base64_decode($image_parts[1]);

                $fileName = 'tanda_tangan_' . $id_asesi . '.png'; 
                $folderPath = 'ttd_asesi'; // Standardized folder
                
                // Store using private_docs disk
                Storage::disk('private_docs')->put($folderPath . '/' . $fileName, $image_base64);
                
                $asesi->tanda_tangan = $folderPath . '/' . $fileName;
                $asesi->save();
                
            } else {
                // Kalau gak kirim gambar, pastikan user emang udah punya TTD
                if (empty($asesi->tanda_tangan)) {
                    return response()->json([
                        'success' => false, 
                        'message' => 'Tanda tangan belum ada. Silakan tanda tangan dulu!'
                    ], 422);
                }
            }

            // ============================================================
            // 3. [PERBAIKAN BUG] UPDATE STATUS SERTIFIKASI (DENGAN VALIDASI)
            // ============================================================
            $sertifikasi = DataSertifikasiAsesi::find($request->id_data_sertifikasi_asesi);
            
            if ($sertifikasi) {
                // LOGIKA PENTING:
                // Hanya update jadi 'pendaftaran_selesai' JIKA statusnya masih 'sedang_mendaftar' atau NULL.
                // Kalau statusnya sudah 'menunggu_pembayaran', 'lunas', 'asesmen', dll -> JANGAN UBAH MUNDUR.
                
                $statusSekarang = $sertifikasi->status_sertifikasi;
                $statusAwal = DataSertifikasiAsesi::STATUS_SEDANG_MENDAFTAR; // 'sedang_mendaftar'

                if ($statusSekarang == $statusAwal || is_null($statusSekarang)) {
                    
                    $sertifikasi->status_sertifikasi = DataSertifikasiAsesi::STATUS_PENDAFTARAN_SELESAI;
                    $sertifikasi->save();
                    
                } 
                // Else: Biarkan statusnya apa adanya (jangan diubah)
            }

            return response()->json([
                'success' => true,
                'message' => 'Data berhasil diperbarui!',
                'path' => $asesi->tanda_tangan
            ]);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
    public function showTandaTangan($id_sertifikasi)
    {
        try {
            // 1. Cari Data Pendaftaran berdasarkan ID Pendaftaran
            // Load asesi beserta data pekerjaannya
            $sertifikasi = DataSertifikasiAsesi::with('asesi.dataPekerjaan')->findOrFail($id_sertifikasi);

            return view('asesi.formulir_pendaftaran.tanda_tangan_pemohon', [
                'sertifikasi' => $sertifikasi,
                'asesi' => $sertifikasi->asesi,
            ]);

        } catch (\Exception $e) {
            // 2. Handle jika data tidak ditemukan atau error lain
            return redirect('/asesi/tracker')->with('error', 'Data Pendaftaran tidak ditemukan.');
        }
    }
}

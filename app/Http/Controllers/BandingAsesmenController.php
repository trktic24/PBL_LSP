<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BandingAsesmen;
use Illuminate\Support\Facades\Validator;

class BandingAsesmenController extends Controller
{
    /**
     * Menyimpan pengajuan banding asesmen yang baru.
     */
    public function store(Request $request)
    {
        // 1. Validasi Data
        $validator = Validator::make($request->all(), [
            'nama_asesi' => 'required|string|max:255',
            'nama_asesor' => 'required|string|max:255',
            'tanggal_asesmen' => 'required|date',
            
            // Perhatikan bahwa input checkbox/radio biasanya mengirimkan 'on' atau 1/0
            'proses_banding_dijelaskan' => 'nullable|boolean', 
            'diskusi_banding_dengan_asesor' => 'nullable|boolean',
            'melibatkan_orang_lain' => 'nullable|boolean',
            
            'skema_sertifikasi' => 'required|string|max:255',
            'no_skema_sertifikasi' => 'required|string|max:255',
            'alasan_banding' => 'required|string',
            'tanggal_pengajuan_banding' => 'required|date',
            // Untuk tanda tangan, jika berupa upload file/gambar
            // 'tanda_tangan_asesi' => 'nullable|image|max:2048', 
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // 2. Proses Penyimpanan Tanda Tangan (Opsional)
        // Jika Anda mengupload gambar tanda tangan, tambahkan logika ini
        $tanda_tangan_path = null;
        if ($request->hasFile('tanda_tangan_asesi')) {
            $tanda_tangan_path = $request->file('tanda_tangan_asesi')->store('signatures', 'public');
        }

        // 3. Menyimpan Data ke Database
        $banding = BandingAsesmen::create([
            'nama_asesi' => $request->nama_asesi,
            'nama_asesor' => $request->nama_asesor,
            'tanggal_asesmen' => $request->tanggal_asesmen,
            
            // Konversi nilai input (misal 'on' atau null) ke boolean
            'proses_banding_dijelaskan' => $request->boolean('proses_banding_dijelaskan'), 
            'diskusi_banding_dengan_asesor' => $request->boolean('diskusi_banding_dengan_asesor'),
            'melibatkan_orang_lain' => $request->boolean('melibatkan_orang_lain'),
            
            'skema_sertifikasi' => $request->skema_sertifikasi,
            'no_skema_sertifikasi' => $request->no_skema_sertifikasi,
            'alasan_banding' => $request->alasan_banding,
            'tanda_tangan_asesi' => $tanda_tangan_path,
            'tanggal_pengajuan_banding' => $request->tanggal_pengajuan_banding,
        ]);

        return response()->json([
            'message' => 'Pengajuan Banding Asesmen berhasil disimpan.', 
            'data' => $banding
        ], 201);
    }
}
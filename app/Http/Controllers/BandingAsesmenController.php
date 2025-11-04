<?php

namespace App\Http\Controllers;

use App\Models\Banding;
use App\Models\Asesmen; // Asumsi Model Asesmen yang menyimpan data hasil asesmen
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class BandingController extends Controller
{
    /**
     * Menampilkan formulir banding dengan data asesmen terkait yang sudah terisi.
     * @param int $id_asesmen ID dari Asesmen yang akan diajukan banding
     */
    public function create($id_asesmen)
    {
        try {
            // Eager loading relasi yang diperlukan untuk mengisi form
            // Kita masih butuh relasi ke Asesor, Asesi, dan Skema untuk mengisi field di Blade.
            $dataAsesmen = Asesmen::with([
                'asesi.user', 
                'asesor.user', 
                'skema'
            ])->findOrFail($id_asesmen);

            // Format data yang akan dikirim ke view 'banding.blade.php'
            $viewData = (object) [
                // Field yang dibutuhkan untuk tampilan form:
                'nama_asesor' => $dataAsesmen->asesor->user->nama ?? 'Nama Asesor Tidak Tersedia', 
                'nama_asesi' => $dataAsesmen->asesi->user->nama ?? 'Nama Asesi Tidak Tersedia',
                'skema_sertifikasi' => $dataAsesmen->skema->nama_skema ?? 'Skema Tidak Tersedia', 
                'no_skema_sertifikasi' => $dataAsesmen->skema->no_skema ?? 'No. Skema Tidak Tersedia',
                'tanggal_asesmen' => $dataAsesmen->tanggal_asesmen,
                
                // Field hidden yang wajib ada di Model Banding (sesuai Model Anda):
                'id_asesmen' => $dataAsesmen->id_asesmen,
                'id_asesi' => $dataAsesmen->id_asesi, // Diambil dari FK di tabel asesmen
            ];
            
            return view('banding', ['dataAsesmen' => $viewData]);

        } catch (\Exception $e) {
            Log::error("Error memuat form banding:", ['error' => $e->getMessage()]);
            return abort(404, 'Data Asesmen yang terkait tidak dapat ditemukan atau dimuat. Periksa relasi Model Asesmen Anda.');
        }
    }

    /**
     * Menyimpan pengajuan banding dari formulir.
     */
    public function store(Request $request)
    {
        // 1. Validasi Input Formulir
        $validated = $request->validate([
            // Data Banding
            'ya_tidak_1' => 'required|in:Ya,Tidak',
            'ya_tidak_2' => 'required|in:Ya,Tidak',
            'ya_tidak_3' => 'required|in:Ya,Tidak',
            'alasan_banding' => 'required|string|max:1000',
            'tanggal_pengajuan_banding' => 'required|date',
            
            // Hidden fields (FKs)
            'id_asesmen' => 'required|integer|exists:asesmen,id_asesmen',
            'id_asesi' => 'required|integer|exists:asesi,id_asesi',
            
            // Checkbox (nullable)
            'tuk_sewaktu' => 'nullable',
            'tuk_tempatkerja' => 'nullable',
            'tuk_mandiri' => 'nullable',
        ]);
        
        // 2. Menyiapkan Data untuk Disimpan
        $dataToStore = [
            'id_asesmen' => $validated['id_asesmen'],
            'id_asesi' => $validated['id_asesi'],
            
            // Checkbox: true jika ada di request, false jika tidak ada
            'tuk_sewaktu' => $request->has('tuk_sewaktu'), 
            'tuk_tempatkerja' => $request->has('tuk_tempatkerja'),
            'tuk_mandiri' => $request->has('tuk_mandiri'),

            'ya_tidak_1' => $validated['ya_tidak_1'],
            'ya_tidak_2' => $validated['ya_tidak_2'],
            'ya_tidak_3' => $validated['ya_tidak_3'],
            'alasan_banding' => $validated['alasan_banding'],
            'tanggal_pengajuan_banding' => $validated['tanggal_pengajuan_banding'],
        ];

        // 3. Simpan data menggunakan Model Banding
        try {
            Banding::create($dataToStore);
            
            // Redirect ke halaman sukses
            return redirect()->route('dashboard')->with('success', 'Pengajuan banding Anda berhasil dikirim.');

        } catch (\Exception $e) {
            Log::error("Gagal menyimpan banding:", ['error' => $e->getMessage()]);
            return redirect()->back()->withInput()->with('error', 'Gagal menyimpan banding. Silakan coba lagi.');
        }
    }
}
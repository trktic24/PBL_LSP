<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\PoinAk03;
use App\Models\ResponHasilAk03;
use App\Models\DataSertifikasiAsesi;

class Ak03Controller extends Controller
{
    /**
     * MENAMPILKAN FORM
     */
    public function index()
    {
        $user = Auth::user();
        $asesi = $user->asesi;
        $sertifikasi = null;

        if ($asesi) {
            // Ambil data sertifikasi terakhir beserta relasi yang dibutuhkan
            // [PENTING] 'jadwal.jenisTuk' ditambahkan agar TUK muncul otomatis
            $sertifikasi = $asesi->dataSertifikasi()
                ->with([
                    'jadwal.skema',
                    'jadwal.asesor',
                    'jadwal.jenisTuk',
                    'asesi'
                ])
                ->latest()
                ->first();
        }

        $komponen = PoinAk03::all();

        // Kirim data ke View
        return view('umpan_balik.umpan_balik', [
            'komponen'    => $komponen,
            'sertifikasi' => $sertifikasi,
            'asesi'       => $asesi,
        ]);
    }

    /**
     * MENYIMPAN JAWABAN
     */
    public function store(Request $request)
    {
        // 1. Validasi input
        $request->validate([
            'jawaban'           => 'required|array',
            // Validasi untuk textarea catatan tambahan (boleh kosong)
            'catatan_tambahan'  => 'nullable|string',
        ]);

        $user = Auth::user();
        $asesi = $user->asesi;

        // Ambil sertifikasi terakhir
        $sertifikasi = $asesi->dataSertifikasi()->latest()->first();

        if (!$sertifikasi) {
            return redirect()->back()->with('error', 'Data sertifikasi tidak ditemukan.');
        }

        $id_sertifikasi = $sertifikasi->id_data_sertifikasi_asesi;

        // 2. Loop untuk menyimpan setiap jawaban (Ya/Tidak & Catatan per poin)
        foreach ($request->jawaban as $id_poin => $data) {
            ResponHasilAk03::create([
                'id_data_sertifikasi_asesi' => $id_sertifikasi,
                'id_poin_ak03'              => $id_poin,
                'hasil'                     => $data['hasil'] ?? null,
                'catatan'                   => $data['catatan'] ?? null,
            ]);
        }

        // ==================================================================
        // [PERBAIKAN DI SINI] Simpan Catatan Tambahan Umum ke tabel data_sertifikasi_asesi
        // ==================================================================
        // Kita gunakan nama kolom yang SESUAI dengan migrasi Anda: 'catatan_asesi_AK03'
        // BUKAN 'komentar'.
        $sertifikasi->update([
            'catatan_asesi_AK03' => $request->catatan_tambahan
        ]);
        // ==================================================================

        return redirect()->back()->with('success', 'Terima kasih, semua umpan balik berhasil disimpan!');
    }
}
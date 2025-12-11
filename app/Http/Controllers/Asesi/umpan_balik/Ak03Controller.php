<?php

namespace App\Http\Controllers\Asesi\umpan_balik;

use App\Models\PoinAk03;
use Illuminate\Http\Request;
use App\Models\ResponHasilAk03;
use App\Http\Controllers\Controller;
use App\Models\DataSertifikasiAsesi;
use Illuminate\Support\Facades\Auth;

class Ak03Controller extends Controller
{
    /**
     * MENAMPILKAN FORM
     * Menerima parameter $id dari Route
     */
    public function index($id)
    {
        $user = Auth::user();
        $asesi = $user->asesi;
        
        // Cek apakah asesi ada
        if (!$asesi) {
            abort(403, 'Akses ditolak. User bukan asesi.');
        }

        // 1. Cek apakah user SUDAH pernah mengisi umpan balik untuk sertifikasi ini
        // Ini mencegah user membuka form lagi jika sudah pernah submit
        // Pastikan kakak sudah membuat view 'umpan_balik.sudah_mengisi'
        $sudahIsi = ResponHasilAk03::where('id_data_sertifikasi_asesi', $id)->exists();

        if ($sudahIsi) {
            return view('asesi.tunggu_or_berhasil.berhasil', [
                'id_sertifikasi' => $id
            ]); 
        }

        // 2. Cari data sertifikasi berdasarkan ID spesifik dari URL ($id)
        // Kita tambahkan where('id_asesi', ...) untuk keamanan
        $sertifikasi = DataSertifikasiAsesi::where('id_data_sertifikasi_asesi', $id)
            ->where('id_asesi', $asesi->id_asesi)
            ->with([
                'jadwal.skema',
                'jadwal.asesor',
                'jadwal.jenisTuk',
                'asesi'
            ])
            ->firstOrFail(); // Error 404 jika tidak ketemu

        $komponen = PoinAk03::all();

        // Kirim data ke View Form
        return view('asesi.umpan_balik.umpan_balik', [
            'komponen'    => $komponen,
            'sertifikasi' => $sertifikasi,
            'asesi'       => $asesi,
        ]);
    }

    /**
     * MENYIMPAN JAWABAN
     * Menerima parameter $id dari Route
     */
    public function store(Request $request, $id)
    {
        // 1. Validasi input
        $request->validate([
            'jawaban'           => 'required|array',
            'catatan_tambahan'  => 'nullable|string',
        ]);

        $user = Auth::user();
        $asesi = $user->asesi;

        // Cek sertifikasi
        $sertifikasi = DataSertifikasiAsesi::where('id_data_sertifikasi_asesi', $id)
            ->where('id_asesi', $asesi->id_asesi)
            ->first();

        if (!$sertifikasi) {
            return redirect()->back()->with('error', 'Data sertifikasi tidak ditemukan.');
        }

        // Cek Double Submit
        if (ResponHasilAk03::where('id_data_sertifikasi_asesi', $id)->exists()) {
            return redirect()->route('tracker')->with('warning', 'Anda sudah mengisi umpan balik sebelumnya.');
        }

        $id_sertifikasi = $id;

        // 2. Loop simpan jawaban respon
        foreach ($request->jawaban as $id_poin => $data) {
            ResponHasilAk03::create([ 
                'id_data_sertifikasi_asesi' => $id_sertifikasi,
                'id_poin_ak03'              => $id_poin,
                'hasil'   => $data['hasil'] ?? null,
                'catatan' => $data['catatan'] ?? null,
            ]);
        }

        // 3. Update Data Sertifikasi (Catatan + Status)
        $sertifikasi->update([
            'catatan_asesi_AK03' => $request->catatan_tambahan,
            // [UPDATE STATUS DI SINI]
            // Gunakan konstanta dari Model agar lebih rapi dan aman
            'status_sertifikasi' => DataSertifikasiAsesi::STATUS_UMPAN_BALIK_SELESAI
        ]);

        // 4. Redirect ke Tracker
        // GANTI 'dashboard' dengan nama route halaman list sertifikasi/tracker kakak
        return redirect()->route('tracker')->with('success', 'Umpan balik berhasil dikirim! Status sertifikasi telah diperbarui.');
    }
}
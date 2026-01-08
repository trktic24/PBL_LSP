<?php

namespace App\Http\Controllers\Asesor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Jadwal;
use App\Models\DataSertifikasiAsesi;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class Ak05Controller extends Controller
{
    // 1. MENAMPILKAN FORM (BERDASARKAN JADWAL)
    public function index($id_jadwal)
    {
        // Ambil Data Jadwal & Asesor
        // Pastikan ID Jadwal valid, jika tidak ada akan otomatis 404
        $jadwal = Jadwal::with(['skema', 'asesor', 'tuk'])->findOrFail($id_jadwal);
        
        // Cek Otorisasi: Hanya Asesor yang bersangkutan atau Admin yang bisa akses
        $user = Auth::user();
        // Gunakan helper hasRole dari model User untuk keamanan
        if ($user->hasRole('asesor')) {
            if (!$user->asesor || $jadwal->id_asesor != $user->asesor->id_asesor) {
                abort(403, 'Anda tidak berhak mengakses jadwal ini.');
            }
        }
        
        // Ambil Daftar Asesi yang ada di Jadwal ini
        $listAsesi = DataSertifikasiAsesi::with('asesi')
                    ->where('id_jadwal', $id_jadwal)
                    ->get();

        // Data Asesor untuk Header
        $asesor = $jadwal->asesor;

        // Tampilkan View
        return view('frontend.AK_05.FR_AK_05', compact('jadwal', 'listAsesi', 'asesor'));
    }

    // 2. MENYIMPAN DATA (BATCH UPDATE)
    public function store(Request $request, $id_jadwal)
    {
        // Validasi input
        $request->validate([
            'asesi' => 'required|array',
            'asesi.*.id_asesi' => 'required',
            'asesi.*.rekomendasi' => 'required|in:K,BK',
            'asesi.*.keterangan' => 'nullable|string',
            'aspek_asesmen' => 'nullable|string',
            'catatan_penolakan' => 'nullable|string',
            'saran_perbaikan' => 'nullable|string',
            'catatan_akhir' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            // 1. Simpan Data Per-Asesi (Rekomendasi K/BK)
            foreach ($request->asesi as $item) {
                $dataAsesi = DataSertifikasiAsesi::where('id_asesi', $item['id_asesi']) // Cari berdasarkan ID Asesi
                                ->where('id_jadwal', $id_jadwal)
                                ->first();
                
                if ($dataAsesi) {
                    $dataAsesi->update([
                        'rekomendasi_AK05' => ($item['rekomendasi'] == 'K') ? 'kompeten' : 'belum kompeten', // Mapping K/BK ke ENUM DB
                        'keterangan_AK05' => $item['keterangan'],
                        
                        // 2. Simpan Catatan Umum ke SETIAP Asesi (Redundan tapi aman utk report)
                        'aspek_dalam_AK05' => $request->aspek_asesmen,
                        'catatan_penolakan_AK05' => $request->catatan_penolakan,
                        'saran_dan_perbaikan_AK05' => $request->saran_perbaikan,
                        'catatan_AK05' => $request->catatan_akhir,
                        
                        // Update Status Sertifikasi (Opsional)
                        // Jika K -> Direkomendasikan, Jika BK -> Tidak Direkomendasikan
                        'status_sertifikasi' => ($item['rekomendasi'] == 'K') ? 'direkomendasikan' : 'tidak_direkomendasikan',
                    ]);
                }
            }

            DB::commit();
            return redirect()->back()->with('success', 'Laporan Asesmen (AK-05) berhasil disimpan!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
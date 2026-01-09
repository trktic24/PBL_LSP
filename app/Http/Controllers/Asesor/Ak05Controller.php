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
        $listAsesi = DataSertifikasiAsesi::with(['asesi', 'komentarAk05.Ak05'])
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
            // 1. Simpan Data Global (AK05 Table) - General Notes
            // Logic: Create new or Find existing (Assumption: One AK05 per schedule?? 
            // OR One AK05 per Asesi? The user request implies Global notes go to Ak05 table.
            
            // To link Ak05 to valid context, we need to know how strict the relation is.
            // Since there is no `id_jadwal` in `ak05` table, we create one record and link it via `komentar_ak05`? 
            // OR we assume one AK05 record is shared?
            // User Intention: "simpan catatan umumnya ke tabel ak05 ... kecuali catatan_AK05 ke KomentarAk05"
            // Let's create a NEW Ak05 record OR update existing if we can find it via existing KomentarAk05.
            
            // Find existing Ak05 via any asesi in this jadwal
            $firstAsesiData = DataSertifikasiAsesi::where('id_asesi', $request->asesi[array_key_first($request->asesi)]['id_asesi'] ?? 0)
                ->where('id_jadwal', $id_jadwal)
                ->first();
            
            $existingKomentar = $firstAsesiData 
                ? \App\Models\KomentarAk05::where('id_data_sertifikasi_asesi', $firstAsesiData->id_data_sertifikasi_asesi)->first()
                : null;
            
            if ($existingKomentar && $existingKomentar->Ak05) {
                $ak05 = $existingKomentar->Ak05;
            } else {
                $ak05 = new \App\Models\Ak05();
            }

            $ak05->aspek_negatif_positif = $request->aspek_asesmen;
            $ak05->penolakan_hasil_asesmen = $request->catatan_penolakan;
            $ak05->saran_perbaikan = $request->saran_perbaikan;
            $ak05->save();

            // 2. Simpan Data Per-Asesi
            foreach ($request->asesi as $index => $item) {
                // Find DataSertifikasi
                $dataAsesi = DataSertifikasiAsesi::where('id_asesi', $item['id_asesi'])
                                ->where('id_jadwal', $id_jadwal)
                                ->first();
                
                if ($dataAsesi) {
                    // Update Status di DataSertifikasiAsesi (untuk flow sistem)
                    $dataAsesi->update([
                        'rekomendasi_AK05' => ($item['rekomendasi'] == 'K') ? 'kompeten' : 'belum kompeten',
                        // 'catatan_AK05' => $request->catatan_akhir, // Moved to KomentarAk05 as per request
                        'status_sertifikasi' => ($item['rekomendasi'] == 'K') ? 'direkomendasikan' : 'tidak_direkomendasikan',
                    ]);

                    // Update/Create KomentarAk05
                    \App\Models\KomentarAk05::updateOrCreate(
                        ['id_data_sertifikasi_asesi' => $dataAsesi->id_data_sertifikasi_asesi],
                        [
                            'id_ak05' => $ak05->id_ak05,
                            'rekomendasi' => $item['rekomendasi'],
                            'keterangan' => $item['keterangan'],
                            'catatan_ak05' => $request->catatan_akhir, // As requested
                        ]
                    );
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
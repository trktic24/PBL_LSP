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

    /**
     * Menampilkan Template Form FR.AK.05 (Admin Master View)
     */
    public function adminShow($id_skema)
    {
        $skema = \App\Models\Skema::findOrFail($id_skema);

        $query = \App\Models\DataSertifikasiAsesi::with([
            'asesi.dataPekerjaan',
            'jadwal.skema',
            'jadwal.masterTuk',
            'jadwal.asesor',
            'responApl2Ia01',
            'responBuktiAk01',
            'lembarJawabIa05',
            'komentarAk05'
        ])->whereHas('jadwal', function($q) use ($id_skema) {
            $q->where('id_skema', $id_skema);
        });

        if (request('search')) {
            $search = request('search');
            $query->whereHas('asesi', function($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%");
            });
        }

        $pendaftar = $query->paginate(request('per_page', 10))->withQueryString();

        $user = auth()->user();
        $asesor = new \App\Models\Asesor();
        $asesor->id_asesor = 0;
        $asesor->nama_lengkap = $user ? $user->name : 'Administrator';
        $asesor->pas_foto = $user ? $user->profile_photo_path : null;
        $asesor->status_verifikasi = 'approved';
        $asesor->setRelation('skemas', collect());
        $asesor->setRelation('jadwals', collect());
        $asesor->setRelation('skema', null);

        $jadwal = new \App\Models\Jadwal([
            'tanggal_pelaksanaan' => now(),
            'waktu_mulai' => '08:00',
        ]);
        $jadwal->setRelation('skema', $skema);
        $jadwal->setRelation('masterTuk', new \App\Models\MasterTUK(['nama_lokasi' => 'Semua TUK (Filter Skema)']));

        return view('Admin.master.skema.daftar_asesi', [
            'pendaftar' => $pendaftar,
            'asesor' => $asesor,
            'jadwal' => $jadwal,
            'isMasterView' => true,
            'sortColumn' => request('sort', 'nama_lengkap'),
            'sortDirection' => request('direction', 'asc'),
            'perPage' => request('per_page', 10),
            'targetRoute' => 'admin.ak05.view', 
            'buttonLabel' => 'FR.AK.05',
        ]);
    }
    
    /**
     * Helper to show AK.05 from Sertifikasi ID (for Admin redirection)
     */
    public function showBySertifikasi($id_sertifikasi)
    {
        $sertifikasi = DataSertifikasiAsesi::findOrFail($id_sertifikasi);
        return redirect()->route('ak05.index', $sertifikasi->id_jadwal);
    }
}
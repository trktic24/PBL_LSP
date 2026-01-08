<?php

namespace App\Http\Controllers\Asesi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DataSertifikasiAsesi;
use App\Models\ResponAk04;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Ak04Controller extends Controller
{
    // 1. MENAMPILKAN FORM AK-04
    public function create($id_sertifikasi)
    {
        $asesi_id = Auth::user()->asesi->id_asesi;

        // Ambil data sertifikasi
        $sertifikasi = DataSertifikasiAsesi::with(['jadwal.skema', 'jadwal.asesor', 'asesi'])
            ->where('id_asesi', $asesi_id)
            ->findOrFail($id_sertifikasi);

        // Ambil respon lama (jika ada) untuk ditampilkan kembali (edit mode)
        $respon = ResponAk04::where('id_data_sertifikasi_asesi', $id_sertifikasi)->first();

        return view('frontend.FR_AK_04', compact('sertifikasi', 'respon'));
    }

    // 2. MENYIMPAN DATA (STORE)
    public function store(Request $request, $id_sertifikasi)
    {
        // Validasi
        $request->validate([
            'banding_1' => 'required|in:ya,tidak',
            'banding_2' => 'required|in:ya,tidak',
            'banding_3' => 'required|in:ya,tidak',
            'alasan_banding' => 'nullable|string|max:1000',
        ]);

        DB::beginTransaction();
        try {
            // Konversi "ya" => 1, "tidak" => 0
            $dataSimpan = [
                'id_data_sertifikasi_asesi' => $id_sertifikasi,
                'penjelasan_banding' => $request->banding_1 == 'ya' ? 1 : 0,
                'diskusi_dengan_asesor' => $request->banding_2 == 'ya' ? 1 : 0,
                'melibatkan_orang_lain' => $request->banding_3 == 'ya' ? 1 : 0,
                'alasan_banding' => $request->alasan_banding,
                'tanggal_pengajuan' => now(), // Tambahkan tanggal jika ada kolomnya
            ];

            // Simpan atau Update
            ResponAk04::updateOrCreate(
                ['id_data_sertifikasi_asesi' => $id_sertifikasi], // Kunci pencarian
                $dataSimpan
            );

            // Opsional: Update Status Sertifikasi jadi "banding_diajukan"
            $sertifikasi = DataSertifikasiAsesi::find($id_sertifikasi);
            // $sertifikasi->status_sertifikasi = 'banding_diajukan'; // Uncomment jika perlu
            // $sertifikasi->save();

            DB::commit();
            return redirect()->route('tracker')->with('success', 'Pengajuan Banding (AK-04) berhasil dikirim.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal menyimpan data: ' . $e->getMessage());
        }
    }

    /**
     * Menampilkan Template Form FR.AK.04 (Admin Master View)
     */
    public function adminShow($id_skema)
    {
        $skema = \App\Models\Skema::findOrFail($id_skema);

        $query = DataSertifikasiAsesi::with([
            'asesi.dataPekerjaan',
            'jadwal.skema',
            'jadwal.masterTuk',
            'jadwal.asesor'
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

        return view('Admin.profile_asesor.daftar_asesi', [
            'pendaftar' => $pendaftar,
            'asesor' => $asesor,
            'jadwal' => $jadwal,
            'isMasterView' => true,
            'sortColumn' => request('sort', 'nama_lengkap'),
            'sortDirection' => request('direction', 'asc'),
            'perPage' => request('per_page', 10),
            'targetRoute' => 'ak04.create',
            'buttonLabel' => 'FR.AK.04',
        ]);
    }
}
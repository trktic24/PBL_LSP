<?php

namespace App\Http\Controllers\Asesi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DataSertifikasiAsesi;
use App\Models\ResponAk04;
use App\Models\MasterFormTemplate;
use App\Models\Skema;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Ak04Controller extends Controller
{
    // ============================================================================
    // 🛡️ HELPER: CEK ROLE
    // ============================================================================
    private function isAdmin()
    {
        $role = Auth::user()->role->nama_role ?? '';
        return in_array($role, ['admin', 'superadmin']);
    }

    private function isAsesor()
    {
        return Auth::check() && Auth::user()->role->nama_role === 'asesor';
    }

    // 1. MENAMPILKAN FORM AK-04
    public function create($id_sertifikasi)
    {
        $query = DataSertifikasiAsesi::with(['jadwal.skema', 'jadwal.asesor', 'asesi']);

        // Jika bukan admin/asesor, batasi hanya data milik asesi yang bersangkutan
        if (!$this->isAdmin() && !$this->isAsesor()) {
            $asesi = Auth::user()->asesi;
            if (!$asesi) {
                abort(403, 'Profil asesi tidak ditemukan.');
            }
            $query->where('id_asesi', $asesi->id_asesi);
        }

        $sertifikasi = $query->findOrFail($id_sertifikasi);

        // Ambil respon lama (jika ada) untuk ditampilkan kembali (edit mode)
        $respon = ResponAk04::where('id_data_sertifikasi_asesi', $id_sertifikasi)->first();

        // [AUTO-LOAD TEMPLATE]
        $template = MasterFormTemplate::where('id_skema', $sertifikasi->jadwal->id_skema)
                                    ->where('form_code', 'FR.AK.04')
                                    ->first();

        return view('frontend.FR_AK_04', [
            'sertifikasi' => $sertifikasi,
            'respon' => $respon,
            'template' => $template ? $template->content : null
        ]);
    }

    // 2. MENYIMPAN DATA (STORE)
    public function store(Request $request, $id_sertifikasi)
    {
        $query = DataSertifikasiAsesi::query();

        // Jika bukan admin/asesor, batasi hanya data milik asesi yang bersangkutan
        if (!$this->isAdmin() && !$this->isAsesor()) {
            $asesi = Auth::user()->asesi;
            if (!$asesi) {
                abort(403, 'Profil asesi tidak ditemukan.');
            }
            $query->where('id_asesi', $asesi->id_asesi);
        }

        $sertifikasi = $query->findOrFail($id_sertifikasi);

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
     * [MASTER] Menampilkan editor template (Banding Asesmen) per Skema
     */
    public function editTemplate($id_skema)
    {
        $skema = Skema::findOrFail($id_skema);
        $template = MasterFormTemplate::where('id_skema', $id_skema)
                                    ->where('form_code', 'FR.AK.04')
                                    ->first();
        
        $content = $template ? $template->content : [
            'q1' => 'Apakah Proses Banding telah dijelaskan kepada Anda?',
            'q2' => 'Apakah Anda telah mendiskusikan Banding dengan Asesor?',
            'q3' => 'Apakah Anda mau melibatkan "orang lain" membantu Anda dalam Proses Banding?',
            'default_alasan' => ''
        ];

        return view('Admin.master.skema.template.ak04', [
            'skema' => $skema,
            'content' => $content
        ]);
    }

    /**
     * [MASTER] Simpan/Update template per Skema
     */
    public function storeTemplate(Request $request, $id_skema)
    {
        $request->validate([
            'content' => 'required|array',
            'content.q1' => 'required|string',
            'content.q2' => 'required|string',
            'content.q3' => 'required|string',
            'content.default_alasan' => 'nullable|string',
        ]);

        MasterFormTemplate::updateOrCreate(
            ['id_skema' => $id_skema, 'form_code' => 'FR.AK.04'],
            ['content' => $request->content]
        );

        return redirect()->back()->with('success', 'Templat AK-04 berhasil diperbarui.');
    }

    /**
     * Menampilkan Template Form FR.AK.04 (Admin Master View) - DEPRECATED for management
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
            'targetRoute' => 'asesi.banding.fr_ak04',
            'buttonLabel' => 'FR.AK.04',
        ]);
    }
}
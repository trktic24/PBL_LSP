<?php

namespace App\Http\Controllers\Asesi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DataSertifikasiAsesi;
use App\Models\MasterFormTemplate;
use App\Models\Skema;
use Illuminate\Support\Facades\Auth;

class Ak01Controller extends Controller
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

    // 1. MENAMPILKAN FORM AK-01
    public function create($id_sertifikasi)
    {
        $query = DataSertifikasiAsesi::with(['jadwal.asesor', 'asesi']);

        // Jika bukan admin/asesor, batasi hanya data milik asesi yang bersangkutan
        if (!$this->isAdmin() && !$this->isAsesor()) {
            $asesi = Auth::user()->asesi;
            if (!$asesi) {
                abort(403, 'Profil asesi tidak ditemukan.');
            }
            $query->where('id_asesi', $asesi->id_asesi);
        }

        $sertifikasi = $query->findOrFail($id_sertifikasi);
        $asesi = $sertifikasi->asesi;

        // [AUTO-LOAD TEMPLATE]
        $template = MasterFormTemplate::where('id_skema', $sertifikasi->jadwal->id_skema)
                                    ->where('form_code', 'FR.AK.01')
                                    ->first();

        // Kirim variabel $sertifikasi and $asesi ke View
        return view('frontend.FR_AK_01', [
            'sertifikasi' => $sertifikasi,
            'asesi' => $asesi,
            'template' => $template ? $template->content : null
        ]);
    }

    // 2. MENYIMPAN PERSETUJUAN (STORE)
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

        // Disini logikanya: Update status atau simpan ke tabel khusus AK01
        // Contoh: Set status bahwa AK-01 telah ditandatangani
        
        return redirect()->route('jadwal.index')->with('success', 'Persetujuan Asesmen (AK-01) berhasil disetujui.');
    }

    /**
     * [MASTER] Menampilkan editor template (Persetujuan Asesmen) per Skema
     */
    public function editTemplate($id_skema)
    {
        $skema = Skema::findOrFail($id_skema);
        $template = MasterFormTemplate::where('id_skema', $id_skema)
                                    ->where('form_code', 'FR.AK.01')
                                    ->first();
        
        $content = $template ? $template->content : [
            'pernyataan_1' => 'Bahwa saya sudah mendapatkan penjelasan Hak dan Prosedur Banding oleh Asesor.',
            'pernyataan_2' => 'Saya setuju mengikuti asesmen dengan pemahaman bahwa informasi yang dikumpulkan hanya digunakan untuk pengembangan profesional dan hanya dapat diakses oleh orang tertentu saja.'
        ];

        return view('Admin.master.skema.template.ak01', [
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
            'content.pernyataan_1' => 'required|string',
            'content.pernyataan_2' => 'required|string',
        ]);

        MasterFormTemplate::updateOrCreate(
            ['id_skema' => $id_skema, 'form_code' => 'FR.AK.01'],
            ['content' => $request->content]
        );

        return redirect()->back()->with('success', 'Templat AK-01 berhasil diperbarui.');
    }

    /**
     * Menampilkan Template Form FR.AK.01 (Admin Master View) - DEPRECATED for management
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
            'targetRoute' => 'ak01.create',
            'buttonLabel' => 'FR.AK.01',
        ]);
    }
}
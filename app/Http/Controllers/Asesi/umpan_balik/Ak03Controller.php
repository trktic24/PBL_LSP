<?php

namespace App\Http\Controllers\Asesi\umpan_balik;

use App\Models\PoinAk03;
use Illuminate\Http\Request;
use App\Models\ResponHasilAk03;
use App\Http\Controllers\Controller;
use App\Models\DataSertifikasiAsesi;
use App\Models\MasterFormTemplate;
use App\Models\Skema;
use Illuminate\Support\Facades\Auth;

class Ak03Controller extends Controller
{
    /**
     * MENAMPILKAN FORM
     */
    public function index($id)
    {
        return $this->create($id);
    }

    public function create($id)
    {
        $user = Auth::user();
        
        // Cek apakah admin/superadmin
        $isAdmin = $user->hasRole('admin') || $user->hasRole('superadmin');
        $asesi = $user->asesi;
        
        if (!$isAdmin && !$asesi) {
            abort(403, 'Akses ditolak. User bukan asesi.');
        }

        // 1. Ambil Data Sertifikasi Lengkap
        $query = DataSertifikasiAsesi::where('id_data_sertifikasi_asesi', $id)
            ->with([
                'jadwal.skema',
                'jadwal.asesor',
                'jadwal.jenisTuk',
                'asesi'
            ]);

        // Jika bukan admin, harus milik sendiri
        if (!$isAdmin) {
            $query->where('id_asesi', $asesi->id_asesi);
        }

        $sertifikasi = $query->firstOrFail();
        
        // Re-assign asesi from sertifikasi if viewing as admin
        if ($isAdmin) {
            $asesi = $sertifikasi->asesi;
        }

        // 2. Cek apakah sudah mengisi
        $sudahIsi = ResponHasilAk03::where('id_data_sertifikasi_asesi', $id)->exists();

        if ($sudahIsi) {
            // Return ke view BERHASIL dengan data LENGKAP
            return view('asesi.tunggu_or_berhasil.berhasil', [
                'id_sertifikasi'     => $id,
                'id_jadwal_redirect' => $sertifikasi->id_jadwal,
                'asesi'              => $asesi,       // <--- INI SOLUSI ERRORNYA
                'sertifikasi'        => $sertifikasi  // <--- INI JUGA WAJIB
            ]); 
        }

        // 3. Jika Belum Mengisi, Tampilkan Form
        $template = MasterFormTemplate::where('id_skema', $sertifikasi->jadwal->id_skema)
                                    ->where('id_jadwal', $sertifikasi->id_jadwal)
                                    ->where('form_code', 'FR.AK.03')
                                    ->first();
        
        if (!$template) {
            $template = MasterFormTemplate::where('id_skema', $sertifikasi->jadwal->id_skema)
                                        ->whereNull('id_jadwal')
                                        ->where('form_code', 'FR.AK.03')
                                        ->first();
        }

        $komponen = PoinAk03::all();
        if ($template && isset($template->content['selected_points'])) {
            $komponen = $komponen->whereIn('id_poin_ak03', $template->content['selected_points']);
        }

        return view('asesi.umpan_balik.umpan_balik', [
            'komponen'    => $komponen,
            'sertifikasi' => $sertifikasi,
            'asesi'       => $asesi,
            'template'    => $template ? $template->content : null
        ]);
    }

    /**
     * MENYIMPAN JAWABAN (Store tetap sama seperti yang terakhir bener)
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

        // Ambil data sertifikasi untuk ID Jadwal
        $sertifikasi = DataSertifikasiAsesi::where('id_data_sertifikasi_asesi', $id)
            ->where('id_asesi', $asesi->id_asesi)
            ->first();

        if (!$sertifikasi) {
            return redirect()->back()->with('error', 'Data sertifikasi tidak ditemukan.');
        }

        $idJadwalUntukRedirect = $sertifikasi->id_jadwal;

        // Cek Double Submit
        if (ResponHasilAk03::where('id_data_sertifikasi_asesi', $id)->exists()) {
            return redirect()->route('asesi.tracker', ['id' => $idJadwalUntukRedirect])
                ->with('warning', 'Anda sudah mengisi umpan balik sebelumnya.');
        }

        // 2. Simpan Jawaban
        foreach ($request->jawaban as $id_poin => $data) {
            ResponHasilAk03::create([ 
                'id_data_sertifikasi_asesi' => $id,
                'id_poin_ak03'              => $id_poin,
                'hasil'   => $data['hasil'] ?? null,
                'catatan' => $data['catatan'] ?? null,
            ]);
        }

        // 3. Update Status Sertifikasi
        $sertifikasi->update([
            'catatan_asesi_AK03' => $request->catatan_tambahan,
            // Pastikan constant ini ada, atau ganti string manual 'umpan_balik_selesai'
            'status_sertifikasi' => DataSertifikasiAsesi::STATUS_UMPAN_BALIK_SELESAI 
        ]);

        // 4. Redirect
        return redirect()->route('asesi.tracker', ['id' => $idJadwalUntukRedirect]) 
            ->with('success', 'Umpan balik berhasil dikirim! Terima kasih.');
    }

    /**
     * [MASTER] Menampilkan editor template (Umpan Balik) per Skema & Jadwal
     */
    public function editTemplate($id_skema, $id_jadwal)
    {
        $skema = Skema::findOrFail($id_skema);
        $template = MasterFormTemplate::where('id_skema', $id_skema)
                                    ->where('id_jadwal', $id_jadwal)
                                    ->where('form_code', 'FR.AK.03')
                                    ->first();
        
        $allPoints = PoinAk03::all();
        $content = $template ? $template->content : [
            'selected_points' => $allPoints->pluck('id_poin_ak03')->toArray(),
            'catatan_tambahan' => ''
        ];

        return view('Admin.master.skema.template.ak03', [
            'skema' => $skema,
            'id_jadwal' => $id_jadwal,
            'allPoints' => $allPoints,
            'content' => $content
        ]);
    }

    /**
     * [MASTER] Simpan/Update template per Skema & Jadwal
     */
    public function storeTemplate(Request $request, $id_skema, $id_jadwal)
    {
        $request->validate([
            'content' => 'required|array',
            'content.selected_points' => 'required|array',
            'content.catatan_tambahan' => 'nullable|string',
        ]);

        MasterFormTemplate::updateOrCreate(
            [
                'id_skema' => $id_skema, 
                'id_jadwal' => $id_jadwal,
                'form_code' => 'FR.AK.03'
            ],
            ['content' => $request->content]
        );

        return redirect()->back()->with('success', 'Templat AK-03 berhasil diperbarui.');
    }

    /**
     * Menampilkan Template Form FR.AK.03 (Admin Master View)
     */
    public function adminShow($id_skema)
    {
        $skema = \App\Models\Skema::findOrFail($id_skema);

        $query = \App\Models\DataSertifikasiAsesi::with([
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

        return view('Admin.master.skema.daftar_asesi', [
            'pendaftar' => $pendaftar,
            'asesor' => $asesor,
            'jadwal' => $jadwal,
            'isMasterView' => true,
            'sortColumn' => request('sort', 'nama_lengkap'),
            'sortDirection' => request('direction', 'asc'),
            'perPage' => request('per_page', 10),
            'targetRoute' => 'asesi.ak03.index',
            'buttonLabel' => 'FR.AK.03',
            'formName' => 'Umpan Balik dan Catatan Asesmen',
        ]);
    }
}
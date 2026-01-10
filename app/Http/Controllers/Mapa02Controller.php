<?php

namespace App\Http\Controllers;

use App\Models\Mapa02;
use App\Models\DataSertifikasiAsesi;
use App\Models\MasterFormTemplate;
use App\Models\Skema;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class Mapa02Controller extends Controller
{
    /**
     * Menampilkan halaman FR MAPA 02
     */
    public function show(string $id_data_sertifikasi_asesi)
    {
        $userRole = Auth::user()->role_id;

        // Asesi (2) tidak boleh akses
        if ($userRole == 2) {
            abort(403, 'ANDA TIDAK MEMILIKI AKSES KE HALAMAN INI.');
        }

        // Ambil data sertifikasi beserta relasinya
        $sertifikasi = DataSertifikasiAsesi::with([
            'asesi',
            'jadwal.masterTuk',
            'jadwal.skema',
            'jadwal.skema.asesor',
            'jadwal.skema.kelompokPekerjaan.unitKompetensi',
        ])->find($id_data_sertifikasi_asesi);

        if (!$sertifikasi) {
            return redirect()
                ->route('daftar_asesi')
                ->with('error', 'Data Sertifikasi tidak ditemukan.');
        }

        // Ambil SEMUA data Mapa02 untuk sertifikasi ini
        $mapa02Collection = Mapa02::where('id_data_sertifikasi_asesi', $id_data_sertifikasi_asesi)->get();

        $mapa02Map = [];
        $templateData = null;

        if ($mapa02Collection->isEmpty()) {
            // [AUTO-LOAD TEMPLATE]
            $template = MasterFormTemplate::where('id_skema', $sertifikasi->jadwal->id_skema)
                                        ->where('form_code', 'FR.MAPA.02')
                                        ->first();
            if ($template && !empty($template->content)) {
                $templateData = $template->content;
            }
        } else {
            foreach ($mapa02Collection as $item) {
                $mapa02Map[$item->id_kelompok_pekerjaan][$item->instrumen_asesmen] = $item->potensi_asesi;
            }
        }

        // Yang boleh edit: Asesor (3) & Superadmin (4)
        // Admin (1) hanya view
        $canEdit = in_array($userRole, [3, 4]);

        return view('frontend.FR_MAPA_02', [
            'sertifikasi' => $sertifikasi,
            'mapa02Map' => $mapa02Map,
            'template' => $templateData,
            'canEdit' => $canEdit,
            'jadwal' => $sertifikasi->jadwal,
            'asesi' => $sertifikasi->asesi,
        ]);
    }

    /**
     * Menyimpan atau mengupdate data MAPA 02
     */
    public function store(Request $request, string $id_sertifikasi)
    {
        $userRole = Auth::user()->role_id;

        // Hanya Asesor (3) atau Superadmin (4) yang boleh simpan
        if (!in_array($userRole, [3, 4])) {
            abort(403, 'ANDA TIDAK MEMILIKI AKSES UNTUK MENGUBAH DATA INI.');
        }

        $validated = $request->validate([
            'potensi' => 'required|array',
            'potensi.*' => 'required|array', // id_kp => array of instruments
            'potensi.*.*' => 'required|in:1,2,3,4,5', // instrument => value
        ]);

        // Loop setiap Kelompok Pekerjaan
        foreach ($validated['potensi'] as $id_kp => $instruments) {
            // Loop setiap Instrumen dalam KP tersebut
            foreach ($instruments as $instrumen => $nilai) {
                Mapa02::updateOrCreate(
                    [
                        'id_data_sertifikasi_asesi' => $id_sertifikasi,
                        'id_kelompok_pekerjaan' => $id_kp,
                        'instrumen_asesmen' => $instrumen,
                    ],
                    [
                        'potensi_asesi' => $nilai,
                    ]
                );
            }
        }

        return redirect()
            ->back()
            ->with('success', 'Data MAPA 02 berhasil disimpan.');
    }

    public function cetakPDF($id_data_sertifikasi_asesi)
    {
        // 1. Ambil Data Utama dengan LENGKAP (Eager Loading)
        $sertifikasi = DataSertifikasiAsesi::with([
            'asesi',                                          // Data Asesi
            'jadwal.masterTuk',                               // Data TUK
            'jadwal.skema.asesor',                            // Data Asesor
            'jadwal.skema.kelompokPekerjaan.unitKompetensi',  // Data Unit Kompetensi
        ])->find($id_data_sertifikasi_asesi);

        if (!$sertifikasi) {
            return redirect()->back()->with('error', 'Data tidak ditemukan.');
        }

        // 2. Ambil Data Isian MAPA-02 (Centang/Checklist)
        $mapa02Collection = Mapa02::where('id_data_sertifikasi_asesi', $id_data_sertifikasi_asesi)->get();

        // Mapping data agar mudah dicek di View PDF
        $mapa02Map = [];
        foreach ($mapa02Collection as $item) {
            $mapa02Map[$item->id_kelompok_pekerjaan][$item->instrumen_asesmen] = $item->potensi_asesi;
        }

        // 3. Render PDF
        $pdf = Pdf::loadView('pdf.MAPA_02', [
            'sertifikasi' => $sertifikasi,
            'mapa02Map' => $mapa02Map
        ]);

        $pdf->setPaper('A4', 'portrait');

        // 4. Stream (Preview di browser)
        return $pdf->stream('FR_MAPA_02_' . $sertifikasi->asesi->nama_lengkap . '.pdf');
    }

    /**
     * [MASTER] Menampilkan editor template (Peta Instrumen) per Skema
     */
    public function editTemplate($id_skema)
    {
        $skema = Skema::with(['kelompokPekerjaan'])->findOrFail($id_skema);
        $template = MasterFormTemplate::where('id_skema', $id_skema)
                                    ->where('form_code', 'FR.MAPA.02')
                                    ->first();
        
        $content = $template ? $template->content : [];

        return view('Admin.master.skema.template.mapa02', [
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
            'content' => 'required|array'
        ]);

        MasterFormTemplate::updateOrCreate(
            ['id_skema' => $id_skema, 'form_code' => 'FR.MAPA.02'],
            ['content' => $request->content]
        );

        return redirect()->back()->with('success', 'Templat MAPA-02 berhasil diperbarui.');
    }

    /**
     * Menampilkan Template Form MAPA-02 (Admin Master View) - DEPRECATED for management
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
            'targetRoute' => 'mapa02.show',
            'buttonLabel' => 'FR.MAPA.02',
            'formName' => 'Peta Instrumen Asesmen',
        ]);
    }
}

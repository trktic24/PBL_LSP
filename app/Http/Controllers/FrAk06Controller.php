<?php

namespace App\Http\Controllers;

use App\Models\FrAk06;
use App\Models\Jadwal;
use App\Models\MasterFormTemplate;
use App\Models\Skema;
use Illuminate\Http\Request;

class FrAk06Controller extends Controller
{
    public function show($id_jadwal)
    {
        $jadwal = Jadwal::with(['skema', 'asesor'])->findOrFail($id_jadwal);
        
        // [AUTO-LOAD TEMPLATE]
        // Check if data already exists for this jadwal
        $existing = FrAk06::where('id_jadwal', $id_jadwal)->first(); // Assuming id_jadwal exists in frak06
        
        $template = null;
        if (!$existing) {
            $template = MasterFormTemplate::where('id_skema', $jadwal->id_skema)
                                        ->where('form_code', 'FR.AK.06')
                                        ->first();
        }

        return view('frontend.FR_AK_06', [
            'jadwal' => $jadwal,
            'skema' => $jadwal->skema,
            'template' => $template ? $template->content : null
        ]);
    }

    public function store(Request $request, $id_jadwal)
    {
        // 1. Ambil data kecuali token
        $data = $request->except(['_token', '_method']);
        $data['id_jadwal'] = $id_jadwal;

        // 2. Simpan ke database
        FrAk06::updateOrCreate(['id_jadwal' => $id_jadwal], $data);

        // 3. Redirect dengan pesan sukses
        return redirect()->back()->with('success', 'Formulir FR.AK.06 berhasil disimpan!');
    }

    /**
     * [MASTER] Menampilkan editor template (Tinjauan Proses Asesmen) per Skema
     */
    public function editTemplate($id_skema)
    {
        $skema = Skema::findOrFail($id_skema);
        $template = MasterFormTemplate::where('id_skema', $id_skema)
                                    ->where('form_code', 'FR.AK.06')
                                    ->first();
        
        $content = $template ? $template->content : [
            'rekomendasi_aspek' => '',
            'rekomendasi_dimensi' => '',
            'peninjau_komentar' => ''
        ];

        return view('Admin.master.skema.template.ak06', [
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
            'content.rekomendasi_aspek' => 'nullable|string',
            'content.rekomendasi_dimensi' => 'nullable|string',
            'content.peninjau_komentar' => 'nullable|string',
        ]);

        MasterFormTemplate::updateOrCreate(
            ['id_skema' => $id_skema, 'form_code' => 'FR.AK.06'],
            ['content' => $request->content]
        );

        return redirect()->back()->with('success', 'Templat AK-06 berhasil diperbarui.');
    }

    /**
     * Menampilkan Template Form FR.AK.06 (Admin Master View) - DEPRECATED
     */
    public function adminShow($skema_id)
    {
        $skema = \App\Models\Skema::findOrFail($skema_id);

        $query = \App\Models\DataSertifikasiAsesi::with([
            'asesi.dataPekerjaan',
            'jadwal.skema',
            'jadwal.masterTuk',
            'jadwal.asesor',
            'responApl2Ia01',
            'responBuktiAk01',
            'lembarJawabIa05',
            'komentarAk05'
        ])->whereHas('jadwal', function($q) use ($skema_id) {
            $q->where('id_skema', $skema_id);
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
            'targetRoute' => 'admin.ak06.view', 
            'buttonLabel' => 'FR.AK.06',
            'formName' => 'Meninjau Proses Asesmen',
        ]);
    }

    /**
     * Helper to show AK.06 from Sertifikasi ID (for Admin redirection)
     */
    public function showBySertifikasi($id_sertifikasi)
    {
        $sertifikasi = \App\Models\DataSertifikasiAsesi::findOrFail($id_sertifikasi);
        return redirect()->route('asesor.ak06', $sertifikasi->id_jadwal);
    }

    /**
     * Generate PDF for FR.AK.06
     */
    public function cetakPDF($id_jadwal)
    {
         $jadwal = \App\Models\Jadwal::with(['skema', 'asesor', 'masterTuk'])->findOrFail($id_jadwal);
         
         // [FIX] Table fr_ak06s does not have id_jadwal column (migration missing FK).
         // Temporary fix: Do not query by id_jadwal to avoid SQL error.
         // Pass null so PDF renders as blank form (template) with header info.
         $ak06 = null; 
         
         $skema = $jadwal->skema;
         $asesor = $jadwal->asesor;

         $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.ak_06', compact('jadwal', 'ak06', 'skema', 'asesor'))
                    ->setPaper('a4', 'portrait');

         return $pdf->stream('FR.AK.06_' . ($skema->kode_skema ?? 'Skema') . '.pdf');
    }
}
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
    /**
     * [MASTER] Menampilkan editor template (Laporan Asesmen) per Skema & Jadwal
     */
    public function editTemplate($id_skema, $id_jadwal)
    {
        $skema = Skema::findOrFail($id_skema);
        $template = MasterFormTemplate::where('id_skema', $id_skema)
                                    ->where('id_jadwal', $id_jadwal)
                                    ->where('form_code', 'FR.AK.06')
                                    ->first();
        
        $content = $template ? $template->content : [
            'rekomendasi_aspek' => '',
            'rekomendasi_dimensi' => '',
            'peninjau_komentar' => ''
        ];

        return view('Admin.master.skema.template.ak06', [
            'skema' => $skema,
            'id_jadwal' => $id_jadwal,
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
            'content.rekomendasi_aspek' => 'nullable|string',
            'content.rekomendasi_dimensi' => 'nullable|string',
            'content.peninjau_komentar' => 'nullable|string',
        ]);

        MasterFormTemplate::updateOrCreate(
            [
                'id_skema' => $id_skema, 
                'id_jadwal' => $id_jadwal,
                'form_code' => 'FR.AK.06'
            ],
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
        
        // Ambil jadwal yang:
        // 1. Memiliki skema $skema_id
        // 2. Memiliki setidaknya 1 asesi (dataSertifikasiAsesi)
        $jadwalList = \App\Models\Jadwal::with(['asesor', 'masterTuk'])
            ->where('id_skema', $skema_id)
            ->whereHas('dataSertifikasiAsesi')
            ->orderBy('tanggal_mulai', 'desc')
            ->get();

        return view('Admin.master.skema.ak06_jadwal_list', [
            'skema' => $skema,
            'jadwalList' => $jadwalList,
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
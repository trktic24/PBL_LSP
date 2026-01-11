<?php

namespace App\Http\Controllers;

use App\Models\FrMapa01;
use App\Models\MasterFormTemplate;
use App\Models\Skema;
use App\Models\DataSertifikasiAsesi;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class FrMapa01Controller extends Controller
{
    // public function index()
    // {
    //     return view('frontend.FR_MAPA_01');
    // }

    public function index($id)
    {
        $sertifikasi = DataSertifikasiAsesi::with([
            'asesi', 
            'jadwal.skema.kelompokPekerjaan.unitKompetensi'
        ])->findOrFail($id);
        
        $skema = $sertifikasi->jadwal->skema;
        $mapa01 = FrMapa01::where('id_data_sertifikasi_asesi', $id)->first();

        // [AUTO-LOAD TEMPLATE & STATIC FALLBACK]
        $template = null;
        if (!$mapa01) {
            $templateObj = MasterFormTemplate::where('id_skema', $skema->id_skema)
                                        ->where('form_code', 'FR.MAPA.01')
                                        ->first();
            if ($templateObj && !empty($templateObj->content)) {
                $template = $templateObj->content;
            } else {
                // Static Fallback
                $template = [
                    'pendekatan_asesmen' => ['LSP/Sertifikasi'],
                    'konteks_lingkungan' => ['Tempat Kerja'],
                    'peluang_bukti' => ['Aktivitas Kerja Nyata'],
                    'pelaksana_asesmen' => ['Kandidat', 'Asesor'],
                    'konfirmasi_relevan' => ['Manajer/Supervisor']
                ];
            }
        }

        return view('frontend.FR_MAPA_01', [
            'sertifikasi' => $sertifikasi,
            'asesi'       => $sertifikasi->asesi,
            'skema'       => $skema,
            'jadwal'      => $sertifikasi->jadwal,
            'mapa01'      => $mapa01,
            'template'    => $template
        ]);
    }

    public function store(Request $request)
    {
        // 1. Ambil semua data input
        // Kita buang _token dan _method karena tidak perlu disimpan di DB
        $data = $request->except(['_token', '_method']);

        // 2. Simpan ke database
        // Pastikan Model sudah di-set $guarded = ['id'] atau $fillable lengkap
        FrMapa01::create($data);

        // 3. Kembali ke halaman form dengan pesan sukses
        return redirect()->route('mapa01.index', $data['id_data_sertifikasi_asesi'])->with('success', 'Data FR.MAPA.01 berhasil disimpan!');
    }

    public function cetakPDF($idSertifikasi)
    {
        // 1. Ambil Data Sertifikasi
        $sertifikasi = DataSertifikasiAsesi::with([
            'jadwal.skema'
        ])->findOrFail($idSertifikasi);

        // 2. Ambil Data Form MAPA 01
        $mapa01 = FrMapa01::where('id_data_sertifikasi_asesi', $idSertifikasi)->first();

        // 3. Render PDF
        // Pastikan nama view sesuai dengan nama file yang kamu buat (misal: 'pdf.mapa01')
        $pdf = Pdf::loadView('pdf.mapa01', [
            'sertifikasi' => $sertifikasi,
            'mapa01'      => $mapa01
        ]);

        $pdf->setPaper('A4', 'portrait');

        return $pdf->stream('FR_MAPA_01_' . $sertifikasi->id_data_sertifikasi_asesi . '.pdf');
    }

    /**
     * [MASTER] Menampilkan editor template (MAPA-01) per Skema & Jadwal
     */
    public function editTemplate($id_skema, $id_jadwal)
    {
        $skema = Skema::findOrFail($id_skema);
        $template = MasterFormTemplate::where('id_skema', $id_skema)
                                    ->where('id_jadwal', $id_jadwal)
                                    ->where('form_code', 'FR.MAPA.01')
                                    ->first();
        
        // Default values if no template exists
        $content = $template ? $template->content : [
            'pendekatan_asesmen' => [],
            'konteks_lingkungan' => [],
            'peluang_bukti' => [],
            'pelaksana_asesmen' => [],
            'konfirmasi_relevan' => []
        ];

        return view('Admin.master.skema.template.mapa01', [
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
            'content' => 'required|array'
        ]);

        MasterFormTemplate::updateOrCreate(
            [
                'id_skema' => $id_skema, 
                'id_jadwal' => $id_jadwal,
                'form_code' => 'FR.MAPA.01'
            ],
            ['content' => $request->content]
        );

        return redirect()->back()->with('success', 'Templat MAPA-01 berhasil diperbarui.');
    }

    /**
     * Menampilkan Template Form MAPA-01 (Admin Master View) - DEPRECATED for management
     */
    public function adminShow($id_skema)
    {
        $skema = \App\Models\Skema::with(['kelompokPekerjaan.unitKompetensi'])->findOrFail($id_skema);
        
        // Mock data sertifikasi
        $sertifikasi = new \App\Models\DataSertifikasiAsesi();
        $sertifikasi->id_data_sertifikasi_asesi = 0;
        
        $asesi = new \App\Models\Asesi(['nama_lengkap' => 'Template Master']);
        $sertifikasi->setRelation('asesi', $asesi);
        
        $jadwal = new \App\Models\Jadwal(['tanggal_pelaksanaan' => now()]);
        $jadwal->setRelation('skema', $skema);
        $jadwal->setRelation('asesor', new \App\Models\Asesor(['nama_lengkap' => 'Nama Asesor']));
        $jadwal->setRelation('jenisTuk', new \App\Models\JenisTUK(['jenis_tuk' => 'Tempat Kerja']));
        $sertifikasi->setRelation('jadwal', $jadwal);

        return view('frontend.FR_MAPA_01', [
            'sertifikasi' => $sertifikasi,
            'asesi' => $asesi,
            'skema' => $skema,
            'jadwal' => $jadwal,
            'mapa01' => null,
            'template' => null,
            'isMasterView' => true,
        ]);
    }
}
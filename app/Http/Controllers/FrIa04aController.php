<?php

namespace App\Http\Controllers;

use App\Models\FrIa04a;
use App\Models\MasterFormTemplate;
use App\Models\Skema;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Barryvdh\DomPDF\Facade\Pdf;

class FrIa04aController extends Controller
{
    // 2. TAMBAHKAN INI DI DALAM CLASS
    use AuthorizesRequests; 

    // ... function index, store, dll tetap sama ...

    public function store(Request $request)
    {
        // Sekarang kode ini tidak akan error lagi
        $this->authorize('create', FrIa04a::class);
        
        // ... codingan kamu selanjutnya ...
    }

    public function update(Request $request, FrIa04a $frIa04a)
    {
        // Kode ini juga akan aman
        $this->authorize('update', $frIa04a);

        // ... codingan kamu selanjutnya ...
    }
    
    // ...

    // ... method store, update, dll ...

    /**
     * CETAK PDF FR.IA.04
     */
    public function cetakPDF($id_data_sertifikasi_asesi)
    {
        // 1. Ambil Data Sertifikasi
        $sertifikasi = \App\Models\DataSertifikasiAsesi::with([
            'asesi',
            'jadwal.masterTuk',
            'jadwal.asesor',
            'jadwal.skema',
            'jadwal.skema.kelompokPekerjaan.unitKompetensi' // Untuk header unit
        ])->findOrFail($id_data_sertifikasi_asesi);

        // 2. Ambil Poin (Tugas/Kegiatan)
        $points = \App\Models\PoinIA04A::where('id_data_sertifikasi_asesi', $id_data_sertifikasi_asesi)
            ->get();

        // 3. Ambil Respon (Jawaban/Validasi), keyBy ID Poin biar gampang dipanggil di View
        $responses = \App\Models\ResponIA04A::where('id_data_sertifikasi_asesi', $id_data_sertifikasi_asesi)
            ->get()
            ->keyBy('id_poin_ia04A');

        // 4. Ambil Unit Kompetensi (Untuk Header)
        $units = $sertifikasi->jadwal->skema->kelompokPekerjaan->flatMap->unitKompetensi;

        // 5. Render PDF
        $pdf = Pdf::loadView('pdf.ia_04', [
            'sertifikasi' => $sertifikasi,
            'points' => $points,
            'responses' => $responses,
            'units' => $units,
        ]);

        $pdf->setPaper('A4', 'portrait');

        $namaAsesi = preg_replace('/[^A-Za-z0-9\-]/', '_', $sertifikasi->asesi->nama_lengkap);
        return $pdf->stream('FR_IA_04_' . $namaAsesi . '.pdf');
    }

    /**
     * [MASTER] Menampilkan editor tamplate (Verifikasi Portofolio) per Skema
     */
    public function editTemplate($id_skema)
    {
        $skema = Skema::findOrFail($id_skema);
        $template = MasterFormTemplate::where('id_skema', $id_skema)
                                    ->where('form_code', 'FR.IA.04')
                                    ->first();
        
        // Default values if no template exists
        $points = $template ? $template->content : [];

        return view('Admin.master.skema.template.ia04', [
            'skema' => $skema,
            'points' => $points
        ]);
    }

    /**
     * [MASTER] Simpan/Update template per Skema
     */
    public function storeTemplate(Request $request, $id_skema)
    {
        $request->validate([
            'points' => 'required|array',
            'points.*.nama' => 'required|string',
            'points.*.kriteria' => 'nullable|string',
        ]);

        MasterFormTemplate::updateOrCreate(
            ['id_skema' => $id_skema, 'form_code' => 'FR.IA.04'],
            ['content' => $request->points]
        );

        return redirect()->back()->with('success', 'Templat IA-04 berhasil diperbarui.');
    }

    /**
     * Menampilkan Template Form FR.IA.04 (Admin Master View) - DEPRECATED for management
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

        // Required logic from AssessmenFRIA04tController@showIA04A
        $hal_yang_disiapkan_db = "1. Portofolio yang relevan dengan unit kompetensi.\n2. Dokumen pendukung keahlian.";
        $hal_yang_didemonstrasikan_db = "1. Verifikasi keaslian bukti.\n2. Konfirmasi kemutakhiran data.";

        return view('asesi.assesmen.FRIA04_Asesor', [
            'asesi' => $asesi,
            'asesor' => $jadwal->asesor,
            'skema' => $skema,
            'jenis_tuk_db' => 'tempat kerja',
            'judul_kegiatan_db' => 'Proyek Pembuatan Sistem Informasi Pendaftaran Mahasiswa Baru',
            'tanggal_pelaksanaan' => now()->format('d/m/Y'),
            'sertifikasi' => $sertifikasi,
            'poinIA04A' => null,
            'hal_yang_disiapkan_db' => $hal_yang_disiapkan_db,
            'hal_yang_didemonstrasikan_db' => $hal_yang_didemonstrasikan_db,
            'umpan_balik_asesi_db' => null,
            'tanda_tangan_asesor_path' => null,
            'tanda_tangan_asesi_path' => null,
            'ttdAsesorBase64' => null,
            'ttdAsesiBase64' => null,
            'rekomendasi_db' => null,
            'mockUnits' => $skema->kelompokPekerjaan->flatMap->unitKompetensi->map(fn($u) => ['code' => $u->kode_unit, 'title' => $u->judul_unit])->toArray(),
            'kelompok_pekerjaan' => $skema->kelompokPekerjaan->first()->nama_kelompok_pekerjaan ?? '-',
            'isMasterView' => true,
        ]);
    }
}
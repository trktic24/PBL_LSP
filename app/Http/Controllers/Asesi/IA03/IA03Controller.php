<?php

namespace App\Http\Controllers\Asesi\IA03;

use App\Models\IA03;
use App\Models\MasterFormTemplate;
use App\Models\Skema;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\DataSertifikasiAsesi;
use App\Models\MasterTUK;
use App\Models\Jadwal;
use App\Models\Asesor;
use App\Models\Asesi;

class IA03Controller extends Controller
{
    /**
     * INDEX → Menampilkan seluruh IA03 untuk 1 asesi (per jadwal)
     */
    public function index($id_data_sertifikasi_asesi)
    {
        // Ambil data sertifikasi asesi + relasi lengkap
        $sertifikasi = DataSertifikasiAsesi::with(['asesi', 'jadwal', 'jadwal.asesor', 'jadwal.skema', 'jadwal.jenisTuk', 'jadwal.masterTuk', 'jadwal.skema.kelompokPekerjaan', 'jadwal.skema.kelompokPekerjaan.unitKompetensi'])->findOrFail($id_data_sertifikasi_asesi);

        // Ambil seluruh pertanyaan IA03 milik asesi ini
        $pertanyaanIA03 = IA03::where('id_data_sertifikasi_asesi', $id_data_sertifikasi_asesi)->get();

        // [AUTO-LOAD TEMPLATE] Jika belum ada pertanyaan, ambil dari Master Template
        if ($pertanyaanIA03->isEmpty()) {
            $template = MasterFormTemplate::where('id_skema', $sertifikasi->jadwal->id_skema)
                                        ->where('form_code', 'FR.IA.03')
                                        ->first();
            if ($template && !empty($template->content)) {
                foreach ($template->content as $qText) {
                    IA03::create([
                        'id_data_sertifikasi_asesi' => $id_data_sertifikasi_asesi,
                        'pertanyaan' => $qText,
                        'jawaban' => '',
                        'pencapaian' => null,
                        'catatan_umpan_balik' => null
                    ]);
                }
                // Refresh collection
                $pertanyaanIA03 = IA03::where('id_data_sertifikasi_asesi', $id_data_sertifikasi_asesi)->get();
            }
        }
        $catatanUmpanBalik = $pertanyaanIA03
            ->pluck('catatan_umpan_balik')
            ->filter()
            ->map(function ($catatan) {
                return trim($catatan);
            })
            ->filter();

        if (empty($catatanUmpanBalik)) {
            $catatanUmpanBalik = null;
        }

        // Data yang butuh ditampilkan di view
        $asesi = $sertifikasi->asesi;
        $asesor = $sertifikasi->asesor; // lewat accessor
        $skema = $sertifikasi->skema;
        $jenisTuk = $sertifikasi->jenis_tuk;
        $tuk = $sertifikasi->tuk;
        $tanggal = $sertifikasi->tanggal_pelaksanaan;

        // Unit & kelompok pekerjaan
        $kelompokPekerjaan = $skema?->kelompokPekerjaan ?? collect();
        $unitKompetensi = $kelompokPekerjaan->first()?->unitKompetensi ?? collect();

        $trackerUrl = $sertifikasi->jadwal?->id_jadwal ? '/tracker/' . $sertifikasi->jadwal->id_jadwal : '/dashboard';
        $backUrl = session('backUrl', $trackerUrl);
        
        return view('asesi.ia03.index', compact('sertifikasi', 'asesi', 'asesor', 'skema', 'jenisTuk', 'tuk', 'tanggal', 'kelompokPekerjaan', 'unitKompetensi', 'pertanyaanIA03', 'trackerUrl', 'backUrl', 'catatanUmpanBalik'));
    }

    /**
     * SHOW → Menampilkan detail 1 pertanyaan IA03
     */
    public function show($id)
    {
        $ia03 = IA03::with('dataSertifikasiAsesi')->findOrFail($id);

        return view('asesi/ia03.show', compact('ia03'));
    }

    // ... method index dan show ...

    /**
     * CETAK PDF FR.IA.03
     */
    public function cetakPDF($id_data_sertifikasi_asesi)
    {
        // 1. Ambil Data Sertifikasi Lengkap
        $sertifikasi = DataSertifikasiAsesi::with([
            'asesi', 
            'jadwal.masterTuk', 
            'jadwal.asesor', 
            'jadwal.skema',
            'jadwal.skema.kelompokPekerjaan.unitKompetensi'
        ])->findOrFail($id_data_sertifikasi_asesi);

        // 2. Ambil Pertanyaan & Jawaban IA.03
        $pertanyaanIA03 = IA03::where('id_data_sertifikasi_asesi', $id_data_sertifikasi_asesi)->get();

        // 3. Logic: Cek Hasil Akhir (Kompeten/Belum)
        // Jika ada SATU saja yang pencapaiannya 0 (BK), maka Rekomendasi = Belum Kompeten
        $hasBK = $pertanyaanIA03->contains('pencapaian', 0);
        $rekomendasi = $hasBK ? 'Belum Kompeten' : 'Kompeten';

        // 4. Ambil Umpan Balik (Gabung jadi satu string jika banyak, atau ambil unik)
        $umpanBalik = $pertanyaanIA03->pluck('catatan_umpan_balik')
                        ->filter() // Hapus yang null/kosong
                        ->unique() // Ambil yang unik aja biar ga duplikat
                        ->implode(', '); // Gabung pakai koma

        // 5. Ambil Unit Kompetensi (Untuk Header)
        // Kita ambil semua unit yang ada di skema ini
        $units = $sertifikasi->jadwal->skema->kelompokPekerjaan->flatMap->unitKompetensi;

        // 6. Render PDF
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.ia_03', [
            'sertifikasi' => $sertifikasi,
            'pertanyaanIA03' => $pertanyaanIA03,
            'units' => $units,
            'umpanBalik' => $umpanBalik,
            'rekomendasi' => $rekomendasi
        ]);
        
        return $pdf->stream('FR.IA.03_Pertanyaan_Untuk_Mendukung_Observasi.pdf');
    }

    /**
     * [MASTER] Menampilkan editor tamplate (Pertanyaan Lisan) per Skema
     */
    public function editTemplate($id_skema)
    {
        $skema = Skema::findOrFail($id_skema);
        $template = MasterFormTemplate::where('id_skema', $id_skema)
                                    ->where('form_code', 'FR.IA.03')
                                    ->first();
        
        // Default values if no template exists
        $questions = $template ? $template->content : [];

        return view('Admin.master.skema.template.ia03', [
            'skema' => $skema,
            'questions' => $questions
        ]);
    }

    /**
     * [MASTER] Simpan/Update template per Skema
     */
    public function storeTemplate(Request $request, $id_skema)
    {
        $request->validate([
            'questions' => 'required|array',
            'questions.*' => 'required|string',
        ]);

        MasterFormTemplate::updateOrCreate(
            ['id_skema' => $id_skema, 'form_code' => 'FR.IA.03'],
            ['content' => $request->questions]
        );

        return redirect()->back()->with('success', 'Templat IA-03 berhasil diperbarui.');
    }

    /**
     * Menampilkan Template Form FR.IA.03 (Admin Master View) - DEPRECATED for management
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
            'targetRoute' => 'asesi.ia03.index',
            'buttonLabel' => 'FR.IA.03',
            'formName' => 'Pertanyaan Untuk Mendukung Observasi',
        ]);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\DataSertifikasiAsesi;
use App\Models\MasterFormTemplate;
use App\Models\Skema;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\DataPortofolio;
use App\Models\BuktiPortofolioIA08IA09;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;

class IA09Controller extends Controller
{
    /**
     * Pengekstrak data utama yang digunakan oleh Asesor dan Admin.
     */
    protected function prepareIA09Data($id_data_sertifikasi_asesi)
    {
        if ($id_data_sertifikasi_asesi == 0) {
            $dataSertifikasi = new DataSertifikasiAsesi();
            $dataSertifikasi->id_data_sertifikasi_asesi = 0;
            $dataSertifikasi->setRelation('asesi', new \App\Models\Asesi(['nama_lengkap' => 'Template Master']));
            $jadwal = new \App\Models\Jadwal(['tanggal_pelaksanaan' => now()]);
            $jadwal->setRelation('skema', new Skema(['nama_skema' => 'Template Skema', 'kode_unit' => 'SKM001']));
            $jadwal->setRelation('asesor', new \App\Models\Asesor(['nama_lengkap' => 'Nama Asesor']));
            $jadwal->setRelation('jenisTuk', new \App\Models\JenisTUK(['jenis_tuk' => 'Tempat Kerja']));
            $dataSertifikasi->setRelation('jadwal', $jadwal);
            $dataSertifikasi->setRelation('penyusunValidator', null);
        } else {
            // 1. Ambil data utama DataSertifikasiAsesi
            $dataSertifikasi = DataSertifikasiAsesi::with([
                'jadwal.skema.kelompokPekerjaan.unitKompetensi',
                'jadwal.asesor',
                'jadwal.jenisTuk',
                'asesi',
                'penyusunValidator.penyusun',
                'penyusunValidator.validator',
            ])->findOrFail($id_data_sertifikasi_asesi);
        }

        $penyusunValidator = $dataSertifikasi->penyusunValidator;

        // 2. Pemetaan Unit Kompetensi
        $unitKompetensi = [];
        if ($dataSertifikasi->jadwal && $dataSertifikasi->jadwal->skema) {
            $kelompokPekerjaanList = $dataSertifikasi->jadwal->skema->kelompokPekerjaan;
            if ($kelompokPekerjaanList) {
                foreach ($kelompokPekerjaanList as $kelompok) {
                    foreach ($kelompok->unitKompetensi as $unit) {
                        $unitKompetensi[] = [
                            'kelompok' => $kelompok->nama_kelompok_pekerjaan ?? '-',
                            'kode' => $unit->kode_unit ?? '-',
                            'judul' => $unit->judul_unit ?? '-',
                        ];
                    }
                }
            }
        }
        if (empty($unitKompetensi)) {
            $unitKompetensi = [['kelompok' => '-', 'kode' => '-', 'judul' => 'Data unit kompetensi belum tersedia']];
        }

        // 3. ✅ Ambil Bukti Portofolio SAMA PERSIS dengan IA08
        $buktiPortofolio = DataPortofolio::where(
            'id_data_sertifikasi_asesi',
            $id_data_sertifikasi_asesi
        )
            ->with('buktiPortofolioIA08IA09')
            ->get();

        // Format bukti portofolio untuk ditampilkan di blade
        $buktiPortofolioList = [];

        if ($buktiPortofolio->isNotEmpty()) {
            foreach ($buktiPortofolio as $bukti) {
                // ✅ Parse SAMA seperti di IA08 blade (baris 268-278)
                foreach (['persyaratan_dasar', 'persyaratan_administratif'] as $kolom) {
                    if (!empty($bukti->$kolom)) {
                        // Split by newline, semicolon, atau comma
                        $parts = preg_split('/[\n;,]+/', $bukti->$kolom);
                        
                        foreach ($parts as $p) {
                            $trimmed = trim($p);
                            if ($trimmed !== '') {
                                $buktiPortofolioList[] = $trimmed;
                            }
                        }
                    }
                }
            }
        }

        // Jika tidak ada bukti portofolio
        if (empty($buktiPortofolioList)) {
            $buktiPortofolioList = ['Data bukti portofolio belum tersedia'];
        }

        // 4. ✅ Ambil Pertanyaan Wawancara HANYA dari Database
        $pertanyaan = [];

        if ($buktiPortofolio->isNotEmpty()) {
            // Ambil semua id_portofolio
            $idPortofolioList = $buktiPortofolio->pluck('id_portofolio');

            // ✅ Ambil SEMUA data dari tabel bukti_portofolio_ia08_ia09
            $buktiData = BuktiPortofolioIA08IA09::whereIn('id_portofolio', $idPortofolioList)
                ->whereNotNull('daftar_pertanyaan_wawancara')
                ->orderBy('id_bukti_portofolio', 'asc')
                ->get();

            // ✅ Loop semua data yang ada di database
            foreach ($buktiData as $index => $bukti) {
                $pertanyaan[] = [
                    'no' => $index + 1,
                    'pertanyaan' => $bukti->daftar_pertanyaan_wawancara ?? '',
                    'jawaban' => $bukti->kesimpulan_jawaban_asesi ?? '',
                    'pencapaian' => $bukti->pencapaian_ia09 ?? '',
                    'id_jawaban' => $bukti->id_bukti_portofolio,
                ];
            }
        }

        // 5. Mapping data final
        $dataIA09 = [
            'id_data_sertifikasi_asesi' => $id_data_sertifikasi_asesi,
            'skema' => [
                'judul' => $dataSertifikasi->jadwal?->skema?->nama_skema ?? '-',
                'nomor' => $dataSertifikasi->jadwal?->skema?->nomor_skema ?? '-',
            ],
            'info_umum' => [
                'tuk_type' => $dataSertifikasi->jadwal?->jenisTuk?->jenis_tuk ?? '-',
                'nama_asesor' => $dataSertifikasi->jadwal?->asesor?->nama_lengkap ?? '-',
                'no_reg_met' => $dataSertifikasi->jadwal?->asesor?->nomor_regis ?? '-',
                'nama_asesi' => $dataSertifikasi->asesi?->nama_lengkap ?? '-',
                'tanggal' => $dataSertifikasi->jadwal?->tanggal_pelaksanaan ?? date('Y-m-d'),
            ],
            'ttd' => [
                'asesi' => $dataSertifikasi->asesi?->tanda_tangan ?? null,
                'asesor' => $dataSertifikasi->jadwal?->asesor?->tanda_tangan ?? null,
            ],
            'penyusun' => [
                'nama' => $penyusunValidator?->penyusun?->nama ?? 'Data Penyusun tidak ditemukan',
                'no_reg_met' => $penyusunValidator?->penyusun?->no_reg_met ?? '-',
                'ttd' => $penyusunValidator?->penyusun?->tanda_tangan ?? null,
                'tanggal' => $penyusunValidator?->penyusun?->tanggal ?? null,
            ],
            'validator' => [
                'nama' => $penyusunValidator?->validator?->nama ?? 'Data Validator tidak ditemukan',
                'no_reg_met' => $penyusunValidator?->validator?->no_reg_met ?? '-',
                'ttd' => $penyusunValidator?->validator?->tanda_tangan ?? null,
                'tanggal' => $penyusunValidator?->validator?->tanggal ?? null,
                'tanggal_validasi' => $penyusunValidator?->tanggal_validasi?->format('d F Y') ?? '-',
            ],
            'panduan_asesor' => [
                'Pertanyaan wawancara dapat dilakukan untuk keseluruhan unit kompetensi dalam skema sertifikasi atau dilakukan untuk masing-masing kelompok pekerjaan dalam satu skema sertifikasi.',
                'Jika hasil verifikasi potofolio telah memenuhi aturan bukti maka pertanyaan wawancara tidak perlu dilakukan terhadap bukti tersebut.',
                'Tuliskan pencapaian atas setiap kesimpulan pertanyaan wawancara dengan cara mencentang ( ✓ ) "Ya" atau "Tidak".',
            ],
            'unit_kompetensi' => $unitKompetensi,
            'bukti_portofolio' => $buktiPortofolioList,
            'pertanyaan' => $pertanyaan,
        ];

        return $dataIA09;
    }

    // =======================================================
    // METODE UNTUK ASESOR & ADMIN (Display)
    // =======================================================

    /**
     * Menampilkan form wawancara (IA.09) untuk Asesor (Edit) atau Admin (View).
     */
    public function showWawancara(Request $request, $id_data_sertifikasi_asesi)
    {
        $dataIA09 = $this->prepareIA09Data($id_data_sertifikasi_asesi);

        // Fetch objects for Sidebar
        $sertifikasi = DataSertifikasiAsesi::with(['asesi.user', 'jadwal.skema'])->find($id_data_sertifikasi_asesi);
        $asesi = $sertifikasi->asesi;
        $skema = $sertifikasi->jadwal->skema;
        $jadwal = $sertifikasi->jadwal;

        // Mode 'edit' jika role adalah asesor, 'view' jika role adalah admin/lainnya
        $mode = auth()->user()?->role?->nama_role === 'asesor' ? 'edit' : 'view';

        return view('frontend.IA09', compact('dataIA09', 'mode', 'asesi', 'skema', 'jadwal'));
    }

    // =======================================================
    // METODE UNTUK ASESOR (Store/Update)
    // =======================================================

    /**
     * Menyimpan atau memperbarui data wawancara yang diisi Asesor.
     */
    public function storeWawancara(Request $request, $id_data_sertifikasi_asesi)
    {
        // 1. Validasi Input
        $request->validate([
            'pertanyaan' => 'required|array',
            'pertanyaan.*.no' => 'required|integer',
            'pertanyaan.*.pertanyaan' => 'nullable|string',
            'pertanyaan.*.jawaban' => 'required|string|min:10',
            'pertanyaan.*.pencapaian' => 'required|in:Ya,Tidak',
            'pertanyaan.*.id_jawaban' => 'nullable|integer',
        ]);

        try {
            DB::beginTransaction();

            // 2. Ambil id_ia08 dari tabel ia08 (nullable)
            $id_ia08_value = DB::table('ia08')
                ->where('id_data_sertifikasi_asesi', $id_data_sertifikasi_asesi)
                ->value('id_ia08');

            // 3. Loop dan simpan/update Jawaban Pertanyaan
            foreach ($request->pertanyaan as $item) {
                $pencapaian_value = $item['pencapaian']; // 'Ya' atau 'Tidak'

                if (isset($item['id_jawaban']) && $item['id_jawaban']) {
                    // Update data yang sudah ada
                    BuktiPortofolioIA08IA09::where('id_bukti_portofolio', $item['id_jawaban'])
                        ->update([
                            'kesimpulan_jawaban_asesi' => $item['jawaban'],
                            'pencapaian_ia09' => $pencapaian_value,
                            'updated_at' => now(),
                        ]);
                } else {
                    // ❌ Jika id_jawaban tidak ada, skip (karena harus sudah ada dari IA08)
                    Log::warning("IA09 Store: id_jawaban tidak ditemukan untuk pertanyaan: " . ($item['pertanyaan'] ?? 'N/A'));
                    continue;
                }
            }

            DB::commit();

            return redirect()
                ->route('asesor.tracker', ['id_sertifikasi_asesi' => $id_data_sertifikasi_asesi])
                ->with('success', 'Data hasil wawancara (FR-IA.09) berhasil disimpan!');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("IA09 Store Exception: " . $e->getMessage());

            return redirect()
                ->back()
                ->with('error', 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * CETAK PDF FR.IA.09
     */
    public function cetakPDF($id_sertifikasi_asesi)
    {
        $dataRaw = $this->prepareIA09Data($id_sertifikasi_asesi);
        $pdf = Pdf::loadView('pdf.ia_09', ['data' => $dataRaw]);
        $pdf->setPaper('A4', 'portrait');

        $namaAsesi = preg_replace('/[^A-Za-z0-9\-]/', '_', $dataRaw['info_umum']['nama_asesi']);
        return $pdf->stream('FR_IA_09_' . $namaAsesi . '.pdf');
    }

    /**
     * [MASTER] Menampilkan editor template (Pertanyaan Wawancara) per Skema & Jadwal
     */
    public function editTemplate($id_skema, $id_jadwal)
    {
        $skema = Skema::findOrFail($id_skema);
        $template = MasterFormTemplate::where('id_skema', $id_skema)
                                    ->where('id_jadwal', $id_jadwal)
                                    ->where('form_code', 'FR.IA.09')
                                    ->first();
        
        $questions = $template ? $template->content : [];

        return view('Admin.master.skema.template.ia09', [
            'skema' => $skema,
            'id_jadwal' => $id_jadwal,
            'questions' => $questions
        ]);
    }

    /**
     * [MASTER] Simpan/Update template per Skema & Jadwal
     */
    public function storeTemplate(Request $request, $id_skema, $id_jadwal)
    {
        $request->validate([
            'questions' => 'required|array',
            'questions.*' => 'required|string',
        ]);

        MasterFormTemplate::updateOrCreate(
            [
                'id_skema' => $id_skema, 
                'id_jadwal' => $id_jadwal,
                'form_code' => 'FR.IA.09'
            ],
            ['content' => $request->questions]
        );

        return redirect()->back()->with('success', 'Template IA-09 berhasil diperbarui.');
    }

    /**
     * Menampilkan Template Master View untuk IA.09 (Admin)
     */
    public function adminShow($id_skema)
    {
        $skema = \App\Models\Skema::with(['kelompokPekerjaan.unitKompetensi'])->findOrFail($id_skema);
        
        $sertifikasi = new \App\Models\DataSertifikasiAsesi();
        $sertifikasi->id_data_sertifikasi_asesi = 0;
        
        $asesi = new \App\Models\Asesi(['nama_lengkap' => 'Template Master']);
        $sertifikasi->setRelation('asesi', $asesi);
        
        $jadwal = new \App\Models\Jadwal(['tanggal_pelaksanaan' => now()]);
        $jadwal->setRelation('skema', $skema);
        $jadwal->setRelation('asesor', new \App\Models\Asesor(['nama_lengkap' => 'Nama Asesor']));
        $jadwal->setRelation('jenisTuk', new \App\Models\JenisTUK(['jenis_tuk' => 'Tempat Kerja']));
        $sertifikasi->setRelation('jadwal', $jadwal);

        $dataIA09 = $this->prepareIA09Data(0);

        return view('frontend.IA09', [
            'dataIA09' => $dataIA09,
            'mode' => 'view',
            'asesi' => $asesi,
            'skema' => $skema,
            'jadwal' => $jadwal,
            'isMasterView' => true,
        ]);
    }
}
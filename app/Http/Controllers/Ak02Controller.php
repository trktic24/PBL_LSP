<?php

namespace App\Http\Controllers;

use App\Models\Ak02;
use App\Models\DataSertifikasiAsesi;
use App\Models\MasterFormTemplate;
use App\Models\Skema;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

use Illuminate\Support\Facades\Auth;

class Ak02Controller extends Controller
{
    public function edit($id_asesi)
    {
        // Ambil Data
        $asesi = DataSertifikasiAsesi::with([
            'jadwal.skema.kelompokPekerjaan.unitKompetensi',
            'jadwal.asesor', // <--- Added
            'asesi.user',
            'lembarJawabIa05' => function ($q) {
                $q->whereNotNull('pencapaian_ia05');
            },
            'ia06Answers' => function ($q) {
                $q->whereNotNull('pencapaian');
            },
            'ia07' => function ($q) {
                $q->whereNotNull('pencapaian');
            },
            'ia10',
            'ia02'
        ])->findOrFail($id_asesi);

        // --- PERBAIKAN LOGIKA VALIDASI DI SINI ---

        // Relasi HasMany (Mengembalikan Collection -> Aman pakai count())
        $ia05Done = $asesi->lembarJawabIa05->count() > 0;
        $ia06Done = $asesi->ia06Answers->count() > 0;
        $ia07Done = $asesi->ia07->count() > 0;

        // Relasi HasOne (Mengembalikan Object atau Null -> Cek tidak null)
        $ia10Done = !is_null($asesi->ia10);
        $ia02Done = !is_null($asesi->ia02);

        $isFinalized = ($asesi->level_status >= 100);

        // Check if user is Admin/Superadmin
        $user = Auth::user();
        $isAdmin = $user && in_array($user->role_id, [1, 4]);

        // Jika data belum lengkap, lempar kembali (kecuali sudah final atau user adalah Admin)
        if (!$isAdmin && !$isFinalized && !($ia05Done && $ia06Done && $ia07Done && $ia10Done && $ia02Done)) {
            return redirect()->back()->with('error', 'Penilaian Asesmen (IA) belum lengkap. Mohon selesaikan penilaian IA terlebih dahulu.');
        }

        // Ambil Data Penilaian yang sudah ada (jika ada)
        // Kita key-by ID Unit Kompetensi biar gampang akses di Blade
        $penilaianList = $asesi->ak02()->get()->keyBy('id_unit_kompetensi');

        // Extract Skema and Jadwal for Sidebar
        $skema = $asesi->jadwal->skema;
        $jadwal = $asesi->jadwal;

        // [AUTO-LOAD TEMPLATE]
        $template = null;
        if ($penilaianList->isEmpty()) {
            $template = MasterFormTemplate::where('id_skema', $skema->id_skema)
                ->where('id_jadwal', $asesi->id_jadwal)
                ->where('form_code', 'FR.AK.02')
                ->first();

            if (!$template) {
                $template = MasterFormTemplate::where('id_skema', $skema->id_skema)
                    ->whereNull('id_jadwal')
                    ->where('form_code', 'FR.AK.02')
                    ->first();
            }
        }

        return view('frontend.AK_02.FR_AK_02', [
            'asesi' => $asesi,
            'penilaianList' => $penilaianList,
            'skema' => $skema,
            'jadwal' => $jadwal,
            'template' => $template ? $template->content : null
        ]);
    }

    public function update(Request $request, $id_asesi)
    {
        $request->validate([
            'penilaian' => 'required|array',
            'global_kompeten' => 'nullable|in:Kompeten,Belum Kompeten',
        ]);

        // Ambil input global
        $globalKompeten = $request->input('global_kompeten');
        $globalTindakLanjut = $request->input('global_tindak_lanjut');
        $globalKomentar = $request->input('global_komentar');

        DB::beginTransaction();
        try {
            foreach ($request->penilaian as $idUnit => $data) {
                // Ambil checkbox (array)
                $bukti = isset($data['jenis_bukti']) ? $data['jenis_bukti'] : [];

                Ak02::updateOrCreate(
                    [
                        'id_unit_kompetensi' => $idUnit,
                        'id_data_sertifikasi_asesi' => $id_asesi,
                    ],
                    [
                        'jenis_bukti' => $bukti,
                        // Simpan input global ke setiap baris unit
                        'kompeten' => $globalKompeten,
                        'tindak_lanjut' => $globalTindakLanjut,
                        'komentar' => $globalKomentar,
                    ]
                );
            }

            $asesi = DataSertifikasiAsesi::findOrFail($id_asesi);

            if ($globalKompeten) {
                $asesi->update([
                    'rekomendasi_hasil_asesmen_AK02' => $globalKompeten,
                    'status_validasi' => 'pending', // ENUM: ['pending', 'valid']
                ]);
            }

            DB::commit();

            // Dispatch Event
            try {
                $asesiData = DataSertifikasiAsesi::find($id_asesi);
                $userName = $request->user()->name ?? 'Asesor'; // Fallback name

                $notificationData = [
                    'title' => "Penilaian Siap Divalidasi",
                    'message' => "Asesor {$userName} telah menyelesaikan penilaian FR.AK.02.",
                    'action_url' => route('validator.tracker.show', ['id' => $id_asesi]),
                    'actor' => "Asesor",
                    'entity_id' => $id_asesi,
                ];

                event(new \App\Events\AssessmentReviewed($notificationData));

                // --- NEW: Notify Admins (Validators) ---
                $validators = \App\Models\User::whereHas('role', function ($q) {
                    $q->where('nama_role', 'admin');
                })->get();

                // Safely access relationships
                $namaAsesi = $asesiData->asesi->nama_lengkap ?? 'Asesi';
                $namaAsesor = $asesiData->jadwal->asesor->nama_asesor ?? ($request->user()->name ?? 'Asesor');
                $namaSkema = $asesiData->jadwal->skema->judul_skema ?? 'Skema';
                $idJadwal = $asesiData->id_jadwal;

                foreach ($validators as $validator) {
                    $validator->notify(new \App\Notifications\AssessmentCompletionNotice([
                        'message' => "Asesmen dari {$namaAsesi} telah selesai dinilai oleh asesor {$namaAsesor}, dari skema {$namaSkema}.",
                        'link' => route('admin.schedule.attendance', ['id_jadwal' => $idJadwal]),
                    ]));
                }
            } catch (\Exception $evt) {
                // Ignore event failure to not block saving
                // Log::error($evt);
            }

            return redirect()->route('asesor.tracker', $id_asesi)->with('success', 'Rekaman Asesmen FR.AK.02 berhasil disimpan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function cetakPDF($id_asesi)
    {
        // 1. Ambil Data Asesi Lengkap
        $asesi = DataSertifikasiAsesi::with([
            'asesi',
            'jadwal.masterTuk',
            'jadwal.skema.kelompokPekerjaan.unitKompetensi',
            'jadwal.skema.asesor',
            'jadwal.asesor',
        ])->findOrFail($id_asesi);

        // 2. Ambil Penilaian AK.02
        $ak02Data = Ak02::where('id_data_sertifikasi_asesi', $id_asesi)
            ->get()
            ->keyBy('id_unit_kompetensi');

        // 3. Mapping Data untuk View PDF
        // Header
        // Menggunakan optional chaining atau null coalescing untuk menghindari error jika relasi kosong
        $data['skema'] = $asesi->jadwal->skema->judul_skema ?? '-';
        $data['nomor_skema'] = $asesi->jadwal->skema->nomor_skema ?? '-';
        $data['tuk'] = $asesi->jadwal->tuk->nama_tuk ?? 'Tempat Kerja';
        $data['nama_asesor'] = $asesi->jadwal->asesor->nama_asesor ?? '-';
        $data['nama_asesi'] = $asesi->asesi->nama_lengkap ?? '-';
        $data['ttd_asesi'] = $asesi->asesi->tanda_tangan ?? null;
        $data['ttd_asesor'] = $asesi->jadwal->asesor->tanda_tangan ?? null;
        $data['tanggal'] = date('d-m-Y');

        // Unit Kompetensi
        $data['unit_kompetensi'] = [];
        $allUnits = collect();

        if ($asesi->jadwal->skema->kelompokPekerjaan) {
            foreach ($asesi->jadwal->skema->kelompokPekerjaan as $kelompok) {
                if ($kelompok->unitKompetensi) {
                    $allUnits = $allUnits->merge($kelompok->unitKompetensi);
                    if (!isset($data['kelompok_pekerjaan'])) {
                        $data['kelompok_pekerjaan'] = $kelompok->nama_kelompok_pekerjaan;
                    }
                }
            }
        }

        foreach ($allUnits as $unit) {
            $data['unit_kompetensi'][] = [
                'kode' => $unit->kode_unit_kompetensi,
                'judul' => $unit->judul_unit_kompetensi,
            ];
        }

        // Bukti-Bukti
        $data['bukti_kompetensi'] = [];
        $firstRec = null;

        foreach ($allUnits as $unit) {
            $record = $ak02Data->get($unit->id_unit_kompetensi);
            if (!$firstRec && $record)
                $firstRec = $record;

            $jenisBukti = $record ? ($record->jenis_bukti ?? []) : [];

            $data['bukti_kompetensi'][] = [
                'unit' => $unit->judul_unit_kompetensi,
                'observasi' => in_array('observasi', $jenisBukti),
                'portofolio' => in_array('portofolio', $jenisBukti),
                'pihak_ketiga' => in_array('pihak_ketiga', $jenisBukti),
                'lisan' => in_array('lisan', $jenisBukti),
                'tertulis' => in_array('tertulis', $jenisBukti),
                'proyek' => in_array('proyek', $jenisBukti),
                'lainnya' => in_array('lainnya', $jenisBukti),
            ];
        }

        // Rekomendasi (Global)
        if ($firstRec) {
            $data['hasil_asesmen'] = ($firstRec->kompeten == 'Kompeten') ? 'kompeten' : 'belum_kompeten';
            $data['tindak_lanjut'] = $firstRec->tindak_lanjut;
            $data['komentar_asesor'] = $firstRec->komentar;
        } else {
            $data['hasil_asesmen'] = null;
            $data['tindak_lanjut'] = '-';
            $data['komentar_asesor'] = '-';
        }

        // 4. Render PDF
        $pdf = Pdf::loadView('pdf.ak_02', ['data' => $data]);
        $pdf->setPaper('A4', 'portrait');

        return $pdf->stream('FR_AK_02_' . str_replace(' ', '_', $data['nama_asesi']) . '.pdf');
    }

    /**
     * [MASTER] Menampilkan editor template (Rekaman Asesmen) per Skema & Jadwal
     */
    public function editTemplate($id_skema, $id_jadwal)
    {
        $skema = Skema::findOrFail($id_skema);
        $template = MasterFormTemplate::where('id_skema', $id_skema)
            ->where('id_jadwal', $id_jadwal)
            ->where('form_code', 'FR.AK.02')
            ->first();

        $content = $template ? $template->content : [
            'tindak_lanjut' => '',
            'komentar' => ''
        ];

        return view('Admin.master.skema.template.ak02', [
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
            'content.tindak_lanjut' => 'nullable|string',
            'content.komentar' => 'nullable|string',
        ]);

        MasterFormTemplate::updateOrCreate(
            [
                'id_skema' => $id_skema,
                'id_jadwal' => $id_jadwal,
                'form_code' => 'FR.AK.02'
            ],
            ['content' => $request->input('content')]
        );

        return redirect()->back()->with('success', 'Templat AK-02 berhasil diperbarui.');
    }

    /**
     * Menampilkan Template Form FR.AK.02 (Admin Master View) - DEPRECATED for management
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

        $sertifikasi->setRelation('lembarJawabIa05', collect());
        $sertifikasi->setRelation('ia06Answers', collect());
        $sertifikasi->setRelation('ia07', collect());
        $sertifikasi->setRelation('ia10', null);
        $sertifikasi->setRelation('ia02', null);

        return view('frontend.AK_02.FR_AK_02', [
            'asesi' => $sertifikasi,
            'penilaianList' => collect(),
            'skema' => $skema,
            'jadwal' => $jadwal,
            'template' => null,
            'isMasterView' => true,
        ]);
    }
}

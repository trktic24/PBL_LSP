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
        // 1. Ambil data utama DataSertifikasiAsesi
        $dataSertifikasi = DataSertifikasiAsesi::with([
            'jadwal.skema.kelompokPekerjaan.unitKompetensi',
            'jadwal.asesor',
            'jadwal.jenisTuk',
            'asesi',
            'penyusunValidator.penyusun',
            'penyusunValidator.validator',
            'portofolio.buktiPortofolioIA08IA09',
        ])->findOrFail($id_data_sertifikasi_asesi);

        $portofolio = $dataSertifikasi->portofolio->first();
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

        // 3. Pemetaan Bukti Portofolio (Mengambil dari kolom JSON 'persyaratan_dasar' dan 'persyaratan_administratif')
        $buktiPortofolio = ['Data bukti portofolio belum tersedia'];
        if ($portofolio) {
            $buktiList = [];
            $keys = ['persyaratan_dasar', 'persyaratan_administratif'];
            foreach ($keys as $key) {
                if (!empty($portofolio->{$key})) {
                    $data = is_string($portofolio->{$key}) ? json_decode($portofolio->{$key}, true) : $portofolio->{$key};
                    if (is_array($data)) {
                        $buktiList = array_merge($buktiList, $data);
                    } elseif ($data) {
                        $buktiList[] = $data;
                    }
                }
            }
            $buktiPortofolio = array_unique(array_filter($buktiList));
        }

        // 4. *** PERBAIKAN: Ambil Pertanyaan dari Tabel bukti_portofolio_ia08_ia09 ***
        $pertanyaan = [];

        if ($portofolio) {
            // Ambil semua data bukti yang terkait dengan portofolio ini
            $buktiData = BuktiPortofolioIA08IA09::where('id_portofolio', $portofolio->id_portofolio)
                ->orderBy('id_bukti_portofolio', 'asc')
                ->get();

            if ($buktiData->isEmpty()) {
                // Jika belum ada data, buat pertanyaan kosong dari bukti portofolio
                foreach ($buktiPortofolio as $index => $bukti) {
                    $pertanyaan[] = [
                        'no' => $index + 1,
                        'pertanyaan' => "Sesuai dengan bukti no. " . ($index + 1) . ", jelaskan secara detail: " . $bukti,
                        'jawaban' => '',
                        'pencapaian' => '',
                        'id_jawaban' => null,
                    ];
                }

                // [AUTO-LOAD TEMPLATE] Tambahkan pertanyaan dari Master Template jika ada
                $template = MasterFormTemplate::where('id_skema', $dataSertifikasi->jadwal?->id_skema)
                                            ->where('form_code', 'FR.IA.09')
                                            ->first();
                if ($template && !empty($template->content)) {
                    $startIndex = count($pertanyaan);
                    foreach ($template->content as $idx => $qText) {
                        $pertanyaan[] = [
                            'no' => $startIndex + $idx + 1,
                            'pertanyaan' => $qText,
                            'jawaban' => '',
                            'pencapaian' => '',
                            'id_jawaban' => null,
                        ];
                    }
                }
            } else {
                // Jika sudah ada data, ambil dari database
                foreach ($buktiData as $index => $bukti) {
                    $pertanyaan[] = [
                        'no' => $index + 1,
                        'pertanyaan' => $bukti->daftar_pertanyaan_wawancara ?? "Pertanyaan untuk bukti no. " . ($index + 1),
                        'jawaban' => $bukti->kesimpulan_jawaban_asesi ?? '',
                        'pencapaian' => $bukti->pencapaian_ia09 ?? '',
                        'id_jawaban' => $bukti->id_bukti_portofolio,
                    ];
                }
            }
        }

        // Jika tidak ada pertanyaan sama sekali, buat minimal 1 pertanyaan default
        if (empty($pertanyaan)) {
            $pertanyaan[] = [
                'no' => 1,
                'pertanyaan' => 'Jelaskan pengalaman Anda terkait kompetensi yang diujikan.',
                'jawaban' => '',
                'pencapaian' => '',
                'id_jawaban' => null,
            ];
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
                'Tuliskan pencapaian atas setiap kesimpulan pertanyaan wawancara dengan cara mencentang ( ) "Ya" atau "Tidak".',
            ],
            'unit_kompetensi' => $unitKompetensi,
            'bukti_portofolio' => $buktiPortofolio,
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

            // 2. Cari atau Buat Data Portofolio
            $portofolio = DataPortofolio::firstOrCreate(
                ['id_data_sertifikasi_asesi' => $id_data_sertifikasi_asesi],
                [
                    'persyaratan_dasar' => json_encode([]),
                    'persyaratan_administratif' => json_encode([]),
                ]
            );

            if (!$portofolio) {
                DB::rollBack();
                return redirect()->back()
                    ->with('error', 'Gagal membuat data portofolio untuk asesi ini.')
                    ->withInput();
            }

            $idPortofolio = $portofolio->id_portofolio;

            // 3. Ambil id_ia08 dari tabel ia08 (nullable)
            $id_ia08_value = DB::table('ia08')
                ->where('id_data_sertifikasi_asesi', $id_data_sertifikasi_asesi)
                ->value('id_ia08');

            // 5. Loop dan simpan/update Jawaban Pertanyaan
            foreach ($request->pertanyaan as $item) {
                $pencapaian_value = $item['pencapaian']; // 'Ya' atau 'Tidak'

                $updateData = [
                    'id_portofolio' => $idPortofolio,
                    'daftar_pertanyaan_wawancara' => $item['pertanyaan'] ?? null,
                    'kesimpulan_jawaban_asesi' => $item['jawaban'],
                    'pencapaian_ia09' => $pencapaian_value, // ✅ String: 'Ya' atau 'Tidak'
                    'id_ia08' => $id_ia08_value, // ✅ Nullable

                    'updated_at' => now(),
                ];

                if (isset($item['id_jawaban']) && $item['id_jawaban']) {
                    // Update data yang sudah ada
                    BuktiPortofolioIA08IA09::where('id_bukti_portofolio', $item['id_jawaban'])
                        ->update($updateData);
                } else {
                    // Buat data baru
                    BuktiPortofolioIA08IA09::create(array_merge($updateData, [
                        'created_at' => now(),
                    ]));
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

    // ... method lainnya ...

    /**
     * CETAK PDF FR.IA.09
     */
    public function cetakPDF($id_sertifikasi_asesi)
    {
        // 1. Siapkan Data (Reuse method prepareIA09Data yg sudah ada biar konsisten)
        // Method ini sudah meng-handle logic pengambilan data sertifikasi, unit, dan pertanyaan.
        $dataRaw = $this->prepareIA09Data($id_sertifikasi_asesi);

        // 2. Render PDF
        // Kita kirim array $dataRaw langsung ke view
        $pdf = Pdf::loadView('pdf.ia_09', ['data' => $dataRaw]);

        $pdf->setPaper('A4', 'portrait');

        $namaAsesi = preg_replace('/[^A-Za-z0-9\-]/', '_', $dataRaw['info_umum']['nama_asesi']);
        return $pdf->stream('FR_IA_09_' . $namaAsesi . '.pdf');
    }

    /**
     * [MASTER] Menampilkan editor tamplate (Pertanyaan Wawancara) per Skema
     */
    public function editTemplate($id_skema)
    {
        $skema = Skema::findOrFail($id_skema);
        $template = MasterFormTemplate::where('id_skema', $id_skema)
                                    ->where('form_code', 'FR.IA.09')
                                    ->first();
        
        // Default values if no template exists
        $questions = $template ? $template->content : [];

        return view('Admin.master.skema.template.ia09', [
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
            ['id_skema' => $id_skema, 'form_code' => 'FR.IA.09'],
            ['content' => $request->questions]
        );

        return redirect()->back()->with('success', 'Templat IA-09 berhasil diperbarui.');
    }

    /**
     * Menampilkan Template Master View untuk IA.09 (Admin) - DEPRECATED for management
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
        ])->whereHas('jadwal', function ($q) use ($id_skema) {
            $q->where('id_skema', $id_skema);
        });

        if (request('search')) {
            $search = request('search');
            $query->whereHas('asesi', function ($q) use ($search) {
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
            'targetRoute' => 'ia09.index',
            'buttonLabel' => 'FR.IA.09',
            'formName' => 'Pertanyaan Wawancara',
        ]);
    }
}
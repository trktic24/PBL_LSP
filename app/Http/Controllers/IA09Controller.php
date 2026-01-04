<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\DataSertifikasiAsesi;
use App\Models\DataPortofolio;
use App\Models\BuktiPortofolioIA08IA09; 
use Illuminate\Support\Facades\Log;

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
                'rekomendasi' => $portofolio?->rekomendasi_asesor ?? '', 
                'catatan' => $portofolio?->catatan_asesor ?? '', 
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
        
        // Mode 'edit' jika role adalah asesor, 'view' jika role adalah admin/lainnya
        $mode = auth()->user()?->role?->nama_role === 'asesor' ? 'edit' : 'view';

        return view('frontend.IA09', compact('dataIA09', 'mode'));
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
            'rekomendasi' => 'nullable|string',
            'catatan' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            // 2. Cari Data Portofolio
            $portofolio = DataPortofolio::firstOrCreate(
                ['id_data_sertifikasi_asesi' => $id_data_sertifikasi_asesi],
                [
                    'rekomendasi_asesor' => null,
                    'catatan_asesor' => null,
                    'persyaratan_dasar' => json_encode([]), // Default empty array
                    'persyaratan_administratif' => json_encode([]), // Default empty array
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );            
            
            if (!$portofolio) {
                DB::rollBack();
                return redirect()->back()->with('error', 'Gagal membuat data portofolio untuk asesi ini.')->withInput();
            }
            
            $idPortofolio = $portofolio->id_portofolio;
            
            // 3. Ambil id_ia08 dari tabel ia08
            $id_ia08_value = DB::table('ia08')
                ->where('id_data_sertifikasi_asesi', $id_data_sertifikasi_asesi) 
                ->value('id_ia08');

            // 4. Update Rekomendasi/Catatan di Model Portofolio
            $portofolio->update([
                'rekomendasi_asesor' => $request->rekomendasi,
                'catatan_asesor' => $request->catatan,
            ]);
            
            // 5. Loop dan simpan/update Jawaban Pertanyaan
            foreach ($request->pertanyaan as $item) {
                $pencapaian_value = $item['pencapaian']; 
                
                $updateData = [
                    'id_portofolio' => $idPortofolio,
                    'daftar_pertanyaan_wawancara' => $item['pertanyaan'] ?? null,
                    'kesimpulan_jawaban_asesi' => $item['jawaban'], 
                    'pencapaian_ia09' => $pencapaian_value,
                    'id_ia08' => $id_ia08_value,
                    // Nilai default untuk validasi portofolio
                    'is_valid' => true, 
                    'is_asli' => true,  
                    'is_terkini' => true, 
                    'is_memadai' => true,
                    'updated_at' => now(),
                ];

                if (isset($item['id_jawaban']) && $item['id_jawaban']) {
                    // Update data yang sudah ada
                    BuktiPortofolioIA08IA09::where('id_bukti_portofolio', $item['id_jawaban'])
                        ->update($updateData);
                } else {
                    // Buat data baru
                    $createData = array_merge($updateData, [
                        'created_at' => now(),
                    ]);
                    BuktiPortofolioIA08IA09::create($createData);
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
}

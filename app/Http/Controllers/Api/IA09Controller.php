<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\DataSertifikasiAsesi;
use App\Models\Portofolio;
use App\Models\BuktiPortofolioIA08IA09;
use Log;

class IA09Controller extends Controller
{
    // --- Data Statis Pertanyaan (DUMMY) ---
    protected $pertanyaanDummy = [
        [
            'no' => 1,
            'pertanyaan' => 'Sesuai dengan bukti no. 1, jelaskan langkah-langkah Anda dalam memvalidasi data yang dimasukkan ke spreadsheet.',
        ],
        [
            'no' => 2,
            'pertanyaan' => 'Sesuai dengan bukti no. 2, bagaimana Anda memastikan bahwa slide presentasi yang Anda buat memenuhi prinsip desain visual?',
        ],
        [
            'no' => 3,
            'pertanyaan' => 'Jelaskan pengalaman Anda dalam menggunakan tools spreadsheet untuk analisis data.',
        ],
        [
            'no' => 4,
            'pertanyaan' => 'Bagaimana cara Anda menangani data yang error atau tidak valid dalam spreadsheet?',
        ],
    ];

    /**
     * Pengekstrak data utama
     */
    protected function prepareIA09Data($id_data_sertifikasi_asesi)
    {
        $dataSertifikasi = DataSertifikasiAsesi::with([
            'jadwal.skema.kelompokPekerjaans.unitKompetensis',
            'jadwal.asesor',
            'jadwal.jenisTuk',
            'asesi',
            'penyusunValidator.penyusun', 
            'penyusunValidator.validator',
            'portofolio.buktiPortofolioIA08IA09', 
        ])->findOrFail($id_data_sertifikasi_asesi);

        $portofolio = $dataSertifikasi->portofolio->first();
        $penyusunValidator = $dataSertifikasi->penyusunValidator;

        // Pemetaan Unit Kompetensi
        $unitKompetensi = [];
        if ($dataSertifikasi->jadwal && $dataSertifikasi->jadwal->skema) {
            $kelompokPekerjaanList = $dataSertifikasi->jadwal->skema->kelompokPekerjaans;
            if ($kelompokPekerjaanList) {
                foreach ($kelompokPekerjaanList as $kelompok) {
                    foreach ($kelompok->unitKompetensis as $unit) {
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

        // Pemetaan Bukti Portofolio
        $buktiPortofolio = [];
        if ($portofolio) {
            $buktiList = [];
            $keys = ['persyaratan_dasar', 'persyaratan_administratif'];
            foreach ($keys as $key) {
                if (!empty($portofolio->{$key})) {
                    $data = is_string($portofolio->{$key}) ? json_decode($portofolio->{$key}, true) : $portofolio->{$key};
                    if (is_array($data)) {
                        $buktiList = array_merge($buktiList, $data);
                    } else {
                        $buktiList[] = $data;
                    }
                }
            }
            $buktiPortofolio = array_unique(array_filter($buktiList));
        }
        if (empty($buktiPortofolio)) {
            $buktiPortofolio = ['Data bukti portofolio belum tersedia'];
        }

        // Pemetaan Pertanyaan dan Jawaban
        $existingAnswers = collect();
        if ($portofolio && $portofolio->buktiPortofolioIA08IA09) {
            $existingAnswers = $portofolio->buktiPortofolioIA08IA09;
        }

        $pertanyaan = [];
        foreach ($this->pertanyaanDummy as $item) {
            $saved = $existingAnswers->firstWhere('id_bukti_portofolio', $item['no']); 
            
            $pertanyaan[] = [
                'no' => $item['no'],
                'pertanyaan' => $item['pertanyaan'],
                'jawaban' => $saved->kesimpulan_jawaban_asesi ?? '', 
                'pencapaian' => $saved->pencapaian_ia09 ?? '', 
                'id_jawaban' => $saved->id_bukti_portofolio ?? null,
            ];
        }

        // Mapping data final
        $dataIA09 = [
            'id_data_sertifikasi_asesi' => $id_data_sertifikasi_asesi,
            'skema' => [
                'judul' => $dataSertifikasi->jadwal->skema->nama_skema ?? '-',
                'nomor' => $dataSertifikasi->jadwal->skema->nomor_skema ?? '-',
            ],
            'info_umum' => [
                'tuk_type' => $dataSertifikasi->jadwal->jenisTuk->jenis_tuk ?? '-',
                'nama_asesor' => $dataSertifikasi->jadwal->asesor->nama_lengkap ?? '-',
                'no_reg_met' => $dataSertifikasi->jadwal->asesor->nomor_regis ?? '-',
                'nama_asesi' => $dataSertifikasi->asesi->nama_lengkap ?? '-',
                'tanggal' => $dataSertifikasi->jadwal->tanggal_pelaksanaan ?? date('Y-m-d'),
            ],
            'ttd' => [
                'asesi' => $dataSertifikasi->asesi->tanda_tangan ?? null,
                'asesor' => $dataSertifikasi->jadwal->asesor->tanda_tangan ?? null,
            ],
            'penyusun' => [
                'nama' => $penyusunValidator?->penyusun?->penyusun ?? 'Data Penyusun tidak ditemukan',
                'no_reg_met' => $penyusunValidator?->penyusun?->no_MET_penyusun ?? '-',
                'ttd' => $penyusunValidator?->penyusun?->tanda_tangan ?? 
                         $penyusunValidator?->penyusun?->ttd ?? null,
                'tanggal' => $penyusunValidator?->penyusun?->tanggal ?? null,
            ],
            'validator' => [
                'nama' => $penyusunValidator?->validator?->nama_validator ?? 'Data Validator tidak ditemukan',
                'no_reg_met' => $penyusunValidator?->validator?->no_MET_validator ?? '-',
                'ttd' => $penyusunValidator?->validator?->tanda_tangan ?? 
                         $penyusunValidator?->validator?->ttd ?? null,
                'tanggal' => $penyusunValidator?->validator?->tanggal ?? null,
                'tanggal_validasi' => $penyusunValidator?->tanggal_validasi?->format('d F Y') ?? '-',
            ],
            'panduan_asesor' => [
                'Pertanyaan wawancara dapat dilakukan untuk keseluruhan unit kompetensi dalam skema sertifikasi atau dilakukan untuk masing-masing kelompok pekerjaan dalam satu skema sertifikasi.',
                'Isilah bukti portofolio sesuai dengan bukti yang diminta pada skema sertifikasi sebagaimana yang telah dibuat pada FR-IA.08.',
                'Ajukan pertanyaan verifikasi portofolio untuk semua unit/elemen kompetensi yang di checklist pada FR-IA.08',
                'Ajukan pertanyaan kepada asesi sebagai tindak lanjut hasil verifikasi portofolio.',
                'Jika hasil verifikasi potofolio telah memenuhi aturan bukti maka pertanyaan wawancara tidak perlu dilakukan terhadap bukti tersebut.',
                'Tuliskan pencapaian atas setiap kesimpulan pertanyaan wawancara dengan cara mencentang ( ) "Ya" atau "Tidak".',
            ],
            'unit_kompetensi' => $unitKompetensi,
            'bukti_portofolio' => $buktiPortofolio,
            'pertanyaan' => $pertanyaan,
        ];

        return $dataIA09;
    }

    /**
     * API: Get IA09 Data
     */
    public function getIA09Data($id_data_sertifikasi_asesi)
    {
        try {
            $dataIA09 = $this->prepareIA09Data($id_data_sertifikasi_asesi);
            
            return response()->json([
                'success' => true,
                'message' => 'Data IA09 berhasil diambil',
                'data' => $dataIA09
            ], 200);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * API: Store IA09 Data
     */
    public function storeWawancara(Request $request)
    {
        $request->validate([
            'id_data_sertifikasi_asesi' => 'required|exists:data_sertifikasi_asesi,id_data_sertifikasi_asesi',
            'pertanyaan' => 'required|array',
            'pertanyaan.*.no' => 'required|integer',
            'pertanyaan.*.pertanyaan' => 'required|string',
            'pertanyaan.*.jawaban' => 'required|string|min:10',
            'pertanyaan.*.pencapaian' => 'required|in:Ya,Tidak',
        ]);

        try {
            $idDataSertifikasi = $request->id_data_sertifikasi_asesi;
            $portofolio = Portofolio::where('id_data_sertifikasi_asesi', $idDataSertifikasi)->first();
            
            if (!$portofolio) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data portofolio tidak ditemukan'
                ], 404);
            }
            
            $idPortofolio = $portofolio->id_portofolio;
            
            $id_ia08_value = DB::table('ia08')
                ->where('id_data_sertifikasi_asesi', $idDataSertifikasi) 
                ->value('id_ia08');
            
            if (!$id_ia08_value) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data FR-IA.08 belum ada'
                ], 404);
            }

            foreach ($request->pertanyaan as $item) {
                $pencapaian_value = ($item['pencapaian'] === 'Ya') ? 1 : 0; 

                $updateData = [
                    'id_portofolio' => $idPortofolio,
                    'kesimpulan_jawaban_asesi' => $item['jawaban'], 
                    'pencapaian_ia09' => $pencapaian_value,
                    'id_ia08' => $id_ia08_value, 
                    'is_valid' => true, 
                    'is_asli' => true,  
                    'is_terkini' => true, 
                    'is_memadai' => true, 
                ];

                if (isset($item['id_jawaban']) && $item['id_jawaban']) {
                    BuktiPortofolioIA08IA09::where('id_bukti_portofolio', $item['id_jawaban'])->update($updateData);
                } else {
                    BuktiPortofolioIA08IA09::create($updateData);
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Data wawancara berhasil disimpan'
            ], 200);
                
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
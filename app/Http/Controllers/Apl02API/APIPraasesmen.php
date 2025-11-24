<?php

namespace App\Http\Controllers\Apl02API;

use App\Http\Controllers\Controller;
use App\Models\DataSertifikasiAsesi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\JsonResponse;
use App\Models\ResponApl2Ia01;

class ApiPraasesmenController extends Controller
{
    /**
     * Display a listing of the resource.
     * Metode ini tidak digunakan dalam konteks ini, tetapi harus ada untuk Resource Controller.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(): JsonResponse
    {
        // Secara umum, ini akan mengembalikan daftar semua data sertifikasi.
        // Kita kembalikan error 403 karena rute yang digunakan adalah /asesi/{idAsesi}/praasesmen (show).
        return response()->json([
            'status' => 'error',
            'message' => 'Akses terlarang. Gunakan endpoint show(/asesi/{idAsesi}/praasesmen) untuk melihat data spesifik.'
        ], 403);
    }

    /**
     * Display the specified resource (Data Pra-Asesmen Berdasarkan ID Asesi).
     * Metode ini menggantikan fungsi index($idAsesi) sebelumnya.
     *
     * @param string $id ID Asesi ($idAsesi)
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(string $id): JsonResponse
    {
        $idAsesi = (int)$id;

        // 1. Ambil Data Sertifikasi Asesi dengan nested relasi
        $dataSertifikasi = DataSertifikasiAsesi::where('id_asesi', $idAsesi)
            ->latest()  
            ->with([
                'jadwal.skema.kelompokPekerjaan.unitKompetensi.elemen.kriteriaUnjukKerja',
                'asesi.user',
                // Ambil data respon APL-02 yang sudah pernah diisi
                'responApl02Ia01'
            ])
            ->first();

        if (!$dataSertifikasi) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data sertifikasi untuk asesi ini tidak ditemukan.'
            ], 404);
        }

        $skema = $dataSertifikasi->jadwal->skema;
        $unitKompetensiList = collect();
        $existingResponses = $dataSertifikasi->responApl02Ia01->keyBy('id_kriteria');

        // Mengumpulkan semua unit kompetensi dari semua kelompok pekerjaan
        foreach ($skema->kelompokPekerjaan as $kelompok) {
            $unitKompetensiList = $unitKompetensiList->merge($kelompok->unitKompetensi);
        }

        // 2. Memformat Data untuk Kebutuhan API
        $skemaData = [];
        foreach ($unitKompetensiList as $unit) {
            $elementsData = [];

            foreach ($unit->elemen as $element) {
                $kuksData = [];
                foreach ($element->kriteriaUnjukKerja as $kuk) {
                    $kukId = $kuk->id_kriteria;
                    $response = $existingResponses->get($kukId);

                    $kuksData[] = [
                        'id_kuk' => $kukId,
                        'text' => $kuk->kriteria_unjuk_kerja,
                        // Tambahkan respon yang sudah ada (jika ada)
                        'respon_asesi_apl02' => $response ? ($response->respon_asesi_apl02 ? 'K' : 'BK') : null,
                        'bukti_asesi_apl02' => $response ? $response->bukti_asesi_apl02 : null,
                    ];
                }

                $elementsData[] = [
                    'id_elemen' => $element->id_elemen,
                    'name' => $element->elemen,
                    'kuks' => $kuksData,
                ];
            }

            $skemaData[] = [
                'id_unit' => $unit->id_unit_kompetensi,
                'kode' => $unit->kode_unit,
                'judul' => $unit->judul_unit,
                'elements' => $elementsData,
            ];
        }

        return response()->json([
            'status' => 'success',
            'id_data_sertifikasi' => $dataSertifikasi->id_data_sertifikasi_asesi,
            'asesi' => $dataSertifikasi->asesi->user->name ?? 'N/A',
            'skema' => [
                'id_skema' => $skema->id_skema,
                'nama' => $skema->nama_skema
            ],
            'unit_kompetensi' => $skemaData,
        ]);
    }

    /**
     * Store a newly created resource in storage (Menyimpan hasil Pra-Asesmen Mandiri).
     * Metode ini menggantikan fungsi store(Request $request, $idDataSertifikasi) sebelumnya.
     * Parameter $id akan diabaikan karena rute yang kita gunakan adalah POST: /store.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        // Dalam konteks resource, kita asumsikan ID Data Sertifikasi dikirim di body request.
        // Namun, karena rute POST Anda sebelumnya menggunakan ID di URL, saya akan kembali ke pola itu
        // dan menganggap $request berisi semua data kecuali ID Sertifikasi.
        
        // Asumsi ID Data Sertifikasi ada di URL melalui rute yang dimodifikasi.
        // Kita perlu mendapatkan $idDataSertifikasi dari URL atau Request Body.
        // Karena template Resource Controller tidak menyediakan $id di 'store',
        // kita akan mengambilnya dari body request (pola yang lebih umum untuk resource creation).
        
        // ATAU, jika rute tetap /asesi/sertifikasi/{idDataSertifikasi}/praasesmen/store,
        // kita perlu menggunakan rute yang non-resource atau memodifikasi store/update.
        
        // Solusi: Kita gunakan metode `update` untuk menyimpan/memperbarui APL-02 
        // karena APL-02 adalah data yang diperbarui dari resource DataSertifikasiAsesi.
        return $this->update($request, $request->input('id_data_sertifikasi', 0)); 
    }

    /**
     * Update the specified resource in storage (Menyimpan/Memperbarui hasil Pra-Asesmen Mandiri).
     * Metode ini akan memproses logic store sebelumnya.
     *
     * @param \Illuminate\Http\Request $request
     * @param string $id ID Data Sertifikasi ($idDataSertifikasi)
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $idDataSertifikasi = (int)$id;

        $validated = $request->validate([
            'respon' => 'required|array',
            'respon.*.id_kuk' => 'required|integer|exists:master_kriteria_unjuk_kerja,id_kriteria',
            'respon.*.jawaban' => 'required|in:K,BK',
            'respon.*.bukti_file' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:5120',
        ]);

        $responses = $validated['respon'] ?? [];

        try {
            DB::beginTransaction();
            
            foreach ($responses as $key => $respon) {
                $id_kuk = $respon['id_kuk'];
                $jawaban = $respon['jawaban'];

                $bukti_path = null;
                $fileKey = "respon.{$key}.bukti_file";

                // 1. Proses Upload File
                if ($jawaban === 'K' && $request->hasFile($fileKey)) {
                    $file = $request->file($fileKey);
                    $path = 'public/bukti_asesi/' . $idDataSertifikasi;
                    $fileName = time() . '_' . $file->getClientOriginalName();
                    
                    $bukti_path = $file->storeAs($path, $fileName);
                    $bukti_path = str_replace('public/', 'storage/', $bukti_path);
                }
                
                // 2. Simpan atau Update ke tabel respon_apl02_ia01
                ResponApl2Ia01::updateOrCreate(
                    [
                        'id_data_sertifikasi_asesi' => $idDataSertifikasi,
                        'id_kriteria' => $id_kuk,
                    ],
                    [
                        'respon_asesi_apl02' => ($jawaban === 'K'), 
                        'bukti_asesi_apl02' => $bukti_path,
                    ]
                );
            }

            // Opsional: Update status di DataSertifikasiAsesi
            DataSertifikasiAsesi::where('id_data_sertifikasi_asesi', $idDataSertifikasi)
                                ->update(['status_sertifikasi' => 'apl02_selesai']);

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Pra-Asesmen Mandiri berhasil disimpan dan dilanjutkan.',
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Gagal menyimpan Pra-Asesmen via API: " . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat menyimpan data.',
                'error_detail' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     * Metode ini tidak digunakan dalam konteks ini.
     *
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(string $id): JsonResponse
    {
        return response()->json([
            'status' => 'error',
            'message' => 'Penghapusan data Pra-Asesmen tidak didukung melalui API ini.'
        ], 403);
    }
}
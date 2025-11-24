<?php

namespace App\Http\Controllers\Apl02;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller; 
use App\Models\DataSertifikasiAsesi;
use App\Models\Asesi;
use App\Models\Jadwal;
use App\Models\Skema;
use App\Models\KelompokPekerjaan;
use App\Models\UnitKompetensi; 
use App\Models\Elemen; 
use App\Models\KriteriaUnjukKerja; 
use App\Models\ResponApl2Ia01; 
use Illuminate\Support\Facades\DB; // Jika Anda perlu debug query
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
class PraasesmenController extends Controller
{
    /**
     * Menampilkan halaman Pra-Asesmen Mandiri (APL-02)
     *
     * @param int $idAsesi ID Asesi yang sedang login/dilihat
     * @return \Illuminate\View\View
     */
    public function index($idAsesi)
    {
        // 1. Ambil Data Sertifikasi Asesi (dengan eager loading)
        $dataSertifikasi = DataSertifikasiAsesi::where('id_asesi', $idAsesi)
            // Ambil yang paling baru/sedang berjalan
            ->latest()  
            ->with([
                // KUNCI PERBAIKAN: MENGGUNAKAN NAMA RELASI SINGULAR (kelompokPekerjaan)
                'jadwal.skema.kelompokPekerjaan.unitKompetensi.elemen.kriteriaUnjukKerja', 
                'jadwal.asesor',
                'asesi.user',
            ])
            ->first();

        // -----------------------------------------------------------
        // PENGELOLAAN ERROR & DATA DEFAULT
        // -----------------------------------------------------------
        if (!$dataSertifikasi || !$dataSertifikasi->jadwal || !$dataSertifikasi->jadwal->skema) {
            $defaultData = [
                'skema_title' => 'Pra-Asesmen Mandiri',
                'error' => "Data sertifikasi, jadwal, atau skema untuk Asesi ID {$idAsesi} tidak ditemukan.",
                'currentSkema' => ['judul' => 'N/A', 'unit' => 'Tidak Ada Unit'],
                'totalSkema' => 0,
                'skemaList' => [],
                'asesor' => ['nama' => 'N/A', 'no_reg' => 'N/A'],
                'idAsesi' => $idAsesi,
                'idDataSertifikasi' => null, 
            ];
            
            // Asumsi view ada di 'praasesmen' atau 'pra-assesmen.praasesmen'
            return view('praasesmen', $defaultData);
        }

        // -----------------------------------------------------------
        // LANJUT JIKA DATA DITEMUKAN
        // -----------------------------------------------------------

        $skema = $dataSertifikasi->jadwal->skema;
        $idDataSertifikasi = $dataSertifikasi->id_data_sertifikasi_asesi;
        $asesor = $dataSertifikasi->jadwal->asesor;
        $skemaList = [];
        $unitCounter = 1;

        // Ambil semua ID KUK untuk skema ini
        // KUNCI PERBAIKAN: AKSES RELASI DENGAN SINGULAR
        $kukIds = $skema->kelompokPekerjaan 
            ->flatMap(fn ($kelompok) => $kelompok->unitKompetensi)
            ->flatMap(fn ($unit) => $unit->elemen)
            ->flatMap(fn ($elemen) => $elemen->kriteriaUnjukKerja)
            ->pluck('id_kriteria');
        
        // Ambil SEMUA respon APL-02 yang sudah diisi asesi untuk data sertifikasi ini
        $existingResponses = ResponApl2Ia01::where('id_data_sertifikasi_asesi', $idDataSertifikasi)
            ->whereIn('id_kriteria', $kukIds)
            ->pluck('respon_asesi_apl02', 'id_kriteria') // Ambil respon (1/0) keyed by id_kriteria
            ->toArray();

        $existingBukti = ResponApl2Ia01::where('id_data_sertifikasi_asesi', $idDataSertifikasi)
            ->whereIn('id_kriteria', $kukIds)
            ->pluck('bukti_asesi_apl02', 'id_kriteria') // Ambil bukti (path file) keyed by id_kriteria
            ->toArray();

        // 2. Memformat Data untuk Kebutuhan View (Iterasi Eager Loading Result)
        // KUNCI PERBAIKAN: AKSES RELASI DENGAN SINGULAR
        $unitKompetensiList = $skema->kelompokPekerjaan->flatMap(fn ($kelompok) => $kelompok->unitKompetensi); 

        foreach ($unitKompetensiList as $unit) {
            $elementsData = [];
            $elementCounter = 1;

            foreach ($unit->elemen as $element) {
                $kuksText = [];
                
                foreach ($element->kriteriaUnjukKerja as $kuk) {
                    $idKuk = $kuk->id_kriteria;
                    
                    // Cek respon yang sudah ada di array $existingResponses
                    // Konversi boolean (1/0) di DB ke string 'K'/'BK' untuk tampilan
                    $dbRespon = $existingResponses[$idKuk] ?? null;
                    $responAsesi = is_null($dbRespon) ? null : ($dbRespon ? 'K' : 'BK'); 
                    $buktiAsesi = $existingBukti[$idKuk] ?? null;

                    $kuksText[] = [
                        'text' => $kuk->kriteria_unjuk_kerja,
                        'id_kuk' => $idKuk, 
                        'respon' => $responAsesi, // 'K', 'BK', atau null
                        'bukti' => $buktiAsesi,
                    ];
                }

                $elementsData[] = [
                    'no' => $elementCounter++,
                    'id_elemen' => $element->id_elemen, 
                    'name' => $element->elemen, 
                    'kuks' => $kuksText,
                ];
            }

            $skemaList[] = [
                'unit' => $unitCounter++, 
                'id_unit' => $unit->id_unit_kompetensi, 
                'kode' => $unit->kode_unit, 
                'judul' => $unit->judul_unit, 
                'elements' => $elementsData,
            ];
        }

        // 3. Menyiapkan Data Header dan Sidebar
        $currentUnitDisplay = count($skemaList) > 0 ? 
            "({$skemaList[0]['kode']} - {$skemaList[0]['judul']})" : 
            "Tidak Ada Unit";
        
        return view('pra-assesmen.praasesmen', [
            'error' => null, 
            'skema_title' => 'Pra-Asesmen: ' . $skema->nama_skema,
            'currentSkema' => [
                'judul' => $skema->nama_skema,
                'unit' => $currentUnitDisplay,
            ],
            'totalSkema' => $unitKompetensiList->count(),
            'skemaList' => $skemaList,
            'asesor' => [
                'nama' => $asesor->nama_asesor ?? 'N/A', 
                'no_reg' => $asesor->no_reg ?? 'N/A',
            ],
            'idAsesi' => $idAsesi,
            'idDataSertifikasi' => $idDataSertifikasi,
        ]);
    }

    /**
     * Menyimpan hasil Pra-Asesmen Mandiri (APL-02) ke tabel respon_apl02_ia01.
     * Menggunakan logic store yang Anda sediakan (untuk form submission dan upload file).
     *
     * @param \Illuminate\Http\Request $request
     * @param int $idDataSertifikasi
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request, $idDataSertifikasi)
    {
        $validated = $request->validate([
            // Validasi data umum, misalnya pastikan idDataSertifikasi valid
            'respon.*.id_kuk' => 'required|integer|exists:master_kriteria_unjuk_kerja,id_kriteria',
            'respon.*.jawaban' => 'required|in:K,BK',
            'respon.*.bukti_file' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:5120', // Maks 5MB
        ]);

        $responses = $validated['respon'] ?? [];

        try {
            DB::beginTransaction();
            
            foreach ($responses as $key => $respon) {
                $id_kuk = $respon['id_kuk'];
                $jawaban = $respon['jawaban']; // 'K' atau 'BK'

                $bukti_path = null;
                $fileKey = "respon.{$key}.bukti_file"; // Untuk mengakses file dari request

                // 1. Ambil record yang sudah ada untuk mendapatkan path bukti lama
                $existingRecord = ResponApl2Ia01::where('id_data_sertifikasi_asesi', $idDataSertifikasi)
                                                ->where('id_kriteria', $id_kuk)
                                                ->first();
                
                // Gunakan path lama sebagai default
                if ($existingRecord) {
                    $bukti_path = $existingRecord->bukti_asesi_apl02;
                }

                // 2. Proses Upload File BARU jika Komponen K dan ada file terkirim
                if ($jawaban === 'K' && $request->hasFile($fileKey)) {
                    $file = $request->file($fileKey);
                    $path = 'public/bukti_asesi/' . $idDataSertifikasi;
                    $fileName = time() . '_' . $file->getClientOriginalName();
                    
                    // Hapus file lama jika ada sebelum menyimpan yang baru
                    if ($bukti_path) {
                        // Pastikan hanya menghapus file yang ada di storage
                        $oldFilePath = str_replace('storage/', 'public/', $bukti_path);
                        if (Storage::exists($oldFilePath)) {
                             Storage::delete($oldFilePath);
                        }
                    }
                    
                    // Simpan file ke storage
                    $newPath = $file->storeAs($path, $fileName);
                    // Simpan path yang mudah diakses di URL (biasanya 'storage/...' setelah menjalankan php artisan storage:link)
                    $bukti_path = str_replace('public/', 'storage/', $newPath); 
                } 
                
                // 3. Jika jawaban diubah menjadi BK (Belum Kompeten), hapus bukti file lama
                if ($jawaban === 'BK') {
                    if ($bukti_path) {
                        $oldFilePath = str_replace('storage/', 'public/', $bukti_path);
                        if (Storage::exists($oldFilePath)) {
                             Storage::delete($oldFilePath);
                        }
                    }
                    $bukti_path = null;
                }
                
                // 4. Simpan atau Update ke tabel respon_apl02_ia01
                ResponApl2Ia01::updateOrCreate(
                    [
                        'id_data_sertifikasi_asesi' => $idDataSertifikasi,
                        'id_kriteria' => $id_kuk,
                    ],
                    [
                        // 1 = K (Kompeten), 0 = BK (Belum Kompeten)
                        'respon_asesi_apl02' => ($jawaban === 'K'), 
                        'bukti_asesi_apl02' => $bukti_path,
                    ]
                );
            }

            // Opsional: Update status di DataSertifikasiAsesi
            DataSertifikasiAsesi::where('id_data_sertifikasi_asesi', $idDataSertifikasi)
                                ->update(['status_sertifikasi' => 'apl02_selesai']);

            DB::commit();

            return redirect()->back()->with('success', 'Pra-Asesmen Mandiri berhasil disimpan.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Gagal menyimpan Pra-Asesmen: " . $e->getMessage()); 
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage())->withInput();
        }
    }
}
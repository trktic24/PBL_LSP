<?php

namespace App\Http\Controllers\Asesi\asesmen;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DataSertifikasiAsesi;
use App\Models\DataPortofolio; 
use App\Models\BuktiPortofolioIA08IA09; 
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class AssessmenFRIA09Controller extends Controller
{
    protected function getSkemaRelatedData($id_skema)
    {
        // ... (Bagian ini TETAP SAMA seperti sebelumnya, tidak saya ubah) ...
        if (!$id_skema) {
             return [
                'data_unit_kompetensi' => [], 
            ];
        }

        $kelompokPekerjaanList = DB::table('kelompok_pekerjaan')
            ->where('id_skema', $id_skema)
            ->get();
        
        $structuredData = [];

        foreach ($kelompokPekerjaanList as $kelompok) {
            $units = DB::table('unit_kompetensi')
                ->where('id_kelompok_pekerjaan', $kelompok->id_kelompok_pekerjaan)
                ->get()
                ->map(function ($unit) {
                    return [
                        'code' => $unit->kode_unit,
                        'title' => $unit->judul_unit,
                    ];
                })->toArray();

            if (!empty($units)) {
                $structuredData[] = [
                    'nama_kelompok' => $kelompok->nama_kelompok_pekerjaan,
                    'units' => $units
                ];
            }
        }

        return [
            'data_unit_kompetensi' => $structuredData, 
        ];
    }
    
    public function index($id)
    {
        $sertifikasi = DataSertifikasiAsesi::with(['asesi', 'jadwal.asesor', 'jadwal.skema', 'jadwal.jenisTuk'])
                            ->findOrFail($id);

        if (Auth::check()) {
             $asesiLogin = Auth::user()->asesi;
             if ($asesiLogin && $sertifikasi->id_asesi != $asesiLogin->id_asesi) {
                 abort(403, 'Anda tidak berhak mengakses data ini.');
             }
        }

        $asesi = $sertifikasi->asesi;
        $asesor = optional($sertifikasi->jadwal)->asesor; 
        $skema = optional($sertifikasi->jadwal)->skema; 
        $jadwal = $sertifikasi->jadwal;

        // --- PERUBAHAN UTAMA DI SINI ---
        
        // 1. Ambil dari DataPortofolio, BUKAN BuktiDasar
        // 2. Filter: Hanya ambil yang 'persyaratan_administratif' TIDAK NULL
        $portofolio_data = DataPortofolio::where('id_data_sertifikasi_asesi', $sertifikasi->id_data_sertifikasi_asesi)
                                ->whereNotNull('persyaratan_administratif') // Hanya Administratif
                                ->where('persyaratan_administratif', '!=', '') // Jaga-jaga string kosong
                                ->get();
        
        // Ambil ID Portofolio untuk cari jawaban wawancara (IA.09)
        $portofolio_ids = $portofolio_data->pluck('id_portofolio');
        
        // Load data pertanyaan/jawaban
        $ia09_data_map = BuktiPortofolioIA08IA09::whereIn('id_portofolio', $portofolio_ids)
                                                ->get()
                                                ->keyBy('id_portofolio');

        // Mapping Data
        $merged_data = $portofolio_data->map(function($item) use ($ia09_data_map) {
            $id_input = $item->id_portofolio;
            $ia09_record = $ia09_data_map->get($id_input);
            
            return (object)[
                'id_input' => $id_input, 
                // Di sini kita pakai Nama Dokumen yang cantik, bukan path file
                'bukti_dasar' => $item->persyaratan_administratif, 
                'pertanyaan_teks' => optional($ia09_record)->daftar_pertanyaan_wawancara ?? 'Pertanyaan belum diinput oleh Asesor',
                'kesimpulan_jawaban_asesi' => optional($ia09_record)->kesimpulan_jawaban_asesi ?? '', 
            ];
        });

        // ---------------------------------

        $jenis_tuk_db = optional(optional($jadwal)->jenisTuk)->jenis_tuk ?? 'Sewaktu';
        $tanggal_pelaksanaan = optional(optional($jadwal)->tanggal_pelaksanaan)->format('d F Y') ?? date('d F Y'); 
        $skemaData = $this->getSkemaRelatedData(optional($skema)->id_skema);

        // --- TAMBAHKAN LOGIC GAMBAR INI ---
        $gambarSkema = $sertifikasi->jadwal && $sertifikasi->jadwal->skema && $sertifikasi->jadwal->skema->gambar
            ? asset('storage/' . $sertifikasi->jadwal->skema->gambar)
            : asset('images/default_pic.jpeg');
        
        return view('asesi.assesmen.FRIA09_Wawancara', array_merge($skemaData, [
            'sertifikasi' => $sertifikasi,
            'asesi' => $asesi,
            'asesor' => $asesor,
            'skema' => $skema,
            'tanggal_pelaksanaan' => $tanggal_pelaksanaan,
            'jenis_tuk_db' => $jenis_tuk_db,
            'data_unit_kompetensi' => $skemaData['data_unit_kompetensi'],
            'tanda_tangan_asesor_path' => optional($asesor)->tanda_tangan,
            'tanda_tangan_asesi_path' => optional($asesi)->tanda_tangan,
            'merged_data' => $merged_data, 
            'gambarSkema' => $gambarSkema,
        ]));
    }
}
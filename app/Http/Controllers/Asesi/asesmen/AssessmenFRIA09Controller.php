<?php

namespace App\Http\Controllers\Asesi\asesmen;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DataSertifikasiAsesi;
use App\Models\BuktiDasar; 
use App\Models\BuktiPortofolioIA08IA09; 
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class AssessmenFRIA09Controller extends Controller
{
    protected function getSkemaRelatedData($id_skema)
    {
        if (!$id_skema) {
             return [
                'data_unit_kompetensi' => [], // Kita ganti struktur return-nya
            ];
        }

        // 1. Ambil SEMUA kelompok pekerjaan, bukan cuma first()
        $kelompokPekerjaanList = DB::table('kelompok_pekerjaan')
            ->where('id_skema', $id_skema)
            ->get();
        
        $structuredData = [];

        // 2. Loop setiap kelompok pekerjaan
        foreach ($kelompokPekerjaanList as $kelompok) {
            // Ambil unit kompetensi KHUSUS buat kelompok pekerjaan ini
            $units = DB::table('unit_kompetensi')
                ->where('id_kelompok_pekerjaan', $kelompok->id_kelompok_pekerjaan)
                ->get()
                ->map(function ($unit) {
                    return [
                        'code' => $unit->kode_unit,
                        'title' => $unit->judul_unit,
                    ];
                })->toArray();

            // Masukin ke array kalau unitnya ada isinya
            if (!empty($units)) {
                $structuredData[] = [
                    'nama_kelompok' => $kelompok->nama_kelompok_pekerjaan,
                    'units' => $units
                ];
            }
        }

        return [
            // Kita return satu variabel array besar yang isinya Groups + Units
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

        $bukti_portofolio_data = BuktiDasar::where('id_data_sertifikasi_asesi', $sertifikasi->id_data_sertifikasi_asesi)->get();
        
        $bukti_dasar_ids = $bukti_portofolio_data->pluck('id_bukti_dasar');
        $ia09_data_map = BuktiPortofolioIA08IA09::whereIn('id_portofolio', $bukti_dasar_ids)
                                                ->get()
                                                ->keyBy('id_portofolio');

        $merged_data = $bukti_portofolio_data->map(function($bukti) use ($ia09_data_map) {
            $id_input = $bukti->id_bukti_dasar;
            $ia09_record = $ia09_data_map->get($id_input);
            
            return (object)[
                'id_input' => $id_input, 
                'bukti_dasar' => $bukti->bukti_dasar,
                'pertanyaan_teks' => optional($ia09_record)->daftar_pertanyaan_wawancara ?? 'Pertanyaan belum diinput oleh Asesor',
                'kesimpulan_jawaban_asesi' => optional($ia09_record)->kesimpulan_jawaban_asesi ?? '', 
            ];
        });

        $jenis_tuk_db = optional(optional($jadwal)->jenisTuk)->jenis_tuk ?? 'Sewaktu';
        $tanggal_pelaksanaan = optional(optional($jadwal)->tanggal_pelaksanaan)->format('d F Y') ?? date('d F Y'); 
        $skemaData = $this->getSkemaRelatedData(optional($skema)->id_skema);
        
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
        ]));
    }

}
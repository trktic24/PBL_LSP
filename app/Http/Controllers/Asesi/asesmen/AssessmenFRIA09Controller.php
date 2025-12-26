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
                'unitsToDisplay' => [], 
                'kelompok_pekerjaan' => 'Skema tidak terhubung', 
            ];
        }

        $kelompokPekerjaanDB = DB::table('kelompok_pekerjaan')->where('id_skema', $id_skema)->first();
        $units_for_table = [];
        $nama_kelompok_pekerjaan = 'Kelompok Pekerjaan Tidak Ditemukan';

        if ($kelompokPekerjaanDB) {
            $nama_kelompok_pekerjaan = $kelompokPekerjaanDB->nama_kelompok_pekerjaan;
            $id_kelompok_pekerjaan = $kelompokPekerjaanDB->id_kelompok_pekerjaan;

            $unitKompetensiList = DB::table('unit_kompetensi')
                ->where('id_kelompok_pekerjaan', $id_kelompok_pekerjaan)
                ->get();
            
            $units_for_table = $unitKompetensiList->map(function ($unit) {
                return [
                    'code' => $unit->kode_unit,
                    'title' => $unit->judul_unit,
                ];
            })->toArray();
        }

        return [
            'unitsToDisplay' => $units_for_table, 
            'kelompok_pekerjaan' => $nama_kelompok_pekerjaan, 
        ];
    }
    
    public function index()
    {
        $sertifikasi = null;
        if (Auth::check()) {
            $asesi = optional(Auth::user())->asesi;
            if ($asesi) {
                $sertifikasi = $asesi->dataSertifikasi()->latest()->first();
            }
        } 
        
        if (!$sertifikasi) {
            $sertifikasi = DataSertifikasiAsesi::with(['asesi', 'jadwal.asesor', 'jadwal.skema', 'jadwal.jenisTuk'])
                                ->orderBy('id_data_sertifikasi_asesi', 'asc')
                                ->first();
        }

        if (!$sertifikasi) {
             return redirect()->back()->with('error', 'Data tidak ditemukan');
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

        // --- BAGIAN YANG DIUBAH: MENGHUBUNGKAN KE KOLOM daftar_pertanyaan_wawancara ---
        $merged_data = $bukti_portofolio_data->map(function($bukti) use ($ia09_data_map) {
            $id_input = $bukti->id_bukti_dasar;
            $ia09_record = $ia09_data_map->get($id_input);
            
            return (object)[
                'id_input' => $id_input, 
                'bukti_dasar' => $bukti->bukti_dasar,
                // Mengambil pertanyaan dari kolom database baru
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
            'unitsToDisplay' => $skemaData['unitsToDisplay'],
            'kelompok_pekerjaan' => $skemaData['kelompok_pekerjaan'],
            'tanda_tangan_asesor_path' => optional($asesor)->tanda_tangan,
            'tanda_tangan_asesi_path' => optional($asesi)->tanda_tangan,
            'merged_data' => $merged_data, 
        ]));
    }

    public function store(Request $request)
    {
        foreach ($request->kesimpulan as $id_portofolio => $jawaban) {
            BuktiPortofolioIA08IA09::where('id_portofolio', $id_portofolio)
                ->update(['kesimpulan_jawaban_asesi' => $jawaban]);
        }
        return redirect()->route('tracker')->with('success', 'Penilaian wawancara berhasil disimpan.');
    }
}
<?php

namespace App\Http\Controllers\Asesi\asesmen;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DataSertifikasiAsesi;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class AssessmenFRIA09Controller extends Controller
{
    /**
     * Fungsi Helper untuk mendapatkan data Unit Kompetensi
     */
    protected function getSkemaRelatedData($id_skema)
    {
        if (!$id_skema) {
             return [
                'unitsToDisplay' => [], 
                'kelompok_pekerjaan' => 'Skema tidak terhubung', 
            ];
        }

        // 1. Ambil Kelompok Pekerjaan (nama_kelompok_pekerjaan)
        $kelompokPekerjaanDB = DB::table('kelompok_pekerjaan')->where('id_skema', $id_skema)->first();
        $units_for_table = [];
        $nama_kelompok_pekerjaan = 'Kelompok Pekerjaan Tidak Ditemukan';

        if ($kelompokPekerjaanDB) {
            $nama_kelompok_pekerjaan = $kelompokPekerjaanDB->nama_kelompok_pekerjaan;
            $id_kelompok_pekerjaan = $kelompokPekerjaanDB->id_kelompok_pekerjaan;

            // 2. Ambil Unit Kompetensi (kode_unit, judul_unit)
            $unitKompetensiList = DB::table('master_unit_kompetensi')
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
    
    /**
     * Menampilkan form FR.IA.09 Pertanyaan Wawancara.
     */
    public function index()
    {
        // 1. Tentukan ID Sertifikasi Aktif
        $sertifikasi = null;
        
        if (Auth::check()) {
            // Jika user login, ambil data Sertifikasi terakhir mereka
            $asesi = optional(Auth::user())->asesi;
            if ($asesi) {
                $sertifikasi = $asesi->dataSertifikasi()->latest()->first();
            }
        } 
        
        if (!$sertifikasi) {
            // Jika tidak login ATAU tidak ada data Asesi, ambil data Sertifikasi PERTAMA (ID 1)
            $sertifikasi = DataSertifikasiAsesi::with(['asesi', 'jadwal.asesor', 'jadwal.skema', 'jadwal.jenisTuk'])
                                ->orderBy('id_data_sertifikasi_asesi', 'asc')
                                ->first();
        }
        
        if (!$sertifikasi) {
            // Default fallback jika DB benar-benar kosong
            return view('asesi.assesmen.FRIA09_Wawancara', [
                'error' => 'Database kosong. Tidak dapat memuat data sertifikasi.',
                'sertifikasi' => null, 'asesi' => null, 'asesor' => null, 'skema' => null, 
                'unitsToDisplay' => [], 'kelompok_pekerjaan' => null, 
                'tanggal_pelaksanaan' => date('d/m/Y'), 'jenis_tuk_db' => 'Sewaktu',
                'tanda_tangan_asesor_path' => null, 'tanda_tangan_asesi_path' => null,
            ]);
        }

        // 2. Deklarasi Data (Menggunakan optional helper untuk menghindari error jika relasi null)
        $asesi = $sertifikasi->asesi;
        $asesor = optional($sertifikasi->jadwal)->asesor; 
        $skema = optional($sertifikasi->jadwal)->skema; 
        $jadwal = $sertifikasi->jadwal;

        $jenis_tuk_db = optional(optional($jadwal)->jenisTuk)->jenis_tuk ?? 'Sewaktu';
        $tanggal_pelaksanaan = optional(optional($jadwal)->tanggal_pelaksanaan)->format('d/m/Y') ?? date('d/m/Y'); 
        
        $skemaData = $this->getSkemaRelatedData(optional($skema)->id_skema);
        
        // --- DATA KHUSUS FORM & TANDA TANGAN ---
        $pertanyaanIA09Data = null; 
        $tanda_tangan_asesor_path = optional($asesor)->tanda_tangan ?? null;
        $tanda_tangan_asesi_path = optional($asesi)->tanda_tangan ?? null; 
        
        return view('asesi.assesmen.FRIA09_Wawancara', array_merge($skemaData, [
            'sertifikasi' => $sertifikasi,
            'asesi' => $asesi,
            'asesor' => $asesor,
            'skema' => $skema,
            'tanggal_pelaksanaan' => $tanggal_pelaksanaan,
            'jenis_tuk_db' => $jenis_tuk_db,
            'pertanyaanIA09Data' => $pertanyaanIA09Data,
            
            'id_sertifikasi_aktif' => $sertifikasi->id_data_sertifikasi_asesi,
            
            // Variabel yang dibutuhkan oleh View
            'unitsToDisplay' => $skemaData['unitsToDisplay'],
            'kelompok_pekerjaan' => $skemaData['kelompok_pekerjaan'],
            'tanda_tangan_asesor_path' => $tanda_tangan_asesor_path,
            'tanda_tangan_asesi_path' => $tanda_tangan_asesi_path,
        ]));
    }

    /**
     * Menyimpan atau memperbarui data FR.IA.09.
     */
    public function store(Request $request)
    {
        // ... (Logika penyimpanan) ...
        return redirect()->route('tracker')->with('success', 'Penilaian wawancara berhasil disimpan.');
    }
}
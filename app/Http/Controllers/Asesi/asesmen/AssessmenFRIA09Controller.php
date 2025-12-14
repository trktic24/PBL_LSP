<?php

namespace App\Http\Controllers\Asesi\asesmen;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DataSertifikasiAsesi;
use App\Models\BuktiDasar; // <<< Import Model BuktiDasar
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
        // ... (Fungsi tetap sama) ...
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
        // 1. Tentukan ID Sertifikasi Aktif (Logic Public/Auth Access)
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
            // Jika DB kosong, kirim data kosong agar view bisa di render
            return view('asesi.assesmen.FRIA09_Wawancara', [
                'error' => 'Database kosong. Tidak dapat memuat data sertifikasi.',
                'sertifikasi' => (object)['id_data_sertifikasi_asesi' => 0], 'asesi' => (object)['id_asesi' => null, 'nama_lengkap' => 'Guest', 'tanda_tangan' => null], 
                'asesor' => (object)['nama_lengkap' => 'N/A', 'tanda_tangan' => null], 'skema' => (object)['nama_skema' => 'N/A', 'nomor_skema' => 'N/A', 'id_skema' => null], 
                'unitsToDisplay' => [], 'kelompok_pekerjaan' => null, 
                'tanggal_pelaksanaan' => date('d/m/Y'), 'jenis_tuk_db' => 'Sewaktu',
                'tanda_tangan_asesor_path' => null, 'tanda_tangan_asesi_path' => null,
                'bukti_portofolio_data' => collect(), // Mengirim data kosong untuk loop
                'judul_kegiatan_db' => 'Proyek Uji Coba',
            ]);
        }

        // 2. Deklarasi Data Utama
        $asesi = $sertifikasi->asesi;
        $asesor = optional($sertifikasi->jadwal)->asesor; 
        $skema = optional($sertifikasi->jadwal)->skema; 
        $jadwal = $sertifikasi->jadwal;

        // 3. Ambil Data Dinamis Bukti Portofolio dari DB
        $bukti_portofolio_data = BuktiDasar::where('id_data_sertifikasi_asesi', $sertifikasi->id_data_sertifikasi_asesi)->get();
        
        // 4. Data Pelengkap
        $jenis_tuk_db = optional(optional($jadwal)->jenisTuk)->jenis_tuk ?? 'Sewaktu';
        $tanggal_pelaksanaan = optional(optional($jadwal)->tanggal_pelaksanaan)->format('d/m/Y') ?? date('d/m/Y'); 
        $skemaData = $this->getSkemaRelatedData(optional($skema)->id_skema);
        $judul_kegiatan_db = 'Proyek Pendaftaran Sertifikasi'; // Asumsi nilai default jika tidak ada form terstruktur
        
        $tanda_tangan_asesor_path = optional($asesor)->tanda_tangan ?? null;
        $tanda_tangan_asesi_path = optional($asesi)->tanda_tangan ?? null; 
        
        return view('asesi.assesmen.FRIA09_Wawancara', array_merge($skemaData, [
            'sertifikasi' => $sertifikasi,
            'asesi' => $asesi,
            'asesor' => $asesor,
            'skema' => $skema,
            'tanggal_pelaksanaan' => $tanggal_pelaksanaan,
            'jenis_tuk_db' => $jenis_tuk_db,
            'judul_kegiatan_db' => $judul_kegiatan_db,
            
            // Variabel yang dibutuhkan oleh View
            'unitsToDisplay' => $skemaData['unitsToDisplay'],
            'kelompok_pekerjaan' => $skemaData['kelompok_pekerjaan'],
            'tanda_tangan_asesor_path' => $tanda_tangan_asesor_path,
            'tanda_tangan_asesi_path' => $tanda_tangan_asesi_path,
            
            // --- DATA BUKTI PORTOFOLIO DINAMIS ---
            'bukti_portofolio_data' => $bukti_portofolio_data, 
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
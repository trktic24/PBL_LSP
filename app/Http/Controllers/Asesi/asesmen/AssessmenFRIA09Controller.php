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
    /**
     * Fungsi Helper untuk mendapatkan data Unit Kompetensi
     */
    protected function getSkemaRelatedData($id_skema)
    {
        // ... (Logika getSkemaRelatedData tetap sama) ...
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
    
    /**
     * Menampilkan form FR.IA.09 Pertanyaan Wawancara.
     */
    public function index()
    {
        // Data Statis Pertanyaan Wawancara (Digunakan di View)
        $pertanyaan_static = [
            '1' => 'Sesuai dengan bukti no. 1 yang Anda ajukan, jelaskan cara Anda menganalisis kebutuhan pengguna.', 
            '2' => 'Bagaimana Anda memastikan kode yang Anda buat mematuhi standar keamanan, terutama dalam hal validasi input?',
            '3' => 'Jelaskan tahapan *debugging* yang efisien saat Anda menghadapi kesalahan logika di proyek Anda.',
            '4' => 'Bagaimana Anda mengelola alur data untuk menghindari redundansi?',
        ];
        $bukti_static = [
            '1' => 'Dokumen Kebutuhan', 
            '2' => 'Laporan Uji Keamanan',
            '3' => 'Screenshoot Konsol Error',
            '4' => 'Diagram ERD',
        ];
        
        // 1. Tentukan ID Sertifikasi Aktif
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
        
        // Data Fallback Dasar jika DB kosong (untuk menghindari error di langkah 3 & 4)
        if (!$sertifikasi) {
            $data_loop_ia09 = collect(range(1, 4))->map(fn($i) => (object)['id_input' => $i, 'bukti_dasar' => $bukti_static[$i], 'pertanyaan_teks' => $pertanyaan_static[$i], 'kesimpulan_jawaban_asesi' => '']);

            return view('asesi.assesmen.FRIA09_Wawancara', [
                'error' => 'Database kosong. Tidak dapat memuat data sertifikasi.',
                'sertifikasi' => (object)['id_data_sertifikasi_asesi' => 0], 'asesi' => (object)['id_asesi' => null, 'nama_lengkap' => 'Guest', 'tanda_tangan' => null], 
                'asesor' => (object)['nama_lengkap' => 'N/A', 'tanda_tangan' => null], 'skema' => (object)['nama_skema' => 'N/A', 'nomor_skema' => 'N/A', 'id_skema' => null], 
                'unitsToDisplay' => [], 'kelompok_pekerjaan' => null, 
                'tanggal_pelaksanaan' => date('d/m/Y'), 'jenis_tuk_db' => 'Sewaktu',
                'tanda_tangan_asesor_path' => null, 'tanda_tangan_asesi_path' => null,
                'bukti_portofolio_data' => collect(), 
                'merged_data' => $data_loop_ia09, 
                'pertanyaan_static' => $pertanyaan_static,
                'judul_kegiatan_db' => 'Proyek Uji Coba',
            ]);
        }

        // 2. Deklarasi Data Utama
        $asesi = $sertifikasi->asesi;
        $asesor = optional($sertifikasi->jadwal)->asesor; 
        $skema = optional($sertifikasi->jadwal)->skema; 
        $jadwal = $sertifikasi->jadwal;

        // 3. Ambil Data Bukti Portofolio (BuktiDasar) - MENENTUKAN JUMLAH BARIS
        $bukti_portofolio_data = BuktiDasar::where('id_data_sertifikasi_asesi', $sertifikasi->id_data_sertifikasi_asesi)->get();
        
        // 4. Ambil Data Kesimpulan Jawaban Asesi (IA09)
        $bukti_dasar_ids = $bukti_portofolio_data->pluck('id_bukti_dasar');
        // Kumpulkan semua data IA09 yang ada, lalu konversi ke array asosiatif [id_portofolio => object] untuk pencarian cepat
        $ia09_data_map = BuktiPortofolioIA08IA09::whereIn('id_portofolio', $bukti_dasar_ids)
                                                ->get()
                                                ->keyBy('id_portofolio');

        // 5. Membuat data terintegrasi (Jumlah baris = Jumlah BuktiDasar)
        $merged_data = $bukti_portofolio_data->map(function($bukti, $index) use ($ia09_data_map, $pertanyaan_static, $bukti_static) {
            $id_input = $bukti->id_bukti_dasar;
            $nomor = $index + 1;
            
            // Mencari data IA09 yang sudah ada berdasarkan id_input (id_bukti_dasar)
            $ia09_record = $ia09_data_map->get($id_input);
            
            // FALLBACK UNTUK PERTANYAAN/BUKTI STATIS
            $pertanyaan_teks = $pertanyaan_static[$nomor] ?? 'Pertanyaan Statis No. ' . $nomor;
            
            return (object)[
                'id_input' => $id_input, 
                'bukti_dasar' => $bukti->bukti_dasar,
                'pertanyaan_teks' => $pertanyaan_teks,
                // Mengambil nilai kesimpulan dari DB jika ada, jika tidak, string kosong
                'kesimpulan_jawaban_asesi' => optional($ia09_record)->kesimpulan_jawaban_asesi ?? '', 
            ];
        });


        // 6. Data Pelengkap
        $jenis_tuk_db = optional(optional($jadwal)->jenisTuk)->jenis_tuk ?? 'Sewaktu';
        $tanggal_pelaksanaan = optional(optional($jadwal)->tanggal_pelaksanaan)->format('d/m/Y') ?? date('d/m/Y'); 
        $skemaData = $this->getSkemaRelatedData(optional($skema)->id_skema);
        $judul_kegiatan_db = 'Proyek Pendaftaran Sertifikasi';
        
        $tanda_tangan_asesor_path = optional($asesor)->tanda_tangan ?? null;
        $tanda_tangan_asesi_path = optional($asesi)->tanda_tangan ?? null; 
        
        // Final Check: Jika BuktiDasar kosong, gunakan data statis
        if ($merged_data->isEmpty() && $bukti_portofolio_data->isEmpty()) {
            $merged_data = collect(range(1, 4))->map(function($i) use ($pertanyaan_static, $bukti_static) {
                return (object)['id_input' => $i, 'bukti_dasar' => $bukti_static[$i], 'pertanyaan_teks' => $pertanyaan_static[$i], 'kesimpulan_jawaban_asesi' => ''];
            });
        }
        
        return view('asesi.assesmen.FRIA09_Wawancara', array_merge($skemaData, [
            'sertifikasi' => $sertifikasi,
            'asesi' => $asesi,
            'asesor' => $asesor,
            'skema' => $skema,
            'tanggal_pelaksanaan' => $tanggal_pelaksanaan,
            'jenis_tuk_db' => $jenis_tuk_db,
            'judul_kegiatan_db' => $judul_kegiatan_db,
            
            'unitsToDisplay' => $skemaData['unitsToDisplay'],
            'kelompok_pekerjaan' => $skemaData['kelompok_pekerjaan'],
            'tanda_tangan_asesor_path' => $tanda_tangan_asesor_path,
            'tanda_tangan_asesi_path' => $tanda_tangan_asesi_path,
            
            'bukti_portofolio_data' => $bukti_portofolio_data, 
            'merged_data' => $merged_data, // Menggunakan merged_data yang sudah disinkronisasi
            'pertanyaan_static' => $pertanyaan_static,
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
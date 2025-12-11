<?php

namespace App\Http\Controllers\asesmen;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Asesi; 
use App\Models\Asesor; 
use App\Models\Skema; 
use App\Models\Jadwal; 
use App\Models\PoinIA04A; 
use App\Models\JenisTuk; 
use App\Models\DataSertifikasiAsesi; 
use App\Models\AspekIA04B; 
use App\Models\ResponIA04A; // <<< IMPORT MODEL BARU
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage; // Untuk Tanda Tangan

class AssessmenFRIA04tController extends Controller
{
    /**
     * Mengambil data umum (Kelompok Pekerjaan & Unit Kompetensi) berdasarkan ID Skema.
     */
    protected function getSkemaRelatedData($id_skema)
    {
        if (!$id_skema) {
             return [
                 'mockUnits' => [], 
                 'kelompok_pekerjaan' => 'Skema tidak terhubung', 
             ];
        }

        // 1. Ambil Kelompok Pekerjaan (nama_kelompok_pekerjaan)
        $kelompokPekerjaanDB = DB::table('kelompok_pekerjaan')
            ->where('id_skema', $id_skema)
            ->first();

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
                    'code' => $unit->kode_unit, // Kode Unit
                    'title' => $unit->judul_unit, // Judul Unit
                ];
            })->toArray();
        }

        return [
            'units_for_table' => $units_for_table, 
            'kelompok_pekerjaan' => $nama_kelompok_pekerjaan, 
        ];
    }

    // ----------------------------------------------------------------------------------
    // SUNDUT PANDANG ASESOR (Dibuat lebih ringkas)
    // ----------------------------------------------------------------------------------

    /**
     * Menampilkan form FR.IA.04A untuk ASESOR.
     */
    public function showIA04A()
    {
        // Ganti ID ini dengan logic autentikasi
        $id_sertifikasi = 1; 

        $sertifikasi = DataSertifikasiAsesi::with(['asesi', 'jadwal.asesor', 'jadwal.skema', 'jadwal.jenisTuk'])
            ->find($id_sertifikasi) ?? DataSertifikasiAsesi::with(['asesi', 'jadwal.asesor', 'jadwal.skema', 'jadwal.jenisTuk'])->first();

        if (!$sertifikasi) {
            return redirect()->back()->with('error', 'Tidak ada Data Sertifikasi ditemukan di database.');
        }

        $active_id_sertifikasi = $sertifikasi->id_data_sertifikasi_asesi;
        $asesi = $sertifikasi->asesi;
        $asesor = $sertifikasi->jadwal->asesor ?? null; 
        $skema = $sertifikasi->jadwal->skema ?? null; 
        $jadwal = $sertifikasi->jadwal;

        $poinIA04A = PoinIA04A::where('id_data_sertifikasi_asesi', $active_id_sertifikasi)->first();
        $responIA04A = ResponIA04A::where('id_data_sertifikasi_asesi', $active_id_sertifikasi)->first();
        
        $jenis_tuk_db = strtolower(optional($jadwal->jenisTuk)->jenis_tuk ?? 'Sewaktu');
        $tanggal_pelaksanaan = optional($jadwal->tanggal_pelaksanaan)->format('d/m/Y') ?? date('d/m/Y'); 
        $skemaData = $this->getSkemaRelatedData(optional($skema)->id_skema);
        
        $judul_kegiatan_db = 'Proyek Pembuatan Sistem Informasi Pendaftaran Mahasiswa Baru'; 
        
        $tanda_tangan_asesor_path = optional($asesor)->tanda_tangan ?? null;
        $tanda_tangan_asesi_path = optional($asesi)->tanda_tangan ?? null;
        $rekomendasi_db = optional($sertifikasi)->rekomendasi_IA04B; 
        
        $hal_yang_disiapkan_db = optional($poinIA04A)->hal_yang_disiapkan ?? null;
        $hal_yang_didemonstrasikan_db = optional($poinIA04A)->hal_yang_didemonstrasikan ?? null;

        // Ambil umpan balik asesi dari respon_ia04A (jika sudah ada)
        $umpan_balik_asesi_db = optional($responIA04A)->umpan_balik_untuk_asesi ?? null;


        return view('assesmen.FRIA04_Asesor', array_merge($skemaData, [
            'asesi' => $asesi,
            'asesor' => $asesor,
            'skema' => $skema,
            'jenis_tuk_db' => $jenis_tuk_db,
            'judul_kegiatan_db' => $judul_kegiatan_db,
            'tanggal_pelaksanaan' => $tanggal_pelaksanaan,
            'sertifikasi' => $sertifikasi, 
            
            'poinIA04A' => $poinIA04A,
            'hal_yang_disiapkan_db' => $hal_yang_disiapkan_db,
            'hal_yang_didemonstrasikan_db' => $hal_yang_didemonstrasikan_db,
            'umpan_balik_asesi_db' => $umpan_balik_asesi_db, // Untuk diisi Asesor

            'tanda_tangan_asesor_path' => $tanda_tangan_asesor_path,
            'tanda_tangan_asesi_path' => $tanda_tangan_asesi_path,
            'rekomendasi_db' => $rekomendasi_db,
        ]));
    }

    /**
     * Menyimpan data dari form FR.IA.04A ASESOR.
     */
    public function storeIA04A(Request $request)
    {
        $id_sertifikasi = $request->input('id_sertifikasi');
        $poin_id = $request->input('poin_id'); 
        $rekomendasi_ia04b = $request->input('rekomendasi_ia04b');
        $umpan_balik = $request->input('umpan_balik_asesi'); // Umpan Balik Asesor

        // 1. Simpan/Update data di poin_ia04A (Instruksi dan Hasil Demonstrasi)
        // Dapatkan atau buat PoinIA04A agar kita bisa mendapatkan ID-nya untuk ResponIA04A
        $poinIA04A = PoinIA04A::updateOrCreate(
            ['id_poin_ia04A' => $poin_id], // Cek berdasarkan PK
            [
                'id_data_sertifikasi_asesi' => $id_sertifikasi,
                'hal_yang_disiapkan' => $request->input('skenario_umum'),
                'hal_yang_didemonstrasikan' => $request->input('hasil_umum'),
                'waktu_disiapkan_menit' => now(), 
                'waktu_demonstrasi_menit' => now(), 
            ]
        );
        
        // 2. Simpan/Update Rekomendasi (di tabel data_sertifikasi_asesi)
        DataSertifikasiAsesi::where('id_data_sertifikasi_asesi', $id_sertifikasi)
            ->update([
                'rekomendasi_IA04B' => $rekomendasi_ia04b,
            ]);

        // 3. Simpan/Update Umpan Balik Asesor ke tabel respon_ia04A <<< LOGIC UTAMA PENYIMPANAN
        if ($poinIA04A) {
            ResponIA04A::updateOrCreate(
                [
                    'id_data_sertifikasi_asesi' => $id_sertifikasi,
                    // Kita asumsikan relasi ke poin_ia04A juga harus diisi
                    'id_poin_ia04A' => $poinIA04A->id_poin_ia04A, 
                ],
                [
                    'umpan_balik_untuk_asesi' => $umpan_balik,
                ]
            );
        }

        return redirect()->back()->with('success', 'Formulir FR.IA.04A Asesor berhasil disimpan.');
    }

    // ----------------------------------------------------------------------------------
    // SUNDUT PANDANG ASESI
    // ----------------------------------------------------------------------------------

    /**
     * Menampilkan form FR.IA.04A untuk ASESI (Mode Baca + Input Tanggapan).
     */
    public function showIA04AAsesi()
    {
        // Ganti ID ini dengan logic autentikasi
        $id_sertifikasi = 1; 

        // 1. Ambil data Sertifikasi
        $sertifikasi = DataSertifikasiAsesi::with(['asesi', 'jadwal.asesor', 'jadwal.skema', 'jadwal.jenisTuk'])
            ->find($id_sertifikasi) ?? DataSertifikasiAsesi::with(['asesi', 'jadwal.asesor', 'jadwal.skema', 'jadwal.jenisTuk'])->first();

        if (!$sertifikasi) {
            return redirect()->back()->with('error', 'Tidak ada Data Sertifikasi ditemukan di database.');
        }
        
        $active_id_sertifikasi = $sertifikasi->id_data_sertifikasi_asesi;

        // 2. AMBIL DATA DARI POIN_IA04A (Instruksi & Hasil Asesor)
        $poinIA04A = DB::table('poin_ia04A')
            ->where('id_data_sertifikasi_asesi', $active_id_sertifikasi) 
            ->first();

        // 3. AMBIL DATA RESPON_IA04A (Umpan Balik Asesor ke Asesi) <<< PERBAIKAN DI SINI
        $responIA04A = ResponIA04A::where('id_data_sertifikasi_asesi', $active_id_sertifikasi)
             ->first();

        // 4. AMBIL DATA ASPEK_IA04B (Tabel Penilaian)
        $aspekIA04BData = AspekIA04B::where('id_data_sertifikasi_asesi', $active_id_sertifikasi)
            ->get();
        
        // Mengisi variabel DB dengan data
        $skenario_umum_db = optional($poinIA04A)->hal_yang_disiapkan ?? "Instruksi dari Asesor belum tersedia.";
        $hasil_umum_db = optional($poinIA04A)->hal_yang_didemonstrasikan ?? "Hasil demonstrasi/output belum ditetapkan oleh Asesor.";

        // Mengambil umpan balik dari respon_ia04A <<< PERBAIKAN DI SINI
        $umpan_balik_asesi_db = optional($responIA04A)->umpan_balik_untuk_asesi ?? "Umpan balik dari Asesor belum tersedia.";

        // Ambil data dari relasi
        $asesi = $sertifikasi->asesi;
        $asesor = $sertifikasi->jadwal->asesor ?? null; 
        $skema = $sertifikasi->jadwal->skema ?? null;
        $jadwal = $sertifikasi->jadwal;

        // Data TUK & Tanggal
        $jenis_tuk_db = strtolower(optional($jadwal->jenisTuk)->jenis_tuk ?? 'Sewaktu');
        $tanggal_pelaksanaan = optional($jadwal->tanggal_pelaksanaan)->format('d/m/Y') ?? date('d/m/Y'); 

        // 5. MENGAMBIL UNIT KOMPETENSI SECARA DINAMIS
        $skemaData = $this->getSkemaRelatedData(optional($skema)->id_skema ?? null);
        
        $judul_kegiatan_db = 'Proyek Pembuatan Sistem Informasi Pendaftaran Mahasiswa Baru'; 
        
        // 6. Ambil Tanda Tangan dan Rekomendasi
        $tanda_tangan_asesor_path = optional($asesor)->tanda_tangan ?? null;
        $tanda_tangan_asesi_path = optional($asesi)->tanda_tangan ?? null;
        $rekomendasi_db = optional($sertifikasi)->rekomendasi_IA04B; 

        return view('assesmen.FRIA04_Asesi', array_merge($skemaData, [
            'asesi' => $asesi,
            'asesor' => $asesor,
            'skema' => $skema,
            'jenis_tuk_db' => $jenis_tuk_db,
            'judul_kegiatan_db' => $judul_kegiatan_db,
            'tanggal_pelaksanaan' => $tanggal_pelaksanaan,
            'sertifikasi' => $sertifikasi, 
            
            // Variabel Data DB
            'skenario_umum_db' => $skenario_umum_db,
            'hasil_umum_db' => $hasil_umum_db,
            'umpan_balik_asesi_db' => $umpan_balik_asesi_db, // Nilai sudah dari DB
            'aspekIA04BData' => $aspekIA04BData, 
            
            // Variabel Tanda Tangan & Rekomendasi
            'tanda_tangan_asesor_path' => $tanda_tangan_asesor_path,
            'tanda_tangan_asesi_path' => $tanda_tangan_asesi_path,
            'rekomendasi_db' => $rekomendasi_db,
        ]));
    }

    /**
     * Menyimpan data tanggapan dan tanda tangan dari form FR.IA.04A ASESI.
     */
    public function storeIA04AAsesi(Request $request)
    {
        $id_sertifikasi = $request->input('id_sertifikasi');
        $tanggapan_data = $request->input('tanggapan'); 
        
        if ($tanggapan_data && is_array($tanggapan_data)) {
            foreach ($tanggapan_data as $id_aspek_ia04B => $tanggapan) {
                AspekIA04B::where('id_aspek_ia04B', $id_aspek_ia04B)
                    ->update(['respon_daftar_tanggapan' => $tanggapan]);
            }
        }

        // Tanda Tangan Asesi (Simulasi - Anda perlu menambahkan logic upload/save signature di sini)
        // Contoh: $tanda_tangan_asesi_base64 = $request->input('tanda_tangan_asesi');
        // Jika Anda menggunakan Model Asesi, Anda bisa update kolom ttd di sana.
        // DataSertifikasiAsesi::where('id_data_sertifikasi_asesi', $id_sertifikasi)->update([...]);

        
        return redirect()->route('tracker')->with('success', 'Tanggapan dan Konfirmasi Instruksi Terstruktur berhasil disimpan!');
    }
}
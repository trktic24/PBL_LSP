<?php

namespace App\Http\Controllers\Asesi\asesmen;

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
use App\Models\ResponIA04A;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

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

            // FIX: Ganti nama tabel dari 'master_unit_kompetensi' menjadi 'unit_kompetensi'
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
            'mockUnits' => $units_for_table,
            'kelompok_pekerjaan' => $nama_kelompok_pekerjaan,
        ];
    }

    // ----------------------------------------------------------------------------------
    // SUNDUT PANDANG ASESOR 
    // ----------------------------------------------------------------------------------

    /**
     * Menampilkan form FR.IA.04A untuk ASESOR.
     */
    public function showIA04A($id_sertifikasi)
    {
        $sertifikasi = DataSertifikasiAsesi::with(['asesi', 'jadwal.asesor', 'jadwal.skema', 'jadwal.jenisTuk'])
            ->find($id_sertifikasi);

        if (!$sertifikasi) {
            return redirect()->back()->with('error', 'Tidak ada Data Sertifikasi ditemukan di database.');
        }

        $active_id_sertifikasi = $sertifikasi->id_data_sertifikasi_asesi;
        $asesi = $sertifikasi->asesi;
        $asesor = optional($sertifikasi->jadwal)->asesor ?? null;
        $skema = optional($sertifikasi->jadwal)->skema ?? null;
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
        $umpan_balik_asesi_db = optional($responIA04A)->umpan_balik_untuk_asesi ?? null;


        return view('asesi.assesmen.FRIA04_Asesor', array_merge($skemaData, [
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
            'umpan_balik_asesi_db' => $umpan_balik_asesi_db,

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
        $sertifikasi = DataSertifikasiAsesi::findOrFail($id_sertifikasi);

        // 1. Simpan Poin IA04A (Skenario & Hasil)
        $poin = PoinIA04A::updateOrCreate(
            ['id_data_sertifikasi_asesi' => $id_sertifikasi],
            [
                'hal_yang_disiapkan' => $request->input('skenario_umum'),
                'hal_yang_didemonstrasikan' => $request->input('hasil_umum'),
            ]
        );

        // 2. Simpan Respon IA04A (Umpan Balik)
        ResponIA04A::updateOrCreate(
            ['id_data_sertifikasi_asesi' => $id_sertifikasi],
            [
                'id_poin_ia04A' => $poin->id_poin_ia04A,
                'umpan_balik_untuk_asesi' => $request->input('umpan_balik_asesi'),
                // 'ttd_supervisor' => ... (jika ada)
            ]
        );

        // 3. Simpan Aspek IA04B (Looping)
        // Ada 2 pertanyaan hardcoded di view: q1 dan q2
        for ($q = 1; $q <= 2; $q++) {
            // Tentukan status pencapaian (karena checkbox 'pencapaian_q1_ya' atau 'pencapaian_q1_tdk')
            $status = 'Tidak Kompeten'; // Default
            if ($request->has("pencapaian_q{$q}_ya")) {
                $status = 'Kompeten';
            } elseif ($request->has("pencapaian_q{$q}_tdk")) {
                $status = 'Tidak Kompeten';
            }

            // Note: Kita butuh cara unik untuk identifikasi row, misal pakai urutan atau asumsi hapus-buat baru
            // Tapi karena updateOrCreate butuh kondisi unik, dan disini tidak ada ID unik per pertanyaan selain kontennya.
            // Strategi aman: Hapus dulu yang lama untuk sertifikasi ini, atau gunakan firstOrNew dengan counter?
            // Karena ini loop sederhana 1-2, kita bisa asumsikan urutan penyimpanan.
            // Namun, karena tabel aspek_ia04B tidak punya kolom 'nomor_soal', ini agak tricky.
            // OPSI: Kita hapus dulu data lama lalu create baru (reset), atau kita asumsikan urutan dari `get()`.
            // Untuk Sederhana & Cepat: Kita HAPUS dulu create ulang (Safe untuk 2 item kecil).
        }

        // Hapus data lama (agar tidak duplikat saat save berulang)
        AspekIA04B::where('id_data_sertifikasi_asesi', $id_sertifikasi)->delete();

        for ($q = 1; $q <= 2; $q++) {
            $status = 'Tidak';
            if ($request->has("pencapaian_q{$q}_ya")) {
                $status = 'Ya';
            }

            AspekIA04B::create([
                'id_data_sertifikasi_asesi' => $id_sertifikasi,
                'respon_lingkup_penyajian_proyek' => $request->input("lingkup_q{$q}"),
                'respon_daftar_pertanyaan' => $request->input("pertanyaan_q{$q}"),
                'respon_daftar_tanggapan' => $request->input("tanggapan_q{$q}"),
                'respon_kesesuaian_standar_kompetensi' => $request->input("kesesuaian_q{$q}"),
                'respon_pencapaian' => $status,
            ]);
        }

        // 4. Update Rekomendasi di DataSertifikasiAsesi
        $sertifikasi->update([
            'rekomendasi_IA04B' => $request->input('rekomendasi_ia04b'),
        ]);

        return redirect()->route('asesor.tracker', ['id_sertifikasi_asesi' => $id_sertifikasi])
            ->with('success', 'Formulir FR.IA.04 berhasil disimpan.');
    }

    // ----------------------------------------------------------------------------------
    // SUNDUT PANDANG ASESI
    // ----------------------------------------------------------------------------------

    /**
     * Menampilkan form FR.IA.04A untuk ASESI (Mode Baca + Input Tanggapan).
     */
    public function showIA04AAsesi()
    {
        // 1. Ambil data Sertifikasi Aktif
        $sertifikasi = null;

        if (Auth::check()) {
            $asesi = optional(Auth::user())->asesi;
            if ($asesi) {
                $sertifikasi = $asesi->dataSertifikasi()->latest()->first();
            }
        }

        // Fallback untuk mode public/jika tidak login
        if (!$sertifikasi) {
            $sertifikasi = DataSertifikasiAsesi::with(['asesi', 'jadwal.asesor', 'jadwal.skema', 'jadwal.jenisTuk'])
                ->orderBy('id_data_sertifikasi_asesi', 'asc')
                ->first();
        }

        if (!$sertifikasi) {
            return redirect()->back()->with('error', 'Tidak ada Data Sertifikasi aktif ditemukan.');
        }

        $active_id_sertifikasi = $sertifikasi->id_data_sertifikasi_asesi;

        // 2. AMBIL DATA DARI DB 
        $asesi = $sertifikasi->asesi;
        $asesor = optional(optional($sertifikasi->jadwal)->asesor);
        $skema = optional(optional($sertifikasi->jadwal)->skema);
        $jadwal = $sertifikasi->jadwal;

        // Data Form FR.IA.04A (Instruksi & Hasil Asesor)
        $poinIA04A = PoinIA04A::where('id_data_sertifikasi_asesi', $active_id_sertifikasi)->first();

        // Data Respon Asesor ke Asesi (Umpan Balik)
        $responIA04A = ResponIA04A::where('id_data_sertifikasi_asesi', $active_id_sertifikasi)->first();

        // Data Tabel Penilaian (Aspek IA.04B)
        $aspekIA04BData = AspekIA04B::where('id_data_sertifikasi_asesi', $active_id_sertifikasi)->get();

        // 3. Mengisi variabel DB dengan data (menggunakan optional() untuk safety)
        $skenario_umum_db = optional($poinIA04A)->hal_yang_disiapkan ?? "Instruksi dari Asesor belum tersedia.";
        $hasil_umum_db = optional($poinIA04A)->hal_yang_didemonstrasikan ?? "Hasil demonstrasi/output belum ditetapkan oleh Asesor.";
        $umpan_balik_asesi_db = optional($responIA04A)->umpan_balik_untuk_asesi ?? "Umpan balik dari Asesor belum tersedia.";

        // Data TUK & Tanggal
        $jenis_tuk_db = strtolower(optional(optional($jadwal)->jenisTuk)->jenis_tuk ?? 'Sewaktu');
        $tanggal_pelaksanaan = optional(optional($jadwal)->tanggal_pelaksanaan)->format('d/m/Y') ?? date('d/m/Y');

        // 4. MENGAMBIL UNIT KOMPETENSI SECARA DINAMIS
        $skemaData = $this->getSkemaRelatedData(optional($skema)->id_skema ?? null);

        // 5. Data Tanda Tangan dan Rekomendasi
        $tanda_tangan_asesor_path = optional($asesor)->tanda_tangan ?? null;
        $tanda_tangan_asesi_path = optional($asesi)->tanda_tangan ?? null;
        $rekomendasi_db = optional($sertifikasi)->rekomendasi_IA04B;

        $judul_kegiatan_db = 'Proyek Pembuatan Sistem Informasi Pendaftaran Mahasiswa Baru';

        return view('asesi.assesmen.FRIA04_Asesi', array_merge($skemaData, [
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
            'umpan_balik_asesi_db' => $umpan_balik_asesi_db,
            'aspekIA04BData' => $aspekIA04BData,

            // Variabel Tanda Tangan & Rekomendasi
            'tanda_tangan_asesor_path' => $tanda_tangan_asesor_path,
            'tanda_tangan_asesi_path' => $tanda_tangan_asesi_path,
            'rekomendasi_db' => $rekomendasi_db,
            'unitsToDisplay' => $skemaData['mockUnits'],
        ]));
    }

    /**
     * Menyimpan data tanggapan dan tanda tangan dari form FR.IA.04A ASESI.
     */
    public function storeIA04AAsesi(Request $request)
    {
        // LOGIKA PENYIMPANAN ASESI
        // (Logika ini disingkat untuk fokus pada View)
        return redirect()->route('tracker')->with('success', 'Tanggapan dan Konfirmasi Instruksi Terstruktur berhasil disimpan!');
    }
}
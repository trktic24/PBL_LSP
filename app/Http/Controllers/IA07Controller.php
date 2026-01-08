<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
// Import semua Models yang relevan
use App\Models\Asesi;
use App\Models\Asesor;
use App\Models\Skema;
use App\Models\JenisTuk;
use App\Models\Jadwal;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\DataSertifikasiAsesi;
use App\Models\Ia07;
use Illuminate\Support\Facades\DB;

class IA07Controller extends Controller
{
    /**
     * Menampilkan halaman Form FR.IA.07 (khusus Asesor, menggunakan view tunggal FR_IA_07.blade.php).
     */
    public function index($idSertifikasi)
    {
        // ----------------------------------------------------
        // 1. PENGAMBILAN DATA (MENGGUNAKAN MODEL NYATA)
        // ----------------------------------------------------

        // Ambil data berdasarkan ID Sertifikasi Asesi
        $sertifikasi = DataSertifikasiAsesi::with([
            'asesi',
            'jadwal.asesor',
            'jadwal.skema.unitKompetensi',
            'jadwal.jenisTuk'
        ])->findOrFail($idSertifikasi);

        $asesi = $sertifikasi->asesi;
        $asesor = $sertifikasi->jadwal->asesor;
        $skema = $sertifikasi->jadwal->skema;
        $jadwal = $sertifikasi->jadwal;

        $skema = null;
        if ($asesor && $asesor->skema()->exists()) {
            $skema = $asesor->skema()->first();
        } else {
            $skema = Skema::first();
        }

        // Ambil Data Jadwal (Dummy/First) untuk mencegah error di view
        $jadwal = Jadwal::with(['skema', 'asesor', 'jenisTuk'])->first();

        // Fallback jika tidak ada jadwal di DB
        if (!$jadwal) {
            $jadwal = new Jadwal();
            // Set dummy relations if needed, or rely on view's null coalescing operator if improved
            if ($skema)
                $jadwal->setRelation('skema', $skema);
            if ($asesor)
                $jadwal->setRelation('asesor', $asesor);
        }

        // Ambil data Jenis TUK untuk radio button
        $jenisTukOptions = JenisTuk::pluck('jenis_tuk', 'id_jenis_tuk');

        // Ambil Unit Kompetensi dari Skema
        $units = $skema->unitKompetensi->map(function ($unit) {
            return [
                'code' => $unit->kode_unit,
                'title' => $unit->judul_unit
            ];
        });

        // --- Handle Data Kosong (Fallbacks) ---
        if (!$asesi) {
            $asesi = (object) ['nama_lengkap' => 'Nama Asesi (DB KOSONG)'];
        }
        if (!$asesor) {
            $asesor = (object) ['nama_lengkap' => 'Nama Asesor (DB KOSONG)', 'nomor_regis' => 'MET.000.000000.2019'];
        }
        if (!$skema) {
            $skema = (object) ['nama_skema' => 'SKEMA KOSONG', 'nomor_skema' => 'N/A'];
        }

        // ----------------------------------------------------
        // 2. KEMBALIKAN KE VIEW TUNGGAL ASESOR
        // ----------------------------------------------------

        // Mengembalikan view ke file frontend/FR_IA_07.blade.php
        // Mengembalikan view ke file frontend/FR_IA_07.blade.php
        return view('frontend.FR_IA_07', compact('asesi', 'asesor', 'skema', 'units', 'jenisTukOptions', 'jadwal', 'sertifikasi'));
    }

    /**
     * Menyimpan data dari Form FR.IA.07.
     */
    public function store(Request $request)
    {
        // --- LOGIKA PENYIMPANAN DATA DI SINI ---

        // 1. Validasi
        //   - Pastikan ada input radio TUK
        //   - Pastikan tanggal terisi
        $request->validate([
            'id_jenis_tuk' => 'required',
            'tanggal_pelaksanaan' => 'required|date',
            'umpan_balik_asesi' => 'nullable|string',
            'id_data_sertifikasi_asesi' => 'required|exists:data_sertifikasi_asesi,id_data_sertifikasi_asesi',
        ]);

        // 2. Tentukan DataSertifikasiAsesi
        $idSertifikasi = $request->input('id_data_sertifikasi_asesi');
        $sertifikasi = DataSertifikasiAsesi::findOrFail($idSertifikasi);
        if (!$sertifikasi) {
            return redirect()->back()->with('error', 'Data Sertifikasi tidak ditemukan (DB Kosong).');
        }
        $idSertifikasi = $sertifikasi->id_data_sertifikasi_asesi;

        DB::beginTransaction();
        try {
            // 3. Simpan Jawaban
            //    Loop semua input yang polanya jawaban_{code}_q{num} dan keputusan_{code}_q{num}
            //    Kita ambil semua data request
            $allData = $request->all();

            // Regex untuk menangkap jawaban_CODE_qX
            $unitCodes = [];

            // Loop data untuk mencari pattern jawaban
            foreach ($allData as $key => $value) {
                if (preg_match('/^jawaban_(.+)_q(\d+)$/', $key, $matches)) {
                    $unitCode = $matches[1];
                    $questionNum = $matches[2];

                    $pertanyaan = "Pertanyaan No $questionNum Unit $unitCode"; // Atau ambil real text dari DB jika ada
                    $jawabanKey = $key;
                    $keputusanKey = "keputusan_{$unitCode}_q{$questionNum}";

                    $jawabanDiharapkan = "Lihat Kunci Jawaban"; // Placeholder

                    $keputusanVal = $request->input($keputusanKey); // K atau BK
                    $isKompeten = ($keputusanVal === 'K');

                    // Simpan ke tabel ia07
                    // Kita anggap satu record per pertanyaan
                    // Cari record lama atau buat baru
                    Ia07::updateOrCreate(
                        [
                            'id_data_sertifikasi_asesi' => $idSertifikasi,
                            'pertanyaan' => $pertanyaan, // Identifier sederhana untuk soal
                        ],
                        [
                            'jawaban_asesi' => $value, // Ringkasan jawaban asesi
                            'jawaban_diharapkan' => $jawabanDiharapkan,
                            'pencapaian' => $isKompeten ? 1 : 0,
                        ]
                    );

                    $unitCodes[$unitCode] = true;
                }
            }

            // 4. Update Umpan Balik (Jika ada kolom di tabel sertifikasi/lainnya, atau simpan di tempat lain)
            //    Model Ia07 sepertinya per-pertanyaan. Jika umpan balik global, mungkin masuk ke log activity atau field lain.
            //    Sesuai struktur yang ada, kita biarkan dulu atau simpan di salah satu record jika terpaksa.
            //    Tapi controller IA05 menyimpan umpan balik di tabel LembarJawab, di sini tabel Ia07 juga punya struktur mirip?
            //    Cek Ia07 model: fillable ['id_data_sertifikasi_asesi', 'pertanyaan', 'jawaban_asesi', 'jawaban_diharapkan', 'pencapaian']
            //    Tidak ada kolom umpan_balik khusus di Ia07.

            // 5. Update Jadwal / Jenis TUK (Opsional, karena jadwal biasanya master data)
            //    Tapi kita bisa update id_jenis_tuk di sertifikasi/jadwal jika perlu.

            DB::commit();

            return redirect()->route('ia07.cetak', $idSertifikasi)->with('success', 'Penilaian FR.IA.07 berhasil disimpan.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal menyimpan penilaian: ' . $e->getMessage());
        }
    }

    public function cetakPDF($idSertifikasi)
    {
        // 1. Ambil Data Sertifikasi Lengkap
        $sertifikasi = DataSertifikasiAsesi::with([
            'asesi',
            'jadwal.masterTuk',
            'jadwal.skema.asesor',
            'jadwal.skema.unitKompetensi' // Ambil unit untuk ditampilkan di tabel awal
        ])->findOrFail($idSertifikasi);

        // 2. Ambil Daftar Pertanyaan & Jawaban (IA07)
        $daftar_pertanyaan = Ia07::where('id_data_sertifikasi_asesi', $idSertifikasi)->get();

        // 3. Ambil Unit Kompetensi (Fallback ke collection kosong jika null)
        $unitKompetensi = $sertifikasi->jadwal->skema->unitKompetensi ?? collect();

        // 4. Render PDF
        $pdf = Pdf::loadView('pdf.ia_07', [
            'sertifikasi' => $sertifikasi,
            'daftar_pertanyaan' => $daftar_pertanyaan,
            'unitKompetensi' => $unitKompetensi
        ]);

        $pdf->setPaper('A4', 'portrait');

        return $pdf->stream('FR_IA_07_' . $sertifikasi->asesi->nama_lengkap . '.pdf');
    }
}
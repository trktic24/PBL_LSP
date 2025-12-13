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

class IA07Controller extends Controller
{
    /**
     * Menampilkan halaman Form FR.IA.07 (khusus Asesor, menggunakan view tunggal FR_IA_07.blade.php).
     */
    public function index()
    {
        // ----------------------------------------------------
        // 1. PENGAMBILAN DATA (MENGGUNAKAN MODEL NYATA)
        // ----------------------------------------------------

        // Ambil data pertama sebagai contoh (Anda mungkin perlu mengubah ini 
        // untuk mengambil data berdasarkan ID Asesi/Asesor yang sebenarnya)
        $asesi = Asesi::first();
        $asesor = Asesor::first();

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
            // But view calls $jadwal->skema directly in some places without ?->
            // So let's attach the fetched skema/asesor to this dummy jadwal if possible,
            // or just ensure the view handles it.
            // For now, let's just make sure $jadwal->skema is accessible if we have $skema.
            $jadwal->setRelation('skema', $skema);
            $jadwal->setRelation('asesor', $asesor);
        }

        // Ambil data Jenis TUK untuk radio button
        $jenisTukOptions = JenisTuk::pluck('jenis_tuk', 'id_jenis_tuk');

        // Data Dummy Unit Kompetensi (Harap ganti dengan data dinamis dari DB Anda)
        $units = [
            ['code' => 'J.620100.004.02', 'title' => 'Menggunakan Struktur Data'],
            ['code' => 'J.620100.005.02', 'title' => 'Mengimplementasikan User Interface'],
            ['code' => 'J.620100.009.01', 'title' => 'Melakukan Instalasi Software Tools'],
            ['code' => 'J.620100.017.02', 'title' => 'Mengimplementasikan Pemrograman Terstruktur'],
        ];

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
        return view('frontend.FR_IA_07', compact('asesi', 'asesor', 'skema', 'units', 'jenisTukOptions', 'jadwal'));
    }

    /**
     * Menyimpan data dari Form FR.IA.07.
     */
    public function store(Request $request)
    {
        // --- LOGIKA PENYIMPANAN DATA DIMULAI DI SINI ---

        // Contoh: Ambil semua data yang disubmit
        $data = $request->all();

        // Di sini Anda akan memasukkan logika untuk:
        // 1. Validasi data ($request->validate([...]))
        // 2. Memproses dan menyimpan data ke tabel database yang relevan (misalnya tabel 'IA07Assessment')
        // 3. Mengambil ringkasan jawaban dan keputusan K/BK untuk setiap unit/pertanyaan.

        // Misalnya: menyimpan ke database, lalu redirect
        // Assessment::create($data); 

        // Untuk saat ini, kita hanya menampilkan data untuk verifikasi (dd)
        return dd($data);
    }

    public function cetakPDF($idSertifikasi)
    {
        // 1. Ambil Data Sertifikasi Lengkap
        $sertifikasi = DataSertifikasiAsesi::with([
            'asesi',
            'jadwal.tuk',
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
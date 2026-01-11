<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Asesi;
use App\Models\Asesor;
use App\Models\Skema;
use App\Models\JenisTUK;
use App\Models\Jadwal;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\DataSertifikasiAsesi;
use App\Models\Ia07;
use Illuminate\Support\Facades\DB;

class IA07Controller extends Controller
{
    public function index($idSertifikasi)
    {
        // 1. PENGAMBILAN DATA
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

        $jenisTukOptions = JenisTUK::pluck('jenis_tuk', 'id_jenis_tuk');

        $units = $skema->unitKompetensi->map(function ($unit) {
            return [
                'code' => $unit->kode_unit,
                'title' => $unit->judul_unit
            ];
        });
        
        // Ambil data jawaban
        $dataIa07 = Ia07::where('id_data_sertifikasi_asesi', $idSertifikasi)->get();

        // Fallbacks
        if (!$asesi) $asesi = (object) ['nama_lengkap' => 'Nama Asesi (DB KOSONG)'];
        if (!$asesor) $asesor = (object) ['nama_lengkap' => 'Nama Asesor (DB KOSONG)', 'nomor_regis' => 'MET.000.000000.2019'];
        if (!$skema) $skema = (object) ['nama_skema' => 'SKEMA KOSONG', 'nomor_skema' => 'N/A'];

        // 2. KEMBALIKAN KE VIEW
        return view('frontend.FR_IA_07', compact(
            'asesi', 'asesor', 'skema', 'units', 'jenisTukOptions', 'jadwal', 'sertifikasi', 
            'dataIa07'
        ));
    } // <--- INI YANG TADI HILANG (Kurung Kurawal Penutup Function Index)

    /**
     * Menyimpan data dari Form FR.IA.07.
     */
    public function store(Request $request)
    {
        // --- PERHATIAN: ---
        // Saya menyarankan menggunakan logika UPDATE BY ID (yang kita bahas sebelumnya)
        // agar teks soal tidak rusak/duplikat. 
        // Tapi ini adalah kode yang Anda kirim (saya hanya perbaiki syntax).

        $request->validate([
            'id_data_sertifikasi_asesi' => 'required',
        ]);

        $idSertifikasi = $request->input('id_data_sertifikasi_asesi');

        DB::beginTransaction();
        try {
            $allData = $request->all();

            // Loop input
            foreach ($allData as $key => $value) {
                // UPDATE AMAN MENGGUNAKAN ID (Sesuai View yang baru)
                if (preg_match('/^id_ia07_(.+)_q(\d+)$/', $key, $matches)) {
                    $unitCode = $matches[1];
                    $questionNum = $matches[2];
                    $idIa07 = $value; // Ini ID Primary Key

                    // Cari pasangannya (Keputusan K/BK)
                    $keputusanKey = "keputusan_{$unitCode}_q{$questionNum}";
                    $keputusanVal = $request->input($keputusanKey);

                    if ($keputusanVal) {
                        $isKompeten = ($keputusanVal === 'K');
                        // Update HANYA status kompeten
                        Ia07::where('id_ia07', $idIa07)->update([
                            'pencapaian' => $isKompeten ? 1 : 0
                        ]);
                    }
                }
                
                // BACKUP: Logika lama (jika view belum pakai ID hidden)
                // Hati-hati kode ini bisa menduplikasi soal jika teks beda sedikit
                elseif (preg_match('/^jawaban_(.+)_q(\d+)$/', $key, $matches)) {
                     // Biarkan kosong jika Anda sudah pakai View Asesor terbaru (update by ID)
                     // Agar tidak bentrok.
                }
            }

            DB::commit();
            return redirect()->back()->with('success', 'Penilaian FR.IA.07 berhasil disimpan.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal: ' . $e->getMessage());
        }
    }

    public function cetakPDF($idSertifikasi)
    {
        $sertifikasi = DataSertifikasiAsesi::with([
            'asesi', 'jadwal.masterTuk', 'jadwal.skema.asesor', 'jadwal.skema.unitKompetensi'
        ])->findOrFail($idSertifikasi);

        $daftar_pertanyaan = Ia07::where('id_data_sertifikasi_asesi', $idSertifikasi)->get();
        $unitKompetensi = $sertifikasi->jadwal->skema->unitKompetensi ?? collect();

        $pdf = Pdf::loadView('pdf.ia_07', [
            'sertifikasi' => $sertifikasi,
            'daftar_pertanyaan' => $daftar_pertanyaan,
            'unitKompetensi' => $unitKompetensi
        ]);

        $pdf->setPaper('A4', 'portrait');
        return $pdf->stream('FR_IA_07_' . $sertifikasi->asesi->nama_lengkap . '.pdf');
    }

    public function adminShow($id_skema)
    {
        $skema = \App\Models\Skema::findOrFail($id_skema);

        $query = \App\Models\DataSertifikasiAsesi::with([
            'asesi.dataPekerjaan', 'jadwal.skema', 'jadwal.masterTuk', 'jadwal.asesor'
        ])->whereHas('jadwal', function($q) use ($id_skema) {
            $q->where('id_skema', $id_skema);
        });

        if (request('search')) {
            $search = request('search');
            $query->whereHas('asesi', function($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%");
            });
        }

        $pendaftar = $query->paginate(request('per_page', 10))->withQueryString();

        // Setup dummy objects for view compatibility
        $user = auth()->user();
        $asesor = new \App\Models\Asesor();
        $asesor->id_asesor = 0;
        $asesor->nama_lengkap = $user ? $user->name : 'Administrator';
        $asesor->pas_foto = $user ? $user->profile_photo_path : null;
        $asesor->status_verifikasi = 'approved';
        $asesor->setRelation('skemas', collect());
        $asesor->setRelation('jadwals', collect());
        $asesor->setRelation('skema', null);

        $jadwal = new \App\Models\Jadwal([
            'tanggal_pelaksanaan' => now(),
            'waktu_mulai' => '08:00',
        ]);
        $jadwal->setRelation('skema', $skema);
        $jadwal->setRelation('masterTuk', new \App\Models\MasterTUK(['nama_lokasi' => 'Semua TUK (Filter Skema)']));

        return view('Admin.master.skema.daftar_asesi', [
            'pendaftar' => $pendaftar,
            'asesor' => $asesor,
            'jadwal' => $jadwal,
            'isMasterView' => true,
            'sortColumn' => request('sort', 'nama_lengkap'),
            'sortDirection' => request('direction', 'asc'),
            'perPage' => request('per_page', 10),
            'targetRoute' => 'ia07.asesor', 
            'buttonLabel' => 'FR.IA.07',
        ]);
    }
}
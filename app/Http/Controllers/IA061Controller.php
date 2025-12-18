<?php

namespace App\Http\Controllers;

use App\Models\SoalIa06;
use App\Models\JawabanIa06;
use App\Models\UmpanBalikIa06;
use App\Models\DataSertifikasiAsesi;
use App\Models\Skema;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class Ia06Controller extends Controller
{
    // =======================================================================
    // BAGIAN 1: ADMIN (MANAJEMEN BANK SOAL)
    // =======================================================================

    /**
     * Menampilkan Halaman Bank Soal (Admin)
     */
    public function adminIndex(Request $request)
    {
        // $this->authorizeRole(1); // Handled by Middleware

        $skemas = Skema::all();
        $selectedSkema = $request->query('skema_id');

        $soals = [];
        if ($selectedSkema) {
            $soals = SoalIa06::where('id_skema', $selectedSkema)->get();
        }

        // Pastikan Anda punya view: resources/views/admin/bank_soal/ia06/index.blade.php
        return view('frontend.IA_06.FR_IA_06_admin', compact('skemas', 'soals', 'selectedSkema'));
    }

    /**
     * Menyimpan Soal Baru (Admin)
     */
    public function adminStoreSoal(Request $request)
    {
        // $this->authorizeRole(1);

        $request->validate([
            'id_skema' => 'required|exists:skema,id_skema',
            'soal_ia06' => 'required|string',
            'kunci_jawaban_ia06' => 'required|string',
        ]);

        SoalIa06::create($request->all());

        return back()->with('success', 'Soal berhasil ditambahkan.');
    }

    /**
     * Update Soal (Admin)
     */
    public function adminUpdateSoal(Request $request, $id)
    {
        // $this->authorizeRole(1);

        $soal = SoalIa06::findOrFail($id);
        $soal->update($request->only(['soal_ia06', 'kunci_jawaban_ia06']));

        return back()->with('success', 'Soal berhasil diperbarui.');
    }

    /**
     * Hapus Soal (Admin)
     */
    public function adminDestroySoal($id)
    {
        // $this->authorizeRole(1);
        SoalIa06::destroy($id);
        return back()->with('success', 'Soal dihapus.');
    }


    // =======================================================================
    // BAGIAN 2: ASESI (MENGERJAKAN SOAL)
    // =======================================================================

    /**
     * Menampilkan Lembar Soal (Asesi)
     */
    public function asesiShow($idSertifikasi)
    {
        $this->authorizeRole(2); // Cek apakah Asesi
        $user = Auth::user();

        // 1. Validasi Kepemilikan Data
        $sertifikasi = DataSertifikasiAsesi::with(['jadwal.skema', 'asesi'])->findOrFail($idSertifikasi);

        // Asumsi: Relasi user ke asesi menggunakan 'user_id' di tabel asesi
        if ($sertifikasi->asesi->user_id !== $user->id_user) {
            abort(403, 'Akses Ditolak. Ini bukan lembar ujian Anda.');
        }

        // 2. Generate Lembar Jawab (Jika belum ada)
        $this->generateLembarJawab($sertifikasi);

        // 3. Ambil Jawaban
        $daftar_soal = JawabanIa06::with('soal')
            ->where('id_data_sertifikasi_asesi', $idSertifikasi)
            ->get();

        $role = 2; // Kita set manual angka 2 karena ini function khusus Asesi

        // Tentukan URL tujuan submit form
        $formAction = route('asesi.ia06.update', $idSertifikasi);

        // Kita juga butuh variabel umpanBalik agar view tidak error (meski asesi cuma baca)
        $umpanBalik = UmpanBalikIa06::where('id_data_sertifikasi_asesi', $idSertifikasi)->first();

        // Panggil View UNIFIED (Satu untuk semua)
        return view('frontend.IA_06.FR_IA_06', compact('sertifikasi', 'daftar_soal', 'role', 'formAction', 'umpanBalik'));
    }

    /**
     * Menyimpan Jawaban (Asesi)
     */
    public function asesiStoreJawaban(Request $request, $idSertifikasi)
    {
        $this->authorizeRole(2);

        $request->validate([
            'jawaban' => 'required|array',
            'jawaban.*' => 'nullable|string',
        ]);

        DB::transaction(function () use ($request, $idSertifikasi) {
            foreach ($request->jawaban as $idJawaban => $teks) {
                JawabanIa06::where('id_jawaban_ia06', $idJawaban)
                    ->where('id_data_sertifikasi_asesi', $idSertifikasi) // Security check extra
                    ->update(['jawaban_asesi' => $teks]);
            }
        });

        return back()->with('success', 'Jawaban berhasil disimpan.');
    }


    // =======================================================================
    // BAGIAN 3: ASESOR (PENILAIAN)
    // =======================================================================

    /**
     * Menampilkan Form Penilaian (Asesor)
     */
    public function asesorShow($idSertifikasi)
    {
        $this->authorizeRole(3); // Cek apakah Asesor

        $sertifikasi = DataSertifikasiAsesi::with(['jadwal.skema', 'asesi'])->findOrFail($idSertifikasi);

        $daftar_soal = JawabanIa06::with('soal')
            ->where('id_data_sertifikasi_asesi', $idSertifikasi)
            ->get();

        $umpanBalik = UmpanBalikIa06::where('id_data_sertifikasi_asesi', $idSertifikasi)->first();

        $role = 3; // Kita set manual angka 3 karena ini function khusus Asesor

        // Tentukan URL tujuan submit form (Ke Route Update Asesor)
        $formAction = route('asesor.ia06.update', $idSertifikasi);

        // Panggil View UNIFIED yang SAMA dengan Asesi
        return view('frontend.IA_06.FR_IA_06', compact('sertifikasi', 'daftar_soal', 'umpanBalik', 'role', 'formAction'));
    }


    /**
     * Menyimpan Nilai & Feedback (Asesor)
     */
    public function asesorStorePenilaian(Request $request, $idSertifikasi)
    {
        $this->authorizeRole(3);

        $request->validate([
            'penilaian' => 'required|array',
            'penilaian.*' => 'required|boolean', // 1=K, 0=BK
            'umpan_balik' => 'required|string',
        ]);

        DB::transaction(function () use ($request, $idSertifikasi) {
            // 1. Simpan Nilai Per Soal
            foreach ($request->penilaian as $idJawaban => $nilai) {
                JawabanIa06::where('id_jawaban_ia06', $idJawaban)
                    ->update(['pencapaian' => $nilai]);
            }

            // 2. Simpan Umpan Balik
            UmpanBalikIa06::updateOrCreate(
                ['id_data_sertifikasi_asesi' => $idSertifikasi],
                ['umpan_balik' => $request->umpan_balik]
            );
        });

        return back()->with('success', 'Penilaian berhasil disimpan.');
    }

    public function cetakPDF($idSertifikasi)
    {
        // 1. Ambil Data Sertifikasi
        $sertifikasi = DataSertifikasiAsesi::with([
            'jadwal.skema',
            'jadwal.asesor',
            'jadwal.masterTuk',
            'asesi'
        ])->findOrFail($idSertifikasi);

        // 2. Ambil Jawaban & Soal
        $daftar_soal = JawabanIa06::with('soal')
            ->where('id_data_sertifikasi_asesi', $idSertifikasi)
            ->get();

        // 3. Ambil Umpan Balik
        $umpanBalik = UmpanBalikIa06::where('id_data_sertifikasi_asesi', $idSertifikasi)->first();

        // 4. Render PDF
        $pdf = Pdf::loadView('pdf.ia_06', [
            'sertifikasi' => $sertifikasi,
            'daftar_soal' => $daftar_soal,
            'umpanBalik' => $umpanBalik
        ]);

        $pdf->setPaper('A4', 'portrait');

        return $pdf->stream('FR_IA_06_' . $sertifikasi->asesi->nama_lengkap . '.pdf');
    }


    // =======================================================================
    // HELPER FUNCTIONS (PRIVATE)
    // =======================================================================

    /**
     * Fungsi Helper untuk proteksi Role ID manual (Backup Middleware)
     */
    private function authorizeRole($roleId)
    {
        if (Auth::user()->role_id != $roleId) {
            abort(403, 'Unauthorized Action.');
        }
    }

    /**
     * Generate Lembar Jawab Kosong Otomatis
     */
    private function generateLembarJawab($sertifikasi)
    {
        // Cek apakah jawaban sudah ada?
        $exists = JawabanIa06::where('id_data_sertifikasi_asesi', $sertifikasi->id_data_sertifikasi_asesi)->exists();

        if (!$exists) {
            // Ambil soal berdasarkan skema jadwal
            $soals = SoalIa06::where('id_skema', $sertifikasi->jadwal->id_skema)->get();

            if ($soals->isEmpty())
                return; // Tidak ada soal, skip

            $dataInsert = [];
            foreach ($soals as $soal) {
                $dataInsert[] = [
                    'id_soal_ia06' => $soal->id_soal_ia06,
                    'id_data_sertifikasi_asesi' => $sertifikasi->id_data_sertifikasi_asesi,
                    'jawaban_asesi' => null,
                    'pencapaian' => null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
            JawabanIa06::insert($dataInsert);
        }
    }
}
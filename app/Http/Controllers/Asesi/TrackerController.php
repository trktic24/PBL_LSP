<?php

namespace App\Http\Controllers\Asesi;

use Carbon\Carbon;
use App\Models\Jadwal;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\DataSertifikasiAsesi;
use Illuminate\Support\Facades\Auth;

class TrackerController extends Controller
{
    /**
     * Menampilkan halaman tracker untuk user yang sedang login.
     * Mendukung parameter opsional $jadwal_id untuk redirect spesifik.
     */
    public function index($jadwal_id = null)
    {
        $user = Auth::user();
        $asesi = $user->asesi;
        $sertifikasi = null;

        // Default Flags View
        $showIA02 = false; $showIA05 = false; $showIA06 = false; 
        $showIA07 = false; $showIA09 = false; 

        // Status Flags
        $unlockAPL02 = false;
        $unlockAK01 = false;
        $unlockAsesmen = false; // Start Ujian
        $isWaktuHabis = false;  // End Ujian -> Trigger AK.03
        $unlockAK03 = false;    // Umpan Balik
        $unlockHasil = false;   // Trigger Lihat Nilai
        $unlockAK04 = false;    // Banding Asesmen
        
        $isSudahHadir = false;
        $statusAPL01 = 'menunggu';
        $statusAPL02 = 'menunggu';
        $pesanWaktu = null;
        $pesanStatus = null;

        if ($asesi) {
            $query = $asesi->dataSertifikasi()->with(['jadwal.skema.listForm', 'jadwal.asesor', 'daftarHadir']);
            $sertifikasi = $jadwal_id ? $query->where('id_jadwal', $jadwal_id)->first() : $query->latest()->first();

            if ($sertifikasi) {
                // --- 1. STATUS AWAL ---
                $statusAPL01 = $sertifikasi->rekomendasi_apl01;
                $statusAPL02 = $sertifikasi->rekomendasi_apl02;
                if ($statusAPL01 == 'diterima') $unlockAPL02 = true;
                if ($statusAPL02 == 'diterima') $unlockAK01 = true;
                
                $isSudahHadir = $sertifikasi->is_sudah_hadir;
                $isAsesorVerified = $sertifikasi->rekomendasi_apl02 == 'diterima';

                // --- 2. SETTING FORM ---
                $setting = $sertifikasi->jadwal->skema->listForm ?? null;
                if ($setting) {
                    $showIA02 = $setting->fr_ia_02 == 1;
                    $showIA05 = $setting->fr_ia_05 == 1;
                    $showIA06 = $setting->fr_ia_06 == 1;
                    $showIA07 = $setting->fr_ia_07 == 1;
                    $showIA09 = $setting->fr_ia_09 == 1;
                }

                // --- 3. LOGIC WAKTU (MULAI & SELESAI) ---
                $jadwalDB = $sertifikasi->jadwal;
                if ($jadwalDB) {
                    $tgl = Carbon::parse($jadwalDB->tanggal_pelaksanaan)->format('Y-m-d');
                    
                    // Waktu Mulai
                    $jamMulai = Carbon::parse($jadwalDB->waktu_mulai)->format('H:i:s');
                    $waktuMulai = Carbon::parse($tgl . ' ' . $jamMulai);

                    // Waktu Selesai (Pastikan kolom waktu_selesai ada di tabel jadwal)
                    $jamSelesai = Carbon::parse($jadwalDB->waktu_selesai)->format('H:i:s');
                    $waktuSelesai = Carbon::parse($tgl . ' ' . $jamSelesai);

                    $now = Carbon::now();

                    // Cek Mulai
                    if ($now->greaterThanOrEqualTo($waktuMulai)) {
                        if (!$isAsesorVerified) {
                            $pesanStatus = 'Menunggu Verifikasi Asesor (APL-02).';
                        } elseif (!$isSudahHadir) {
                            $pesanStatus = 'Anda belum mengisi Daftar Hadir.';
                        } else {
                            $unlockAsesmen = true; // Ujian Buka
                        }
                    } else {
                        $pesanStatus = 'Asesmen belum dimulai.';
                        $pesanWaktu = 'Mulai: ' . $waktuMulai->format('H:i') . ' WIB';
                    }

                    // Cek Selesai (Trigger AK.03 / Umpan Balik)
                    if ($now->greaterThanOrEqualTo($waktuSelesai)) {
                        $isWaktuHabis = true;
                        $unlockAK03 = true; // Umpan Balik Kebuka
                    }
                }

                // --- 4. LOGIC HASIL & BANDING (AK.04) ---
                // Cek apakah Asesor sudah input rekomendasi di AK.02
                if (!is_null($sertifikasi->rekomendasi_hasil_asesmen_AK02)) {
                    $unlockHasil = true;
                    $unlockAK04 = true; // Banding Kebuka bareng Hasil
                }
            }
        }

        return view('asesi.tracker', compact(
            'sertifikasi',
            'showIA02', 'showIA05', 'showIA06', 'showIA07', 'showIA09',
            'unlockAPL02', 'unlockAK01', 'unlockAsesmen', 'unlockAK03', 'unlockAK04',
            'statusAPL01', 'statusAPL02', 'isWaktuHabis',
            'pesanWaktu', 'isSudahHadir', 'unlockHasil', 'pesanStatus'
        ));
    }

    /**
     * ========================================================
     * FUNGSI API: Untuk mendaftarkan user ke jadwal
     * ========================================================
     */
    public function daftarJadwal(Request $request)
    {
        $user = Auth::user();

        // 1. Validasi input
        $request->validate([
            'id_jadwal' => 'required|integer|exists:jadwal,id_jadwal',
        ]);

        $id_jadwal = $request->id_jadwal;
        $asesi = $user->asesi;

        // 2. Pastikan asesi ada
        if (!$asesi) {
            $message = 'Data asesi Anda tidak ditemukan. Harap lengkapi profil Anda.';
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => $message], 404);
            }
            return redirect()->route('asesi.profile.edit')->with('error', $message);
        }

        // 3. Cek apakah user sudah mendaftar di jadwal ini?
        $existing = DataSertifikasiAsesi::where('id_asesi', $asesi->id_asesi)->where('id_jadwal', $id_jadwal)->first();

        // Helper untuk redirect response
        $redirectToTracker = function($jadwalId, $msg, $isError = false) use ($request) {
            if ($request->expectsJson()) {
                 return response()->json([
                    'success' => !$isError,
                    'redirect_url' => route('asesi.tracker', ['jadwal_id' => $jadwalId]),
                    'message' => $msg,
                ], $isError ? 409 : 200);
            }
            return redirect()->route('asesi.tracker', ['jadwal_id' => $jadwalId])->with($isError ? 'info' : 'success', $msg);
        };

        if ($existing) {
            return $redirectToTracker($existing->id_jadwal, 'Anda sudah memiliki pendaftaran aktif. Mengarahkan ke data Anda...', true);
        }

        try {
            // 4. Buat pendaftaran baru
            $newSertifikasi = DataSertifikasiAsesi::create([
                'id_asesi' => $asesi->id_asesi,
                'id_jadwal' => $id_jadwal,
                'tujuan_asesmen' => 'Sertifikasi', // Default
                'tanggal_daftar' => Carbon::now(),
            ]);

            return $redirectToTracker($newSertifikasi->id_jadwal, 'Berhasil mendaftar! Selamat datang di halaman Tracker.');

        } catch (\Exception $e) {
            $msg = 'Terjadi kesalahan server saat mendaftar: ' . $e->getMessage();
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => $msg], 500);
            }
            return back()->with('error', $msg);
        }
    }

    public function pendaftaranSelesai($id_sertifikasi)
    {
        $user = Auth::user();
        
        // Ambil sertifikasi spesifik berdasarkan ID & User yang login (biar aman)
        $sertifikasi = DataSertifikasiAsesi::with(['asesi', 'jadwal.skema'])
            ->where('id_asesi', $user->asesi->id_asesi) // Pastikan punya dia
            ->findOrFail($id_sertifikasi);

        return view('asesi.tunggu_or_berhasil.berhasil', [
            'sertifikasi' => $sertifikasi,
            'asesi'       => $sertifikasi->asesi
        ]);
    }

    /**
     * Menampilkan halaman BERHASIL untuk Pra-Asesmen (APL-02)
     */
    public function praAsesmenSelesai($id_sertifikasi)
    {
        $user = Auth::user();

        // Ambil sertifikasi spesifik
        $sertifikasi = DataSertifikasiAsesi::with(['asesi', 'jadwal.skema'])
            ->where('id_asesi', $user->asesi->id_asesi)
            ->findOrFail($id_sertifikasi);

        return view('asesi.tunggu_or_berhasil.berhasil', [
            'sertifikasi' => $sertifikasi,
            'asesi'       => $sertifikasi->asesi
        ]);
    }
}

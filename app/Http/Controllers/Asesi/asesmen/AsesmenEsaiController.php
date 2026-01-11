<?php

namespace App\Http\Controllers\Asesi\asesmen;

use Carbon\Carbon;
use App\Models\SoalIA06;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;

// --- MODEL ---
use App\Models\DataSertifikasiAsesi;
use Illuminate\Support\Facades\Auth;
use App\Models\JawabanIa06; // Model untuk Tabel Lembar Jawab/Jawaban User

class AsesmenEsaiController extends Controller
{
    /**
     * Menampilkan halaman frontend asesmen essai (Blade).
     */
    public function indexEsai($idSertifikasi)
    {
        $user = Auth::user();
        $isAdmin = $user->hasRole('admin') || $user->hasRole('superadmin');

        $sertifikasi = DataSertifikasiAsesi::with([
            'asesi',
            'jadwal.skema',
        ])->findOrFail($idSertifikasi);

        // Validasi akses user
        if (!$isAdmin && $sertifikasi->id_asesi !== $user->asesi->id_asesi) {
            abort(403, 'Unauthorized action.');
        }

        $asesi = $isAdmin ? $sertifikasi->asesi : $user->asesi;

        // --- LOGIC HITUNG MUNDUR WAKTU (SUDAH BENAR) ---
        $jadwal = $sertifikasi->jadwal;
        $tanggal = Carbon::parse($jadwal->tanggal_pelaksanaan)->format('Y-m-d');
        $jamSaja = Carbon::parse($jadwal->waktu_selesai)->format('H:i:s');
        
        $waktuSelesai = Carbon::parse($tanggal . ' ' . $jamSaja, 'Asia/Jakarta');
        $waktuSekarang = Carbon::now('Asia/Jakarta');

        $sisaWaktuDetik = $waktuSekarang->greaterThanOrEqualTo($waktuSelesai) 
            ? 0 
            : $waktuSekarang->diffInSeconds($waktuSelesai);

        return view('asesi.assesmen.asesmen_esai', [ 
            'asesi' => $user->asesi,
            'sertifikasi' => $sertifikasi,
            'sisa_waktu' => (int) $sisaWaktuDetik 
        ]);
    }

    /**
     * [GET API] Mengambil daftar soal essai.
     * LOGIC BARU: Auto-Generate by Skema jika lembar jawab kosong.
     */
    public function getQuestions($idSertifikasi)
    {
        try {
            // 1. Ambil Info Sertifikasi & Jadwal Peserta
            $sertifikasi = DataSertifikasiAsesi::with('jadwal.skema')->findOrFail($idSertifikasi);

            if (!$sertifikasi->jadwal) {
                return response()->json(['success' => false, 'message' => 'Jadwal tidak ditemukan.'], 404);
            }

            $idSkema = $sertifikasi->jadwal->id_skema;
            $idJadwal = $sertifikasi->id_jadwal;

            // 2. AMBIL MASTER SOAL (Disimpan ke variabel $bankSoal)
            $bankSoal = SoalIA06::where('id_skema', $idSkema)
                    ->where('id_jadwal', $idJadwal)
                    ->get();

            // Cek kalau kosong
            if ($bankSoal->isEmpty()) {
                return response()->json([
                    'success' => true, 
                    'data' => [], 
                    'message' => 'Belum ada soal untuk skema/jadwal ini.'
                ]);
            }

            // 3. AUTO-SYNC (Cek mana soal yang belum masuk ke lembar jawab user)
            // Ambil ID Soal yang SUDAH ada di tabel jawaban user
            $existingSoalIds = JawabanIa06::where('id_data_sertifikasi_asesi', $idSertifikasi)
                ->pluck('id_soal_ia06') 
                ->toArray();

            // Filter $bankSoal: Ambil cuma yang ID-nya BELUM ada di $existingSoalIds
            $soalBaru = $bankSoal->whereNotIn('id_soal_ia06', $existingSoalIds);

            // 4. INSERT SOAL BARU (Jika ada)
            if ($soalBaru->isNotEmpty()) {
                $dataInsert = [];
                $now = now();

                foreach ($soalBaru as $soal) {
                    $dataInsert[] = [
                        'id_data_sertifikasi_asesi' => $idSertifikasi,
                        'id_soal_ia06' => $soal->id_soal_ia06,
                        'jawaban_asesi' => null,
                        'pencapaian' => null,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ];
                }
                // Insert Sekaligus
                JawabanIa06::insert($dataInsert);
            }

            // 5. AMBIL DATA FINAL (Ambil dari tabel jawaban yang sudah lengkap)
            $dataFinal = JawabanIa06::with(['soal'])
                ->where('id_data_sertifikasi_asesi', $idSertifikasi)
                ->get();

            // 6. MAPPING OUTPUT
            $formattedData = $dataFinal->map(function ($item) {
                if (!$item->soal) return null;

                return [
                    'id_lembar_jawab' => $item->id_jawaban_ia06,
                    'id_soal_master' => $item->soal->id_soal_ia06,
                    'pertanyaan' => $item->soal->soal_ia06,
                    'jawaban_tersimpan' => $item->jawaban_asesi,
                ];
            })->filter()->values();

            return response()->json(['success' => true, 'data' => $formattedData]);

        } catch (\Exception $e) {
            Log::error('Error Get Questions IA06: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Server Error: ' . $e->getMessage()], 500);
        }
    }

    /**
     * [POST API] Menyimpan jawaban essai asesi.
     */
    public function submitAnswers(Request $request, $idSertifikasi)
    {
        $request->validate([
            'jawaban' => 'required|array',
            // Validasi keberadaan ID di tabel jawaban_ia06
            'jawaban.*.id_lembar_jawab' => 'required|exists:jawaban_ia06,id_jawaban_ia06', 
            'jawaban.*.jawaban' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            foreach ($request->jawaban as $item) {
                // Update jawaban user
                $lembarJawab = JawabanIa06::where('id_jawaban_ia06', $item['id_lembar_jawab'])
                    ->where('id_data_sertifikasi_asesi', $idSertifikasi)
                    ->first();

                if ($lembarJawab) {
                    $lembarJawab->update([
                        'jawaban_asesi' => $item['jawaban'] ?? null,
                        'updated_at' => now(),
                    ]);
                }
            }

            $sertifikasi = DataSertifikasiAsesi::findOrFail($idSertifikasi);
            $sertifikasi->update([
                'status_sertifikasi' => 'asesmen_praktek_selesai', 
            ]);

            // Ambil ID Jadwal untuk redirect
            $sertifikasiData = DataSertifikasiAsesi::select('id_jadwal')->findOrFail($idSertifikasi);
            $idJadwalRedirect = $sertifikasiData->id_jadwal;

            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Jawaban essai berhasil disimpan.',
                'redirect_id_jadwal' => $idJadwalRedirect
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error Submit IA06: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Gagal menyimpan jawaban: ' . $e->getMessage()], 500);
        }
    }
}
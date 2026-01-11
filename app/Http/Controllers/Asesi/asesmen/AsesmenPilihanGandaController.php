<?php

namespace App\Http\Controllers\Asesi\asesmen;

use App\Models\SoalIA05;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\LembarJawabIA05;
use App\Models\KunciJawabanIA05;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\DataSertifikasiAsesi;
use Illuminate\Support\Facades\Auth;

class AsesmenPilihanGandaController extends Controller
{
    /**
     * Menampilkan halaman frontend asesmen pilihan ganda (Blade).
     */
    public function indexPilihanGanda($idSertifikasi)
    {
        $user = Auth::user();
        $isAdmin = $user->hasRole('admin') || $user->hasRole('superadmin');
        
        $sertifikasi = DataSertifikasiAsesi::with(['asesi', 'jadwal.skema'])->findOrFail($idSertifikasi);

        if (!$isAdmin && $sertifikasi->id_asesi !== $user->asesi->id_asesi) {
            abort(403, 'Unauthorized action.');
        }

        $asesi = $isAdmin ? $sertifikasi->asesi : $user->asesi;
        $jadwal = $sertifikasi->jadwal;
        
        // --- LOGIC WAKTU ---
        $tanggal = Carbon::parse($jadwal->tanggal_pelaksanaan)->format('Y-m-d');
        $jamSaja = Carbon::parse($jadwal->waktu_selesai)->format('H:i:s');
        
        $waktuSelesai = Carbon::parse($tanggal . ' ' . $jamSaja, 'Asia/Jakarta');
        $waktuSekarang = Carbon::now('Asia/Jakarta');

        $sisaWaktuDetik = $waktuSekarang->greaterThanOrEqualTo($waktuSelesai) ? 0 : $waktuSekarang->diffInSeconds($waktuSelesai);

        return view('asesi.assesmen.pertanyan_pilgan', [
            'asesi' => $user->asesi,
            'sertifikasi' => $sertifikasi,
            'sisa_waktu' => (int) $sisaWaktuDetik 
        ]);
    }

    /**
     * [GET API] Mengambil daftar soal.
     * LOGIC FIX: Filter by Jadwal & Auto-Sync (Sama seperti Esai/IA06)
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

            // 2. AMBIL MASTER SOAL IA05
            // Logic: Ambil soal yang Skema-nya cocok DAN (Jadwalnya spesifik user ini ATAU Jadwalnya NULL/Umum)
            $bankSoal = SoalIA05::where('id_skema', $idSkema)
                    ->where('id_jadwal', $idJadwal)
                    ->get();

            // Cek kalau kosong
            if ($bankSoal->isEmpty()) {
                return response()->json([
                    'success' => true, 
                    'data' => [], 
                    'message' => 'Belum ada soal pilihan ganda untuk skema/jadwal ini.'
                ]);
            }

            // 3. AUTO-SYNC (Cek mana soal yang belum masuk ke lembar jawab user)
            // Ambil ID Soal yang SUDAH ada di tabel lembar jawab user
            $existingSoalIds = LembarJawabIA05::where('id_data_sertifikasi_asesi', $idSertifikasi)
                ->pluck('id_soal_ia05') // Pastikan nama kolom FK ini benar
                ->toArray();

            // Filter $bankSoal: Ambil cuma yang ID-nya BELUM ada di $existingSoalIds
            $soalBaru = $bankSoal->whereNotIn('id_soal_ia05', $existingSoalIds);

            // 4. INSERT SOAL BARU (Jika ada)
            if ($soalBaru->isNotEmpty()) {
                $dataInsert = [];
                $now = now();

                foreach ($soalBaru as $soal) {
                    $dataInsert[] = [
                        'id_data_sertifikasi_asesi' => $idSertifikasi,
                        'id_soal_ia05' => $soal->id_soal_ia05,
                        'jawaban_asesi_ia05' => null, // Masih kosong
                        'pencapaian_ia05' => null,   // Belum dinilai
                        'created_at' => $now,
                        'updated_at' => $now,
                    ];
                }
                // Insert Sekaligus
                LembarJawabIA05::insert($dataInsert);
            }

            // 5. AMBIL DATA FINAL (Ambil dari tabel lembar jawab yang sudah lengkap)
            $dataFinal = LembarJawabIA05::with(['soal'])
                ->where('id_data_sertifikasi_asesi', $idSertifikasi)
                ->get();

            // 6. MAPPING OUTPUT
            $formattedData = $dataFinal
                ->map(function ($item) {
                    if (!$item->soal) return null;

                    return [
                        'id_lembar_jawab' => $item->id_lembar_jawab_ia05,
                        'id_soal_master' => $item->soal->id_soal_ia05,
                        'pertanyaan' => $item->soal->soal_ia05,
                        // Mapping Opsi Jawaban
                        'opsi' => [
                            ['key' => 'a', 'text' => $item->soal->opsi_a_ia05], 
                            ['key' => 'b', 'text' => $item->soal->opsi_b_ia05], 
                            ['key' => 'c', 'text' => $item->soal->opsi_c_ia05], 
                            ['key' => 'd', 'text' => $item->soal->opsi_d_ia05]
                        ],
                        'jawaban_tersimpan' => $item->jawaban_asesi_ia05,
                    ];
                })
                ->filter()
                ->values();

            return response()->json(['success' => true, 'data' => $formattedData]);

        } catch (\Exception $e) {
            Log::error('Error Get Questions IA05: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan server: ' . $e->getMessage()], 500);
        }
    }

    /**
     * [POST API] Menyimpan jawaban.
     */
    public function submitAnswers(Request $request, $idSertifikasi)
    {
        $request->validate([
            'jawaban' => 'required|array',
            'jawaban.*.id_lembar_jawab' => 'required|exists:lembar_jawab_ia05,id_lembar_jawab_ia05',
            'jawaban.*.jawaban' => 'nullable|in:a,b,c,d',
        ]);

        DB::beginTransaction();
        try {
            // Ambil semua ID soal dari lembar jawab yang dikirim
            $lembarJawabIds = array_column($request->jawaban, 'id_lembar_jawab');
            
            // Ambil ID Soal Master untuk mengambil kunci jawaban
            $soalIds = LembarJawabIA05::whereIn('id_lembar_jawab_ia05', $lembarJawabIds)
                        ->pluck('id_soal_ia05')
                        ->unique()
                        ->toArray();
            
            // Ambil Kunci Jawaban (Map: id_soal => jawaban_benar)
            $kunciJawabanMap = KunciJawabanIA05::whereIn('id_soal_ia05', $soalIds)
                ->pluck('jawaban_benar_ia05', 'id_soal_ia05')
                ->toArray();

            foreach ($request->jawaban as $item) {
                $lembarJawab = LembarJawabIA05::where('id_lembar_jawab_ia05', $item['id_lembar_jawab'])
                    ->where('id_data_sertifikasi_asesi', $idSertifikasi)
                    ->first();

                if ($lembarJawab) {
                    $jawabanAsesi = $item['jawaban'];
                    $idSoal = $lembarJawab->id_soal_ia05;
                    $kunciBenar = $kunciJawabanMap[$idSoal] ?? null;

                    // Logic Koreksi Otomatis
                    // Ubah jadi lowercase biar aman (opsional)
                    $isCorrect = $kunciBenar && (strtolower($jawabanAsesi) === strtolower($kunciBenar));
                    $statusPencapaian = $isCorrect ? 'ya' : 'tidak';

                    $lembarJawab->update([
                        'jawaban_asesi_ia05' => $jawabanAsesi,
                        'pencapaian_ia05' => $statusPencapaian,
                        'updated_at' => now(),
                    ]);
                }
            }

            $sertifikasi = DataSertifikasiAsesi::findOrFail($idSertifikasi);
            $sertifikasi->update([
                'status_sertifikasi' => 'asesmen_praktek_selesai', 
            ]);

            $sertifikasiData = DataSertifikasiAsesi::select('id_jadwal')->findOrFail($idSertifikasi);
            $idJadwalRedirect = $sertifikasiData->id_jadwal;

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Jawaban berhasil disimpan.',
                'redirect_id_jadwal' => $idJadwalRedirect,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error Submit IA05: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Gagal: ' . $e->getMessage()], 500);
        }
    }
}
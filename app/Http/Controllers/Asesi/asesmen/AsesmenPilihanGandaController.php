<?php

namespace App\Http\Controllers\Asesi\asesmen;

use App\Models\SoalIA05;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\LembarJawabIA05;
use App\Models\KunciJawabanIA05;
use Illuminate\Support\Facades\DB;

// --- MODEL ---
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
        $sertifikasi = DataSertifikasiAsesi::with(['asesi', 'jadwal.skema'])->findOrFail($idSertifikasi);

        if ($sertifikasi->id_asesi !== $user->asesi->id_asesi) {
            abort(403, 'Unauthorized action.');
        }

        $jadwal = $sertifikasi->jadwal;
        
        // --- PERBAIKAN FORMAT WAKTU (OPSI 1 YANG KITA BAHAS SEBELUMNYA) ---
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
     * LOGIC BARU: GENERATE OTOMATIS BERDASARKAN SKEMA.
     */
    public function getQuestions($idSertifikasi)
    {
        try {
            // 1. CEK APAKAH LEMBAR JAWAB SUDAH ADA?
            $cekLembarJawab = LembarJawabIA05::where('id_data_sertifikasi_asesi', $idSertifikasi)->exists();

            // 2. JIKA BELUM ADA, KITA GENERATE DULU DARI BANK SOAL
            if (!$cekLembarJawab) {
                
                // Ambil data sertifikasi beserta relasi ke Skema
                $sertifikasi = DataSertifikasiAsesi::with('jadwal.skema')->find($idSertifikasi);

                if (!$sertifikasi || !$sertifikasi->jadwal) {
                    return response()->json(['success' => false, 'message' => 'Data Sertifikasi/Jadwal tidak valid.'], 404);
                }

                $idSkema = $sertifikasi->jadwal->id_skema;

                // Ambil soal dari MASTER SOAL yang id_skema-nya cocok
                // Pastikan di tabel 'soal_ia05' ada kolom 'id_skema'
                $bankSoal = SoalIA05::where('id_skema', $idSkema)->get();

                if ($bankSoal->isEmpty()) {
                    return response()->json([
                        'success' => true,
                        'data' => [],
                        'message' => 'Bank soal untuk skema ini belum tersedia. Hubungi Admin.',
                    ]);
                }

                // Siapkan data untuk Bulk Insert (Biar cepat, gak satu-satu)
                $dataInsert = [];
                $now = now();

                foreach ($bankSoal as $soal) {
                    $dataInsert[] = [
                        'id_data_sertifikasi_asesi' => $idSertifikasi,
                        'id_soal_ia05' => $soal->id_soal_ia05,
                        'jawaban_asesi_ia05' => null,
                        'pencapaian_ia05' => null,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ];
                }

                // Masukkan ke database sekaligus
                LembarJawabIA05::insert($dataInsert);
            }

            // 3. SEKARANG AMBIL DATA (Entah itu baru dibuat atau emang udah ada)
            $data = LembarJawabIA05::with(['soal'])
                ->where('id_data_sertifikasi_asesi', $idSertifikasi)
                ->get();

            // 4. MAPPING DATA (Sama kayak kode lu sebelumnya)
            $formattedData = $data
                ->map(function ($item) {
                    if (!$item->soal) return null;

                    return [
                        'id_lembar_jawab' => $item->id_lembar_jawab_ia05,
                        'id_soal_master' => $item->soal->id_soal_ia05,
                        'pertanyaan' => $item->soal->soal_ia05,
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
            $lembarJawabIds = array_column($request->jawaban, 'id_lembar_jawab');
            $soalIds = LembarJawabIA05::whereIn('id_lembar_jawab_ia05', $lembarJawabIds)->pluck('id_soal_ia05')->unique()->toArray();
            
            // Ambil Kunci Jawaban
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

                    // Logic Koreksi
                    $statusPencapaian = ($kunciBenar && $jawabanAsesi === $kunciBenar) ? 'ya' : 'tidak';

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
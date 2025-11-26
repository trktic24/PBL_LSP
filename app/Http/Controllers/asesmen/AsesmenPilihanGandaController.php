<?php

namespace App\Http\Controllers\asesmen;

use App\Http\Controllers\Controller;
use App\Models\DataSertifikasiAsesi;
use App\Models\KunciJawabanIa05;
use App\Models\LembarJawabIa05;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class AsesmenPilihanGandaController extends Controller
{
    /**
     * Menampilkan halaman frontend asesmen pilihan ganda (Blade).
     */
    public function indexPilihanGanda($idSertifikasi)
    {
        $user = Auth::user();

        // Ambil data sertifikasi dan relasi untuk sidebar/header
        $sertifikasi = DataSertifikasiAsesi::with([
            'asesi',
            'jadwal.skema',
        ])->findOrFail($idSertifikasi);

        // Validasi kepemilikan data
        if ($sertifikasi->id_asesi !== $user->asesi->id_asesi) {
            abort(403, 'Unauthorized action.');
        }

        return view('assesmen.pertanyan_pilgan', [ // Pastikan nama file blade sesuai
            'asesi' => $user->asesi,
            'sertifikasi' => $sertifikasi,
        ]);
    }

    /**
     * [GET API] Mengambil daftar soal untuk ditampilkan di frontend.
     * Asumsi: Admin sudah menyiapkan data di tabel lembar_jawab_ia05.
     */
    public function getQuestions($idSertifikasi)
    {
        try {
            // Ambil data dari lembar jawab, join (eager load) ke master soal
            // untuk mendapatkan teks pertanyaan dan opsi.
            $data = LembarJawabIa05::with(['soal'])
                ->where('id_data_sertifikasi_asesi', $idSertifikasi)
                // Optional: Urutkan berdasarkan ID soal biar konsisten tampilannya
                // ->whereHas('soal', function($q) { $q->orderBy('id_soal_ia05'); })
                ->get();

            if ($data->isEmpty()) {
                return response()->json([
                    'success' => true, 
                    'data' => [], 
                    'message' => 'Soal asesmen belum tersedia. Silakan hubungi Asesor.'
                ]);
            }

            // Mapping data agar format JSON-nya rapi untuk Frontend
            // DI SINI KITA SESUAIKAN DENGAN NAMA KOLOM DI MIGRASI KAMU
            $formattedData = $data->map(function ($item) {
                // Safety check jika data master soal terhapus
                if (!$item->soal) return null;

                return [
                    // KUNCI UTAMA UNTUK UPDATE NANTI (PK tabel lembar jawab)
                    'id_lembar_jawab' => $item->id_lembar_jawab_ia05,
                    
                    // ID Soal Master (untuk referensi)
                    'id_soal_master' => $item->soal->id_soal_ia05,

                    // MENGAMBIL DATA DARI TABEL soal_ia05
                    // (Sesuai nama kolom di migrasi)
                    'pertanyaan' => $item->soal->soal_ia05, 
                    'opsi' => [
                        ['key' => 'a', 'text' => $item->soal->opsi_jawaban_a],
                        ['key' => 'b', 'text' => $item->soal->opsi_jawaban_b],
                        ['key' => 'c', 'text' => $item->soal->opsi_jawaban_c],
                        ['key' => 'd', 'text' => $item->soal->opsi_jawaban_d]
                    ],
                    
                    // MENGAMBIL DATA DARI TABEL lembar_jawab_ia05
                    // Jawaban yang sudah dipilih asesi sebelumnya (jika ada)
                    'jawaban_tersimpan' => $item->jawaban_asesi,
                ];
            })->filter()->values(); // Hapus data null jika ada

            return response()->json(['success' => true, 'data' => $formattedData]);

        } catch (\Exception $e) {
            Log::error('Error Get Questions IA05: ' . $e->getMessage());
            // Tampilkan pesan error yang lebih detail untuk debugging (bisa diubah nanti)
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan server: ' . $e->getMessage()], 500);
        }
    }

    /**
     * [POST API] Menyimpan jawaban asesi DENGAN KOREKSI OTOMATIS.
     */
    public function submitAnswers(Request $request, $idSertifikasi)
    {
        // Validasi input
        $request->validate([
            'jawaban' => 'required|array',
            'jawaban.*.id_lembar_jawab' => 'required|exists:lembar_jawab_ia05,id_lembar_jawab_ia05',
            'jawaban.*.jawaban' => 'required|in:a,b,c,d',
        ]);

        DB::beginTransaction();
        try {
            // --- TAHAP 1: PERSIAPAN KOREKSI ---
            $lembarJawabIds = array_column($request->jawaban, 'id_lembar_jawab');
            $soalIds = LembarJawabIa05::whereIn('id_lembar_jawab_ia05', $lembarJawabIds)
                        ->pluck('id_soal_ia05')->unique()->toArray();
            
            $kunciJawabanMap = KunciJawabanIa05::whereIn('id_soal_ia05', $soalIds)
                                ->pluck('jawaban_benar', 'id_soal_ia05')->toArray();

            // --- TAHAP 2: PROSES SIMPAN & KOREKSI ---
            foreach ($request->jawaban as $item) {
                $lembarJawab = LembarJawabIa05::where('id_lembar_jawab_ia05', $item['id_lembar_jawab'])
                                ->where('id_data_sertifikasi_asesi', $idSertifikasi)
                                ->first();

                if ($lembarJawab) {
                    $jawabanAsesi = $item['jawaban'];
                    $idSoal = $lembarJawab->id_soal_ia05;
                    $kunciBenar = $kunciJawabanMap[$idSoal] ?? null;
                    
                    $statusPencapaian = ($kunciBenar && $jawabanAsesi === $kunciBenar) ? 'ya' : 'tidak';

                    $lembarJawab->update([
                        'jawaban_asesi' => $jawabanAsesi,
                        'pencapaian' => $statusPencapaian,
                        'updated_at' => now(),
                    ]);
                }
            }

            // --- [PERBAIKAN DI SINI] ---
            // Sebelum return, kita cari tahu dulu ID JADWAL untuk redirect.
            // Kita hanya butuh kolom id_jadwal saja agar query ringan.
            $sertifikasiData = DataSertifikasiAsesi::select('id_jadwal')
                                ->findOrFail($idSertifikasi);
            
            $idJadwalRedirect = $sertifikasiData->id_jadwal;
            // ---------------------------

            DB::commit();

            // --- KIRIM ID JADWAL KE FRONTEND ---
            return response()->json([
                'success' => true,
                'message' => 'Jawaban berhasil disimpan dan dikoreksi otomatis.',
                // Tambahkan data ini di response JSON
                'redirect_id_jadwal' => $idJadwalRedirect 
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error Submit IA05 with Auto-Correction: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Gagal menyimpan jawaban: ' . $e->getMessage()], 500);
        }
    }
}
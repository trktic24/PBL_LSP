<?php

namespace App\Http\Controllers\Asesi\asesmen;

use Carbon\Carbon; // PENTING: Jangan lupa import Carbon
use App\Http\Controllers\Controller;
use App\Models\DataSertifikasiAsesi;
use App\Models\JawabanIa06; // Pastikan Model ini connect ke tabel 'lembar_jawab_ia06'
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class AsesmenEsaiController extends Controller
{
    /**
     * Menampilkan halaman frontend asesmen essai (Blade).
     */
    public function indexEsai($idSertifikasi)
    {
        $user = Auth::user();

        $sertifikasi = DataSertifikasiAsesi::with([
            'asesi',
            'jadwal.skema',
        ])->findOrFail($idSertifikasi);

        // Validasi akses user
        if ($sertifikasi->id_asesi !== $user->asesi->id_asesi) {
            abort(403, 'Unauthorized action.');
        }

        // --- LOGIC HITUNG MUNDUR WAKTU (SAMA SEPERTI IA-05) ---
        $jadwal = $sertifikasi->jadwal;
        $tanggal = Carbon::parse($jadwal->tanggal_pelaksanaan)->format('Y-m-d');

        // 1. Set Timezone Explicit ke Asia/Jakarta
        $waktuSelesai = Carbon::parse($tanggal . ' ' . $jadwal->waktu_selesai, 'Asia/Jakarta');
        $waktuSekarang = Carbon::now('Asia/Jakarta');

        // 2. Hitung Sisa Waktu
        $sisaWaktuDetik = $waktuSekarang->greaterThanOrEqualTo($waktuSelesai) 
            ? 0 
            : $waktuSekarang->diffInSeconds($waktuSelesai);
        // -------------------------------------------------------

        return view('asesi.assesmen.asesmen_esai', [ 
            'asesi' => $user->asesi,
            'sertifikasi' => $sertifikasi,
            'sisa_waktu' => (int) $sisaWaktuDetik // Kirim variabel waktu ke blade
        ]);
    }

    /**
     * [GET API] Mengambil daftar soal essai.
     */
    public function getQuestions($idSertifikasi)
    {
        try {
            // Ambil data dari tabel 'lembar_jawab_ia06'
            $data = JawabanIa06::with(['soal'])
                ->where('id_data_sertifikasi_asesi', $idSertifikasi)
                ->get();

            if ($data->isEmpty()) {
                return response()->json([
                    'success' => true, 
                    'data' => [], 
                    'message' => 'Soal essai belum tersedia. Silakan hubungi Asesor.'
                ]);
            }

            // Mapping data agar rapi untuk Frontend
            $formattedData = $data->map(function ($item) {
                if (!$item->soal) return null;

                return [
                    // KUNCI UTAMA (PK tabel lembar_jawab_ia06)
                    // Pastikan di Model JawabanIa06 primary key-nya sesuai (misal: id_lembar_jawab_ia06)
                    'id_lembar_jawab' => $item->id_jawaban_ia06 ?? $item->id_kunci_ia06, // Sesuaikan dengan PK tabelmu
                    
                    // Teks Soal dari master soal
                    'pertanyaan' => $item->soal->soal_ia06, 
                    
                    // Jawaban essai yang sudah disimpan (jika ada)
                    // [PERUBAHAN]: Menggunakan nama kolom 'jawaban_asesi'
                    'jawaban_tersimpan' => $item->jawaban_asesi,
                ];
            })->filter()->values();

            return response()->json(['success' => true, 'data' => $formattedData]);

        } catch (\Exception $e) {
            Log::error('Error Get Questions IA06: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan server: ' . $e->getMessage()], 500);
        }
    }

    /**
     * [POST API] Menyimpan jawaban essai asesi.
     */
    public function submitAnswers(Request $request, $idSertifikasi)
    {
        $request->validate([
            'jawaban' => 'required|array',
            // [PERUBAHAN]: Validasi ke tabel lembar_jawab_ia06 (bukan kunci)
            'jawaban.*.id_lembar_jawab' => 'required|exists:jawaban_ia06,id_jawaban_ia06', 
            'jawaban.*.jawaban' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            foreach ($request->jawaban as $item) {
                // Update tabel 'lembar_jawab_ia06'
                // Pastikan menggunakan Model yang benar atau DB Query builder
                
                // OPSI 1: Pakai Model (Jika PK Model sudah benar 'id_lembar_jawab_ia06')
                $lembarJawab = JawabanIa06::where('id_jawaban_ia06', $item['id_lembar_jawab'])
                    ->where('id_data_sertifikasi_asesi', $idSertifikasi)
                    ->first();

                if ($lembarJawab) {
                    $lembarJawab->update([
                        // [PERUBAHAN]: Nama kolom 'jawaban_asesi' (tanpa _ia06)
                        'jawaban_asesi' => $item['jawaban'] ?? null,
                        'updated_at' => now(),
                    ]);
                }
            }

            // Ambil ID Jadwal untuk redirect balik ke tracker
            $sertifikasiData = DataSertifikasiAsesi::select('id_jadwal')
                                ->findOrFail($idSertifikasi);
            $idJadwalRedirect = $sertifikasiData->id_jadwal;

            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Jawaban essai berhasil disimpan. Menunggu penilaian asesor.',
                'redirect_id_jadwal' => $idJadwalRedirect
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error Submit IA06: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Gagal menyimpan jawaban: ' . $e->getMessage()], 500);
        }
    }
}
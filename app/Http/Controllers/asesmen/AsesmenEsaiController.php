<?php

namespace App\Http\Controllers\asesmen;

use App\Http\Controllers\Controller;
use App\Models\DataSertifikasiAsesi;
use App\Models\KunciIa06; // Ini model untuk tabel transaksi 'kunci_ia06'
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

        if ($sertifikasi->id_asesi !== $user->asesi->id_asesi) {
            abort(403, 'Unauthorized action.');
        }

        // Pastikan nama file bladenya nanti 'asesmen_esai.blade.php'
        return view('assesmen.asesmen_esai', [ 
            'asesi' => $user->asesi,
            'sertifikasi' => $sertifikasi,
        ]);
    }

    /**
     * [GET API] Mengambil daftar soal essai.
     * ASUMSI: Data sudah disiapkan Asesor di tabel 'kunci_ia06'.
     */
    public function getQuestions($idSertifikasi)
    {
        try {
            // Ambil data dari tabel transaksi 'kunci_ia06', join ke master 'soal_ia06'
            $data = KunciIa06::with(['soal'])
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
                    // KUNCI UTAMA UNTUK UPDATE (PK tabel kunci_ia06)
                    'id_lembar_jawab' => $item->id_kunci_ia06,
                    
                    // Teks Soal dari master soal
                    'pertanyaan' => $item->soal->soal_ia06, 
                    
                    // Jawaban essai yang sudah disimpan (jika ada)
                    // Sesuai nama kolom di migrasi 'kunci_ia06'
                    'jawaban_tersimpan' => $item->teks_jawaban_ia06,
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
            // Validasi PK tabel kunci_ia06
            'jawaban.*.id_lembar_jawab' => 'required|exists:kunci_ia06,id_kunci_ia06',
            // Jawaban essai bisa teks panjang dan boleh kosong (nullable di database)
            'jawaban.*.jawaban' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            foreach ($request->jawaban as $item) {
                // Update tabel 'kunci_ia06'
                KunciIa06::where('id_kunci_ia06', $item['id_lembar_jawab'])
                    ->where('id_data_sertifikasi_asesi', $idSertifikasi)
                    ->update([
                        // Simpan teks jawaban ke kolom 'teks_jawaban_ia06'
                        'teks_jawaban_ia06' => $item['jawaban'] ?? null,
                        'updated_at' => now(),
                    ]);
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
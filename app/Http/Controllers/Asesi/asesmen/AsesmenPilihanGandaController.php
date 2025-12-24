<?php

namespace App\Http\Controllers\Asesi\asesmen;

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

        // Ambil data sertifikasi dan relasi untuk sidebar/header
        $sertifikasi = DataSertifikasiAsesi::with(['asesi', 'jadwal.skema'])->findOrFail($idSertifikasi);

        // Validasi kepemilikan data
        if ($sertifikasi->id_asesi !== $user->asesi->id_asesi) {
            abort(403, 'Unauthorized action.');
        }

        $jadwal = $sertifikasi->jadwal;
        $tanggal = Carbon::parse($jadwal->tanggal_pelaksanaan)->format('Y-m-d');

        // Gabungkan Tanggal + Jam Selesai
        $jamSaja = Carbon::parse($jadwal->waktu_selesai)->format('H:i:s');
        $waktuSelesai = Carbon::parse($tanggal . ' ' . $jamSaja, 'Asia/Jakarta');
        $waktuSekarang = Carbon::now('Asia/Jakarta');

        // Hitung selisih waktu dalam detik
        // Jika waktu sekarang sudah lewat waktu selesai, hasilnya 0
        $sisaWaktuDetik = $waktuSekarang->greaterThanOrEqualTo($waktuSelesai) ? 0 : $waktuSekarang->diffInSeconds($waktuSelesai);

        return view('asesi.assesmen.pertanyan_pilgan', [
            // Pastikan nama file blade sesuai
            'asesi' => $user->asesi,
            'sertifikasi' => $sertifikasi,
            'sisa_waktu' => (int) $sisaWaktuDetik 
        ]);
    }

    /**
     * [GET API] Mengambil daftar soal untuk ditampilkan di frontend.
     * Asumsi: Admin/Asesor sudah menyiapkan data di tabel lembar_jawab_ia05.
     */
    public function getQuestions($idSertifikasi)
    {
        try {
            // Ambil data dari lembar jawab, join (eager load) ke master soal
            $data = LembarJawabIA05::with(['soal'])
                ->where('id_data_sertifikasi_asesi', $idSertifikasi)
                // Optional: Urutkan berdasarkan ID soal biar konsisten
                // ->whereHas('soal', function($q) { $q->orderBy('id_soal_ia05'); })
                ->get();

            if ($data->isEmpty()) {
                return response()->json([
                    'success' => true,
                    'data' => [],
                    'message' => 'Soal asesmen belum tersedia. Silakan hubungi Asesor.',
                ]);
            }

            // Mapping data agar format JSON-nya rapi untuk Frontend
            // [PERUBAHAN PENTING: SESUAIKAN NAMA KOLOM DENGAN MIGRASI BARU]
            $formattedData = $data
                ->map(function ($item) {
                    // Safety check jika data master soal terhapus
                    if (!$item->soal) {
                        return null;
                    }

                    return [
                        // KUNCI UTAMA UNTUK UPDATE NANTI (PK tabel lembar jawab)
                        'id_lembar_jawab' => $item->id_lembar_jawab_ia05,

                        // ID Soal Master (untuk referensi)
                        'id_soal_master' => $item->soal->id_soal_ia05,

                        // MENGAMBIL DATA DARI TABEL soal_ia05 (Nama kolom baru)
                        'pertanyaan' => $item->soal->soal_ia05,
                        'opsi' => [['key' => 'a', 'text' => $item->soal->opsi_a_ia05], ['key' => 'b', 'text' => $item->soal->opsi_b_ia05], ['key' => 'c', 'text' => $item->soal->opsi_c_ia05], ['key' => 'd', 'text' => $item->soal->opsi_d_ia05]],

                        // MENGAMBIL DATA DARI TABEL lembar_jawab_ia05 (Nama kolom baru)
                        // Jawaban yang sudah dipilih asesi sebelumnya (jika ada)
                        'jawaban_tersimpan' => $item->jawaban_asesi_ia05,
                    ];
                })
                ->filter()
                ->values(); // Hapus data null jika ada

            return response()->json(['success' => true, 'data' => $formattedData]);
        } catch (\Exception $e) {
            Log::error('Error Get Questions IA05: ' . $e->getMessage());
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
            // Pastikan validasi menggunakan nama PK yang benar
            'jawaban.*.id_lembar_jawab' => 'required|exists:lembar_jawab_ia05,id_lembar_jawab_ia05',
            'jawaban.*.jawaban' => 'required|in:a,b,c,d',
        ]);

        DB::beginTransaction();
        try {
            // --- TAHAP 1: PERSIAPAN KOREKSI ---

            // Ambil semua ID lembar jawab yang dikirim
            $lembarJawabIds = array_column($request->jawaban, 'id_lembar_jawab');

            // Cari tahu ID Soal Master apa saja yang sedang dijawab
            $soalIds = LembarJawabIA05::whereIn('id_lembar_jawab_ia05', $lembarJawabIds)->pluck('id_soal_ia05')->unique()->toArray();

            // Ambil KUNCI JAWABAN BENAR dari tabel kunci_jawaban_ia05
            // [PERUBAHAN PENTING: GUNAAN NAMA KOLOM BARU 'jawaban_benar_ia05']
            $kunciJawabanMap = KunciJawabanIA05::whereIn('id_soal_ia05', $soalIds)->pluck('jawaban_benar_ia05', 'id_soal_ia05')->toArray();

            // --- TAHAP 2: PROSES SIMPAN & KOREKSI ---
            foreach ($request->jawaban as $item) {
                // Cari record lembar jawab yang akan diupdate
                $lembarJawab = LembarJawabIA05::where('id_lembar_jawab_ia05', $item['id_lembar_jawab'])
                    ->where('id_data_sertifikasi_asesi', $idSertifikasi) // Keamanan tambahan
                    ->first();

                if ($lembarJawab) {
                    $jawabanAsesi = $item['jawaban'];
                    $idSoal = $lembarJawab->id_soal_ia05;

                    // --- LOGIKA KOREKSI OTOMATIS ---
                    // Ambil kunci benar dari map yang sudah kita siapkan
                    $kunciBenar = $kunciJawabanMap[$idSoal] ?? null;

                    // Default pencapaian adalah 'tidak' (salah)
                    $statusPencapaian = 'tidak';

                    // Jika kunci ditemukan DAN jawaban asesi sama dengan kunci
                    if ($kunciBenar && $jawabanAsesi === $kunciBenar) {
                        $statusPencapaian = 'ya'; // Berarti benar
                    }
                    // -----------------------

                    // Update tabel lembar_jawab_ia05
                    // [PERUBAHAN PENTING: GUNAAN NAMA KOLOM BARU]
                    $lembarJawab->update([
                        'jawaban_asesi_ia05' => $jawabanAsesi,
                        'pencapaian_ia05' => $statusPencapaian, // Hasil koreksi otomatis
                        'updated_at' => now(),
                    ]);
                }
            }

            // --- AMBIL ID JADWAL UNTUK REDIRECT ---
            $sertifikasiData = DataSertifikasiAsesi::select('id_jadwal')->findOrFail($idSertifikasi);
            $idJadwalRedirect = $sertifikasiData->id_jadwal;
            // -------------------------------------

            DB::commit(); // Simpan semua perubahan

            return response()->json([
                'success' => true,
                'message' => 'Jawaban berhasil disimpan dan dikoreksi otomatis.',
                'redirect_id_jadwal' => $idJadwalRedirect, // Kirim ID Jadwal ke frontend
            ]);
        } catch (\Exception $e) {
            DB::rollBack(); // Batalkan semua jika ada error
            Log::error('Error Submit IA05 with Auto-Correction: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Gagal menyimpan jawaban: ' . $e->getMessage()], 500);
        }
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LembarJawabIa05Seeder extends Seeder
{
    public function run(): void
    {
        // 1. KONFIGURASI JUMLAH SOAL PER PESERTA
        $jumlahSoal = 10; 
        $now = now();

        $this->command->info("🔄 Memulai proses seeding Lembar Jawab IA-05 untuk SEMUA Asesi...");

        // 2. AMBIL SEMUA DATA SERTIFIKASI (Beserta ID Skema-nya)
        // Kita perlu JOIN ke tabel jadwal untuk tahu sertifikasi ini pakai Skema ID berapa
        $listSertifikasi = DB::table('data_sertifikasi_asesi')
            ->join('jadwal', 'data_sertifikasi_asesi.id_jadwal', '=', 'jadwal.id_jadwal')
            ->select(
                'data_sertifikasi_asesi.id_data_sertifikasi_asesi', 
                'jadwal.id_skema' // Kita butuh ini buat filter soal
            )
            ->get();

        if ($listSertifikasi->isEmpty()) {
            $this->command->warn("⚠️ Tidak ada data sertifikasi asesi ditemukan.");
            return;
        }

        $this->command->info("🔎 Ditemukan " . $listSertifikasi->count() . " peserta sertifikasi. Sedang memproses...");

        DB::transaction(function () use ($listSertifikasi, $jumlahSoal, $now) {
            
            $totalSoalTergenerate = 0;

            foreach ($listSertifikasi as $sertifikasi) {
                
                // 3. CEK APAKAH SUDAH PUNYA SOAL? (Opsional, biar gak duplikat kalau di-seed 2x)
                $sudahAda = DB::table('lembar_jawab_ia05')
                    ->where('id_data_sertifikasi_asesi', $sertifikasi->id_data_sertifikasi_asesi)
                    ->exists();

                if ($sudahAda) {
                    continue; // Skip kalau sudah ada soalnya
                }

                // 4. AMBIL SOAL SESUAI ID SKEMA PESERTA
                // Ini logic pentingnya: Ambil soal dimana id_skema == id_skema milik sertifikasi
                $soalPaket = DB::table('soal_ia05')
                    ->where('id_skema', $sertifikasi->id_skema) 
                    ->inRandomOrder()
                    ->take($jumlahSoal)
                    ->get();

                if ($soalPaket->isEmpty()) {
                    // Log warning tapi jangan stop loop, mungkin skema lain ada soalnya
                    // $this->command->warn("⚠️ Skema ID {$sertifikasi->id_skema} belum memiliki Bank Soal.");
                    continue;
                }

                // 5. SIAPKAN DATA INSERT
                $dataToInsert = [];
                foreach ($soalPaket as $soal) {
                    $dataToInsert[] = [
                        'id_data_sertifikasi_asesi' => $sertifikasi->id_data_sertifikasi_asesi,
                        'id_soal_ia05'              => $soal->id_soal_ia05,
                        'jawaban_asesi_ia05'        => null, 
                        'pencapaian_ia05'           => null,
                        'created_at'                => $now,
                        'updated_at'                => $now,
                    ];
                }

                // 6. EKSEKUSI INSERT
                if (!empty($dataToInsert)) {
                    DB::table('lembar_jawab_ia05')->insert($dataToInsert);
                    $totalSoalTergenerate += count($dataToInsert);
                }
            }

            $this->command->info("✅ SUKSES! Total $totalSoalTergenerate baris lembar jawab berhasil dibuat untuk seluruh asesi.");
        });
    }
}
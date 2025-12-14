<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KunciIa06TransaksiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. KONFIGURASI JUMLAH SOAL
        $jumlahSoal = 5; 
        $now = now();

        $this->command->info("🔄 Memulai proses seeding Lembar Jawab ESSAI (IA-06) untuk SEMUA Asesi...");

        // 2. AMBIL SEMUA DATA SERTIFIKASI + ID SKEMA
        // Kita join ke tabel jadwal untuk tahu skema-nya
        $listSertifikasi = DB::table('data_sertifikasi_asesi')
            ->join('jadwal', 'data_sertifikasi_asesi.id_jadwal', '=', 'jadwal.id_jadwal')
            ->select(
                'data_sertifikasi_asesi.id_data_sertifikasi_asesi', 
                'jadwal.id_skema' // Penting buat filter soal
            )
            ->get();

        if ($listSertifikasi->isEmpty()) {
            $this->command->warn("⚠️ Tidak ada data sertifikasi asesi ditemukan.");
            return;
        }

        $this->command->info("🔎 Ditemukan " . $listSertifikasi->count() . " peserta sertifikasi. Sedang memproses...");

        DB::transaction(function () use ($listSertifikasi, $jumlahSoal, $now) {
            
            $totalTergenerate = 0;

            foreach ($listSertifikasi as $sertifikasi) {

                // 3. CEK APAKAH SUDAH ADA DATA? (Biar gak duplikat)
                $sudahAda = DB::table('kunci_ia06')
                    ->where('id_data_sertifikasi_asesi', $sertifikasi->id_data_sertifikasi_asesi)
                    ->exists();

                if ($sudahAda) {
                    continue; // Skip kalau sudah ada
                }

                // 4. AMBIL SOAL SESUAI SKEMA
                // Pastikan kolom 'id_skema' ada di tabel 'soal_ia06' (hasil seeder sebelumnya)
                $soalPaket = DB::table('soal_ia06')
                    ->where('id_skema', $sertifikasi->id_skema)
                    ->inRandomOrder()
                    ->take($jumlahSoal)
                    ->get();

                if ($soalPaket->isEmpty()) {
                    // $this->command->warn("⚠️ Skema ID {$sertifikasi->id_skema} belum punya soal Essai.");
                    continue;
                }

                // 5. SIAPKAN DATA INSERT
                $dataToInsert = [];
                foreach ($soalPaket as $soal) {
                    $dataToInsert[] = [
                        'id_data_sertifikasi_asesi' => $sertifikasi->id_data_sertifikasi_asesi,
                        'id_soal_ia06'              => $soal->id_soal_ia06,
                        
                        // Jawaban awal kosong
                        'teks_jawaban_ia06'         => "", 
                        'created_at'                => $now,
                        'updated_at'                => $now,
                    ];
                }

                // 6. EKSEKUSI INSERT
                if (!empty($dataToInsert)) {
                    DB::table('kunci_ia06')->insert($dataToInsert);
                    $totalTergenerate += count($dataToInsert);
                }
            }

            $this->command->info("✅ SUKSES! Total $totalTergenerate baris jawaban Essai berhasil dibuat untuk seluruh asesi.");
        });
    }
}
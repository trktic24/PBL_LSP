<?php

namespace Database\Seeders;

use App\Models\SoalIa06;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KunciIa06TransaksiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // ================================================================
        // KONFIGURASI PENTING (WAJIB DIUBAH SESUAI KEBUTUHAN)
        // ================================================================
        
        // GANTI ANGKA '1' DI BAWAH INI dengan ID Data Sertifikasi Asesi
        // yang sedang kamu buka di browser dan mengalami loading terus.
        // Contoh: Jika URL-nya /asesmen/ia06/5, maka isi angka 5.
        $targetIdSertifikasi = 81; 

        // Berapa soal yang mau diberikan ke asesi ini?
        $jumlahSoal = 5;

        // ================================================================

        $this->command->info("Memulai proses seeding Transaksi Essai (Kunci IA-06)...");

        // Cek apakah data sertifikasi target ada
        $targetExists = DB::table('data_sertifikasi_asesi')
                        ->where('id_data_sertifikasi_asesi', $targetIdSertifikasi)
                        ->exists();

        if (!$targetExists) {
            $this->command->error("Gagal! Data Sertifikasi dengan ID $targetIdSertifikasi tidak ditemukan.");
            return;
        }

        // 1. Ambil soal master secara acak dari tabel soal_ia06
        // Pastikan kamu sudah menjalankan Seeder Langkah 1 sebelumnya.
        $soalMaster = SoalIa06::inRandomOrder()->take($jumlahSoal)->get();

        if ($soalMaster->isEmpty()) {
            $this->command->error('Gagal! Tabel master "soal_ia06" masih kosong. Jalankan SoalIa06MasterSeeder dulu.');
            return;
        }

        $dataToInsert = [];
        $now = now();

        // 2. Loop soal dan hubungkan ke asesi target
        foreach ($soalMaster as $soal) {
            $dataToInsert[] = [
                // GUNAKAN NAMA KOLOM SESUAI MIGRASI KAMU (kunci_ia06)
                'id_soal_ia06' => $soal->id_soal_ia06,
                'id_data_sertifikasi_asesi' => $targetIdSertifikasi,
                
                // Jawaban awal masih kosong (null)
                'teks_jawaban_ia06' => "", 
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        // 3. Masukkan ke tabel transaksi kunci_ia06
        DB::table('kunci_ia06')->insert($dataToInsert);
        
        $this->command->info("SUKSES! Berhasil menghubungkan " . count($dataToInsert) . " soal essai untuk ID Sertifikasi: $targetIdSertifikasi.");
        $this->command->info("Silakan refresh halaman frontend asesmen essai.");
    }
}
<?php

namespace Database\Seeders;

use App\Models\SoalIa05;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LembarJawabIa05Seeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // ================================================================
        // KONFIGURASI SEEDER (SESUAIKAN DI SINI)
        // ================================================================
        
        // 1. GANTI ID INI dengan ID Data Sertifikasi Asesi yang mau kamu tes.
        $targetIdSertifikasi = 81; 
        
        // 2. Berapa jumlah soal yang mau diberikan ke asesi ini?
        $jumlahSoal = 8;

        // ================================================================

        $this->command->info("Memulai proses seeding Lembar Jawab IA-05...");

        // Cek dulu apakah data sertifikasi target ada
        // Pastikan nama tabel 'data_sertifikasi_asesi' sudah benar (kadang ada 's'-nya)
        $targetExists = DB::table('data_sertifikasi_asesi') // atau 'data_sertifikasi_asesis'
                        ->where('id_data_sertifikasi_asesi', $targetIdSertifikasi)
                        ->exists();

        if (!$targetExists) {
            $this->command->error("Gagal! Data Sertifikasi dengan ID $targetIdSertifikasi tidak ditemukan.");
            return;
        }

        // 1. Ambil sejumlah soal master secara acak dari tabel soal_ia05
        $soalMaster = SoalIa05::inRandomOrder()->take($jumlahSoal)->get();

        if ($soalMaster->isEmpty()) {
            $this->command->error('Gagal! Tabel master "soal_ia05" masih kosong.');
            $this->command->warn('Jalankan "SoalDanKunciSeeder" terlebih dahulu.');
            return;
        }

        if ($soalMaster->count() < $jumlahSoal) {
             $this->command->warn("Hanya menemukan " . $soalMaster->count() . " soal di master. Semua akan digunakan.");
        }

        $dataToInsert = [];
        $now = now();

        // 2. Loop soal-soal yang didapat dan siapkan data untuk tabel penghubung
        foreach ($soalMaster as $soal) {
            $dataToInsert[] = [
                // HUBUNGKAN PESERTA DENGAN SOAL (FK)
                'id_data_sertifikasi_asesi' => $targetIdSertifikasi,
                'id_soal_ia05' => $soal->id_soal_ia05,
                
                // [PERUBAHAN DI SINI: GUNAAN NAMA KOLOM BARU]
                // Kolom lain dibiarkan default (null/belum dijawab)
                'jawaban_asesi_ia05' => null, // Nama kolom baru
                'pencapaian_ia05' => null,     // Nama kolom baru
                
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        // 3. Masukkan data ke tabel lembar_jawab_ia05 menggunakan Query Builder
        DB::table('lembar_jawab_ia05')->insert($dataToInsert);
        
        $count = count($dataToInsert);
        $this->command->info("SUKSES! Berhasil memasukkan $count soal untuk Data Sertifikasi ID: $targetIdSertifikasi.");
        $this->command->info("Silakan refresh halaman frontend asesmen.");
    }
}
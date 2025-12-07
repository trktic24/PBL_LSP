<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SoalIa06MasterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Data dummy soal essai umum dan kunci jawaban singkatnya
        $dataSoal = [
            [
                'q' => 'Jelaskan secara singkat apa yang dimaksud dengan "Kompetensi" dalam konteks sertifikasi profesi!',
                'k' => 'Kompetensi adalah kemampuan kerja setiap individu yang mencakup aspek pengetahuan, keterampilan, dan sikap kerja yang sesuai dengan standar yang ditetapkan.',
            ],
            [
                'q' => 'Sebutkan dan jelaskan minimal 3 alat pelindung diri (APD) standar yang sering digunakan di tempat kerja!',
                'k' => '1. Helm Safety: Melindungi kepala dari benturan. 2. Sepatu Safety: Melindungi kaki dari benda tajam/berat. 3. Sarung Tangan: Melindungi tangan dari bahan kimia atau goresan.',
            ],
            [
                'q' => 'Mengapa penerapan Kesehatan dan Keselamatan Kerja (K3) sangat penting di lingkungan kerja? Jelaskan pendapat Anda!',
                'k' => 'K3 penting untuk mencegah terjadinya kecelakaan kerja dan penyakit akibat kerja, menciptakan lingkungan kerja yang aman, serta meningkatkan produktivitas.',
            ],
            [
                'q' => 'Uraikan langkah-langkah standar yang harus dilakukan jika terjadi keadaan darurat (misalnya kebakaran) di tempat kerja!',
                'k' => 'Tetap tenang, bunyikan alarm, hubungi tim tanggap darurat, evakuasi melalui jalur yang ditentukan menuju titik kumpul, dan ikuti arahan petugas.',
            ],
            [
                'q' => 'Apa perbedaan mendasar antara "Hard Skill" dan "Soft Skill"? Berikan contohnya masing-masing!',
                'k' => 'Hard skill adalah kemampuan teknis yang bisa diukur (contoh: coding, mengoperasikan mesin). Soft skill adalah kemampuan interpersonal/non-teknis (contoh: komunikasi, kepemimpinan, kerjasama tim).',
            ],
        ];

        $now = now();
        $insertData = [];

        foreach ($dataSoal as $item) {
            $insertData[] = [
                'soal_ia06' => $item['q'],
                'kunci_jawaban_ia06' => $item['k'], // Boleh null, tapi kita isi aja buat contoh
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        // Masukkan ke tabel master soal_ia06
        DB::table('soal_ia06')->insert($insertData);
        $this->command->info('Berhasil memasukkan ' . count($insertData) . ' soal master essai.');
    }
}
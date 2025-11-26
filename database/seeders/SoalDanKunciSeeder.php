<?php

namespace Database\Seeders;

use App\Models\KunciJawabanIa05;
use App\Models\SoalIa05;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SoalDanKunciSeeder extends Seeder
{
    public function run(): void
    {
        // DATA SOAL UMUM (Bukan Lorem Ipsum)
        // Kamu bisa menambah atau mengubah daftar ini sesuka hati.
        $dataSoalUmum = [
            [
                'q' => 'Apa kepanjangan dari HTML dalam dunia pemrograman web?',
                'a' => 'Hyperlinks and Text Markup Language',
                'b' => 'Hyper Text Markup Language',
                'c' => 'Home Tool Markup Language',
                'd' => 'Hyper Tool Multi Language',
                'key' => 'b',
            ],
            [
                'q' => 'Ibu kota negara Jepang adalah...',
                'a' => 'Seoul',
                'b' => 'Beijing',
                'c' => 'Tokyo',
                'd' => 'Bangkok',
                'key' => 'c',
            ],
            [
                'q' => 'Planet terbesar dalam tata surya kita adalah...',
                'a' => 'Bumi',
                'b' => 'Mars',
                'c' => 'Saturnus',
                'd' => 'Jupiter',
                'key' => 'd',
                
            ],
            [
                'q' => 'Di bawah ini yang BUKAN merupakan bahasa pemrograman adalah...',
                'a' => 'Python',
                'b' => 'Java',
                'c' => 'Microsoft Word',
                'd' => 'PHP',
                'key' => 'c',
                
            ],
            [
                'q' => 'Siapakah penemu bola lampu pijar yang paling terkenal?',
                'a' => 'Nikola Tesla',
                'b' => 'Thomas Alva Edison',
                'c' => 'Alexander Graham Bell',
                'd' => 'Albert Einstein',
                'key' => 'b',
                
            ],
            [
                'q' => 'Framework PHP yang sedang kita gunakan saat ini adalah...',
                'a' => 'CodeIgniter',
                'b' => 'Symfony',
                'c' => 'Yii',
                'd' => 'Laravel',
                'key' => 'd',
            ],
                
        ];

        // MULAI PROSES SEEDING
        // Kita gunakan transaksi agar jika ada error di tengah, tidak ada data setengah-setengah yang masuk.
        DB::transaction(function () use ($dataSoalUmum) {
            foreach ($dataSoalUmum as $data) {
                
                // 1. Buat SOAL dulu menggunakan Factory, tapi timpa datanya dengan data riil kita.
                // Factory akan otomatis mencarikan id_skema.
                $soalBaru = SoalIa05::factory()->create([
                    'soal_ia05' => $data['q'],
                    'opsi_jawaban_a' => $data['a'],
                    'opsi_jawaban_b' => $data['b'],
                    'opsi_jawaban_c' => $data['c'],
                    'opsi_jawaban_d' => $data['d'],
                ]);

                // 2. Langsung buat KUNCI JAWABAN yang terhubung ke soal yang baru dibuat.
                // Kita hubungkan pakai 'id_soal_ia05' agar pasangannya tidak tertukar.
                KunciJawabanIa05::factory()->create([
                    'id_soal_ia05' => $soalBaru->id_soal_ia05, // KUNCI PENTING DI SINI
                    'jawaban_benar' => $data['key'],
                ]);
            }
        });

        $this->command->info('Berhasil memasukkan ' . count($dataSoalUmum) . ' pasang soal umum dan kunci jawabannya!');
    }
}
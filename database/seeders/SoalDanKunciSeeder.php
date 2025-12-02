<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SoalDanKunciSeeder extends Seeder
{
    public function run(): void
    {
        // DATA SOAL UMUM & KUNCI JAWABAN (Bank Soal IA-05)
        // Nama key disesuaikan dengan nama kolom di migrasi terbaru.
        $bankSoal = [
            [
                'pertanyaan_ia05' => 'Apa kepanjangan dari HTML dalam dunia pemrograman web?',
                'opsi_a_ia05' => 'Hyperlinks and Text Markup Language',
                'opsi_b_ia05' => 'Hyper Text Markup Language',
                'opsi_c_ia05' => 'Home Tool Markup Language',
                'opsi_d_ia05' => 'Hyper Tool Multi Language',
                'jawaban_benar_ia05' => 'b',
                'penjelasan_ia05' => 'HTML adalah bahasa markah standar untuk dokumen yang dirancang untuk ditampilkan di peramban internet.',
            ],
            [
                'pertanyaan_ia05' => 'Ibu kota negara Jepang adalah...',
                'opsi_a_ia05' => 'Seoul',
                'opsi_b_ia05' => 'Beijing',
                'opsi_c_ia05' => 'Tokyo',
                'opsi_d_ia05' => 'Bangkok',
                'jawaban_benar_ia05' => 'c',
                'penjelasan_ia05' => 'Tokyo adalah ibu kota Jepang dan daerah metropolitan terpadat di dunia.',
            ],
            [
                'pertanyaan_ia05' => 'Planet terbesar dalam tata surya kita adalah...',
                'opsi_a_ia05' => 'Bumi',
                'opsi_b_ia05' => 'Mars',
                'opsi_c_ia05' => 'Saturnus',
                'opsi_d_ia05' => 'Jupiter',
                'jawaban_benar_ia05' => 'd',
                'penjelasan_ia05' => 'Jupiter adalah planet kelima dari Matahari dan merupakan planet terbesar di Tata Surya.',
            ],
            [
                'pertanyaan_ia05' => 'Di bawah ini yang BUKAN merupakan bahasa pemrograman adalah...',
                'opsi_a_ia05' => 'Python',
                'opsi_b_ia05' => 'Java',
                'opsi_c_ia05' => 'Microsoft Word',
                'opsi_d_ia05' => 'PHP',
                'jawaban_benar_ia05' => 'c',
                'penjelasan_ia05' => 'Microsoft Word adalah perangkat lunak pengolah kata (aplikasi), bukan bahasa pemrograman.',
            ],
            [
                'pertanyaan_ia05' => 'Siapakah penemu bola lampu pijar yang paling terkenal?',
                'opsi_a_ia05' => 'Nikola Tesla',
                'opsi_b_ia05' => 'Thomas Alva Edison',
                'opsi_c_ia05' => 'Alexander Graham Bell',
                'opsi_d_ia05' => 'Albert Einstein',
                'jawaban_benar_ia05' => 'b',
                'penjelasan_ia05' => 'Thomas Alva Edison sering dianggap sebagai penemu bola lampu pijar praktis pertama yang sukses secara komersial.',
            ],
            [
                'pertanyaan_ia05' => 'Framework PHP yang sedang kita gunakan saat ini adalah...',
                'opsi_a_ia05' => 'CodeIgniter',
                'opsi_b_ia05' => 'Symfony',
                'opsi_c_ia05' => 'Yii',
                'opsi_d_ia05' => 'Laravel',
                'jawaban_benar_ia05' => 'd',
                'penjelasan_ia05' => 'Kita sedang membangun aplikasi ini menggunakan framework Laravel.',
            ],
        ];

        // MULAI PROSES SEEDING
        $this->command->info('Memulai proses seeding Bank Soal IA-05...');
        $now = now();

        DB::transaction(function () use ($bankSoal, $now) {
            foreach ($bankSoal as $data) {
                // 1. Insert SOAL MASTER dan dapatkan ID-nya
                // Kita gunakan insertGetId agar langsung dapat ID soal yang baru dibuat.
                $soalId = DB::table('soal_ia05')->insertGetId([
                    'pertanyaan_ia05' => $data['pertanyaan_ia05'],
                    'opsi_a_ia05' => $data['opsi_a_ia05'],
                    'opsi_b_ia05' => $data['opsi_b_ia05'],
                    'opsi_c_ia05' => $data['opsi_c_ia05'],
                    'opsi_d_ia05' => $data['opsi_d_ia05'],
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);

                // 2. Insert KUNCI JAWABAN MASTER yang terhubung ke soal tadi
                DB::table('kunci_jawaban_ia05')->insert([
                    'id_soal_ia05' => $soalId, // HUBUNGKAN DI SINI
                    'jawaban_benar_ia05' => $data['jawaban_benar_ia05'],
                    'penjelasan_ia05' => $data['penjelasan_ia05'],
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }
        });

        $this->command->info('Berhasil memasukkan ' . count($bankSoal) . ' pasang soal dan kunci jawaban IA-05!');
    }
}
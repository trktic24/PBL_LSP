<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SoalDanKunciSeeder extends Seeder
{
    public function run(): void
    {
        // 1. AMBIL SEMUA DATA ID SKEMA
        // Asumsi nama tabelnya 'skema' dan kolom primary key-nya 'id_skema'
        // Ubah 'skema' jika nama tabelmu beda (misal: 'master_skema')
        $listSkema = DB::table('skema')->pluck('id_skema');

        // Cek validasi biar gak error kalau tabel skema kosong
        if ($listSkema->isEmpty()) {
            $this->command->warn('⚠️ Data Skema kosong! Harap jalankan SkemaSeeder terlebih dahulu.');
            return;
        }

        // 2. DATA BANK SOAL (Tetap sama)
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
            // ... (Tambahkan soal lain di sini jika ada) ...
        ];

        $this->command->info('Memulai proses seeding soal ke ' . $listSkema->count() . ' skema...');
        $now = now();

        DB::transaction(function () use ($bankSoal, $listSkema, $now) {
            
            // LOOPING 1: Iterasi setiap ID Skema
            foreach ($listSkema as $idSkema) {
                
                // LOOPING 2: Masukkan Bank Soal ke Skema tersebut
                foreach ($bankSoal as $data) {
                    
                    // Insert Soal dengan ID SKEMA
                    $soalId = DB::table('soal_ia05')->insertGetId([
                        'id_skema' => $idSkema, // <--- INI KUNCI UTAMANYA!
                        'soal_ia05' => $data['pertanyaan_ia05'],
                        'opsi_a_ia05' => $data['opsi_a_ia05'],
                        'opsi_b_ia05' => $data['opsi_b_ia05'],
                        'opsi_c_ia05' => $data['opsi_c_ia05'],
                        'opsi_d_ia05' => $data['opsi_d_ia05'],
                        'created_at' => $now,
                        'updated_at' => $now,
                    ]);

                    // Insert Kunci Jawaban (Menempel ke ID Soal yang barusan dibuat)
                    DB::table('kunci_jawaban_ia05')->insert([
                        'id_soal_ia05' => $soalId,
                        'jawaban_benar_ia05' => $data['jawaban_benar_ia05'],
                        'penjelasan_ia05' => $data['penjelasan_ia05'],
                        'created_at' => $now,
                        'updated_at' => $now,
                    ]);
                }
            }
        });

        $this->command->info('✅ Berhasil menyebarkan soal ke seluruh Skema!');
    }
}
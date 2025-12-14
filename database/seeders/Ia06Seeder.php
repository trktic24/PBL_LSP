<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SoalIa06;
use App\Models\JawabanIa06;
use App\Models\UmpanBalikIa06;
use App\Models\DataSertifikasiAsesi; // Pastikan model ini ada di project Anda

// class Ia06Seeder extends Seeder
// {
//     public function run(): void
//     {
//         // 1. Buat 5 Soal Baru
//         $daftarSoal = SoalIa06::factory()->count(5)->create();
//         $this->command->info('Berhasil membuat 5 Soal IA-06.');

//         // 2. Ambil 1 Asesi (User) yang ada di database
//         // Pastikan tabel 'data_sertifikasi_asesi' tidak kosong!
//         $asesi = DataSertifikasiAsesi::first();

//         if ($asesi) {
//             $this->command->info('Menyiapkan jawaban simulasi untuk Asesi ID: ' . $asesi->id_data_sertifikasi_asesi);

//             // 3. Loop setiap soal untuk dibuatkan jawabannya oleh si Asesi
//             foreach ($daftarSoal as $soal) {
//                 JawabanIa06::factory()->create([
//                     'id_soal_ia06' => $soal->id_soal_ia06,
//                     'id_data_sertifikasi_asesi' => $asesi->id_data_sertifikasi_asesi,
//                     'jawaban_asesi' => 'Ini adalah jawaban simulasi untuk soal: ' . substr($soal->soal_ia06, 0, 20) . '...',
//                     'pencapaian' => rand(0, 1), // Random Lulus/Gagal
//                 ]);
//             }
//             $this->command->info('Jawaban berhasil di-generate.');

//             // 4. Buat Umpan Balik untuk Asesi tersebut
//             // 4. Buat Umpan Balik untuk Asesi tersebut (Cek duplikasi dulu)
//             $cekUmpanBalik = UmpanBalikIa06::where('id_data_sertifikasi_asesi', $asesi->id_data_sertifikasi_asesi)->first();

//             if (!$cekUmpanBalik) {
//                 UmpanBalikIa06::factory()->create([
//                     'id_data_sertifikasi_asesi' => $asesi->id_data_sertifikasi_asesi,
//                     'umpan_balik' => 'Secara umum jawaban asesi cukup baik dan memenuhi standar kompetensi.',
//                 ]);
//                 $this->command->info('Umpan balik berhasil di-generate.');
//             } else {
//                 $this->command->info('Umpan balik sudah ada untuk asesi ini. Skip pembuatan umpan balik baru.');
//             }

//         } else {
//             $this->command->error('SKIP: Tidak ditemukan data asesi. Harap isi tabel data_sertifikasi_asesi terlebih dahulu.');
//         }
//     }
// }
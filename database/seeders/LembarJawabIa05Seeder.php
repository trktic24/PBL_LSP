<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\LembarJawabIA05;
use App\Models\SoalIA05; // Pastikan nama model Soal benar
use App\Models\DataSertifikasiAsesi;
use Illuminate\Support\Facades\DB;

class LembarJawabIa05Seeder extends Seeder
{
    public function run()
    {
        // 1. Ambil data induk (Asesi & Soal)
        // Kita butuh ID yang valid agar tidak error Foreign Key
        $list_asesi = DataSertifikasiAsesi::where('id_data_sertifikasi_asesi', '>', 1000)
                        ->pluck('id_data_sertifikasi_asesi');
        $list_soal  = SoalIA05::pluck('id_soal_ia05');

        // Cek apakah data induk ada
        if ($list_asesi->isEmpty() || $list_soal->isEmpty()) {
            $this->command->error("Gagal: Tabel 'data_sertifikasi_asesi' atau 'soal_ia05' masih kosong. Isi dulu data tersebut.");
            return;
        }

        $faker = \Faker\Factory::create('id_ID');

        // 2. Loop: Setiap Asesi menjawab Setiap Soal
        foreach ($list_asesi as $id_asesi) {
            foreach ($list_soal->random(5) as $id_soal) {
                
                // Tentukan status kompeten secara acak untuk simulasi
                $is_kompeten = $faker->boolean(80); // 80% kemungkinan kompeten (ya)

                LembarJawabIA05::updateOrCreate(
                    [
                        // Kunci pencarian (agar tidak duplikat)
                        'id_data_sertifikasi_asesi' => $id_asesi,
                        'id_soal_ia05'              => $id_soal,
                    ],
                    [
                        // PENTING: Sesuai gambar, kolom ini ENUM ('a','b','c','d')
                        // Jadi kita acak memilih salah satu huruf saja
                        'jawaban_asesi_ia05' => $faker->randomElement(['a', 'b', 'c', 'd']),

                        // PENTING: Sesuai gambar, kolom ini ENUM ('ya', 'tidak')
                        'pencapaian_ia05'    => $is_kompeten ? 'ya' : 'tidak',

                        // OPSI: Jika kamu SUDAH menambahkan kolom 'umpan_balik_ia05' di database,
                        // hapus tanda komentar (//) di baris bawah ini:
                        
                        // 'umpan_balik_ia05' => $is_kompeten ? 'Jawaban sudah tepat.' : 'Perlu pendalaman materi.',
                    ]
                );
            }
        }
        
        $this->command->info("Berhasil membuat dummy data Lembar Jawab IA.05!");
    }
}
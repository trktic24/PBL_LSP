<?php

namespace Database\Seeders;

use App\Models\DataSertifikasiAsesi;
use App\Models\Ak05;
use App\Models\KomentarAk05;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class KomentarAk05Seeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();

        // 1. Ambil semua ID Asesi
        $allAsesi = DataSertifikasiAsesi::pluck('id_data_sertifikasi_asesi');
        $jumlahAsesi = $allAsesi->count();
                       $this->command->info("Mengambil $jumlahAsesi data sertifikasi asesi...");

        // 2. Hitung jumlah Ak05 yang tersedia
        $jumlahAk05 = Ak05::count();

        // Jika Ak05 kurang dari jumlah asesi → buat sisanya
        if ($jumlahAk05 < $jumlahAsesi) {
            $kurang = $jumlahAsesi - $jumlahAk05;
            $this->command->info("Menambahkan $kurang data Ak05 baru...");

            Ak05::factory()->count($kurang)->create();
        }

        // 3. Ambil semua ID Ak05 lalu acak urutannya
        $allAk05 = Ak05::pluck('id_ak05')->shuffle();

        $this->command->info("Memulai seeding KomentarAk05 (1 asesi = 1 Ak05 unik)...");

        // 4. Loop untuk pairing unik
        foreach ($allAsesi as $idAsesi) {

            // Ambil Ak05 paling atas (unik)
            $idAk05 = $allAk05->shift();

            if (!$idAk05) break; // Safety check

            // Hindari duplikasi (seeder dijalankan lebih dari sekali)
            $exists = KomentarAk05::where('id_data_sertifikasi_asesi', $idAsesi)->exists();
            if ($exists) continue;

            // 5. Create KomentarAk05
            KomentarAk05::create([
                'id_data_sertifikasi_asesi' => $idAsesi,
                'id_ak05' => $idAk05,
                'rekomendasi' => $faker->randomElement(['K', 'BK']),
                'keterangan' => $faker->optional(0.8)->text(300),
            ]);
        }

        $this->command->info("Berhasil! $jumlahAsesi KomentarAk05 telah dipasangkan secara unik.");
    }
}
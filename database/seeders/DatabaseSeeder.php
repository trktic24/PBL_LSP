<?php

namespace Database\Seeders;


use Illuminate\Database\Seeder;
use App\Models\Skema;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {

        $this->call([
            // Pindahkan CategorySeeder ke atas agar data kategori tersedia lebih dulu
            CategorySeeder::class,
            
            RoleSeeder::class,
            UserSeeder::class,
            CountrySeeder::class,
            JenisTukSeeder::class,
            MasterTukSeeder::class,
            SkemaSeeder::class, // SkemaSeeder sekarang menggunakan data dari CategorySeeder
            AsesiSeeder::class,
            AsesorSeeder::class,
            TujuanAssesmenMapa01::class,
            MasterPoinSiapaAsesmenSeeder::class,
            PoinHubunganStandarSeeder::class,
            KonfirmasiOrangRelevanSeeder::class,
            StandarIndustriMapa01Seeder::class,
            PemenuhanDimensiAk06Seeder::class,
            JadwalSeeder::class,
        ]);

    }
}
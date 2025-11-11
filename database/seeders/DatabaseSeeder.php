<?php

namespace Database\Seeders;


use Illuminate\Database\Seeder;
use App\Models\Skema;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {

        $this->call([
            RoleSeeder::class,
            UserSeeder::class,
            AsesiSeeder::class,
            AsesorSeeder::class,
            TujuanAssesmenMapa01::class,
            MasterPoinSiapaAsesmenSeeder::class,
            PoinHubunganStandarSeeder::class,
            KonfirmasiOrangRelevanSeeder::class,
            StandarIndustriMapa01Seeder::class,
            PemenuhanDimensiAk06Seeder::class,
            JenisTukSeeder::class,
            JadwalSeeder::class,
            MasterTukSeeder::class,
            CountrySeeder::class,
            SkemaSeeder::class,
        ]);
    }
}

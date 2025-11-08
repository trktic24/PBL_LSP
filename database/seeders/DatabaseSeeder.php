<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([

            RoleSeeder::class,
            JenisTukSeeder::class,
            MasterTukSeeder::class,
            SkemaSeeder::class,

            UserSeeder::class,

            AsesorSeeder::class,

            JadwalSeeder::class,            

            //SoalSeederIA06::class,
            //TujuanAssesmenMapa01::class,
            //MasterPoinSiapaAsesmenSeeder::class,
            //PoinHubunganStandarSeeder::class,
            //KonfirmasiOrangRelevanSeeder::class,
            //StandarIndustriMapa01Seeder::class,
            //PemenuhanDimensiAk06Seeder::class,
            //RoleSeeder::class,
            //UserSeeder::class,
            //SkemaSeeder::class,
            //AsesorSeeder::class,
        ]);
    }
}

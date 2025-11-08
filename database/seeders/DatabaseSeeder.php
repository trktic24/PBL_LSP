<?php

namespace Database\Seeders;


use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {

        // User::factory(10)->create();
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
<<<<<<< HEAD
            SkemaSeeder::class,
=======
            JenisTukSeeder::class,
            JadwalSeeder::class,
            MasterTukSeeder::class,

>>>>>>> 1a19db39c3eb37c76a33387f70d500a859634a2a
        ]);

}
}

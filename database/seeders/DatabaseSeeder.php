<?php

namespace Database\Seeders;

<<<<<<< HEAD
// use App\Models\User; // Nggak kepake, bisa hapus
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
=======

>>>>>>> 0cc37f75099885ce4dcba4e5853fccaa3b2be4af
use Illuminate\Database\Seeder;
use App\Models\Skema;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
<<<<<<< HEAD
        // GANTI DI SINI:
        $this->call(RoleSeeder::class);
        
        // Baris ini udah bener
        \App\Models\Asesi::factory(20)->create();
        \App\Models\Skema::factory(20)->create();
        \App\Models\KelompokPekerjaan::factory(30)->create();
        \App\Models\UnitKompetensi::factory(25)->create();
        \App\Models\Elemen::factory(26)->create();
        \App\Models\KriteriaUnjukKerja::factory(20)->create();
        \App\Models\ResponApl2Ia01::factory(20)->create();
        \App\Models\JenisTuk::factory(20)->create();
        \App\Models\BuktiKelengkapan::factory(20)->create();
        \App\Models\DataSertifikasiAsesi::factory(20)->create();
        \App\Models\Asesor::factory(20)->create();
        \App\Models\Jadwal::factory(20)->create();
        \App\Models\MasterTuk::factory(20)->create();
        \App\Models\DataPekerjaanAsesi::factory(20)->create();
=======

        $this->call([
            RoleSeeder::class,
            UserSeeder::class,
            CountrySeeder::class,
            JenisTukSeeder::class,
            MasterTukSeeder::class,
            SkemaSeeder::class,
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

>>>>>>> 0cc37f75099885ce4dcba4e5853fccaa3b2be4af
    }
}
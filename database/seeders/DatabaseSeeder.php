<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Skema;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([

            // 1. Data Master (yang tidak bergantung satu sama lain)
            RoleSeeder::class,
            JenisTukSeeder::class,
            MasterTukSeeder::class,  // <-- PINDAHKAN KE ATAS
            // SkemaSeeder::class,       // <-- TAMBAHKAN INI (jika ada)

            // 2. Data User & Profil (Mungkin RoleSeeder harus dijalankan dulu)
            UserSeeder::class,
            CountrySeeder::class,
            JenisTukSeeder::class,
            MasterTukSeeder::class,
            SkemaSeeder::class,
            AsesiSeeder::class,
            AsesorSeeder::class,

            // 3. Data pendukung lainnya
            JadwalSeeder::class,     // <-- PINDAHKAN KE BAWAH
            DataSertifikasiAsesiSeeder::class,
            TujuanAssesmenMapa01::class,
            MasterPoinSiapaAsesmenSeeder::class,
            PoinHubunganStandarSeeder::class,
            KonfirmasiOrangRelevanSeeder::class,
            StandarIndustriMapa01Seeder::class,
            PemenuhanDimensiAk06Seeder::class,
            Ia07Seeder::class,

            // 4. Data Transaksi (Jalankan PALING AKHIR)
            CategorieSeeder::class,
            UnitKompetensiSeeder::class,
            KelompokPekerjaanSeeder::class,
            SkemaSeeder::class,
            JuniorWebDevSeeder::class
        ]);
    }
}

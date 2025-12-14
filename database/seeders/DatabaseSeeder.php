<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use App\Models\Asesor;
use App\Models\Skema;
use App\Models\Asesi;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. CLEANUP STORAGE
        $imageFolder = 'skema_images';
        Storage::disk('public')->deleteDirectory($imageFolder);
        Storage::disk('public')->makeDirectory($imageFolder);

        // 2. MASTER DATA (URGENT & INDEPENDENT)
        $this->call([
            RoleSeeder::class,              // 1. Roles (Admin, Asesor, Asesi)
            CategorySeeder::class,          // 2. Kategori Skema
            CountrySeeder::class,           // 3. Negara
            JenisTukSeeder::class,          // 4. Jenis TUK
            MasterTukSeeder::class,         // 5. Data TUK
            UserSeeder::class,              // 6. Users (Admin, dll)
            AdminSeeder::class,             // 7. Admin Specific
            NotifikasiSeeder::class,        // 8. Notifikasi Default
            BeritaSeeder::class,            // 9. Berita
            StrukturOrganisasiSeeder::class // 10. Struktur Organisasi
        ]);

        // 3. SKEMA & KOMPETENSI
        $this->call([
            SkemaSeeder::class,             // Creates Skema (requires Category)
        ]);
        // Note: SkemaFactory might create related models. Ideally use Seeders if available.
        // If SkemaSeeder is small, we might want factories:
        if (Skema::count() == 0) {
            Skema::factory(10)->create(); 
        }

        $this->call([
            SkemaDetailSeeder::class,       // Details for Skema
            KelompokPekerjaanSeeder::class,
            UnitKompetensiSeeder::class,    // Units
            ElemenSeeder::class,            // Elemen
            KriteriaUnjukKerjaSeeder::class // KUK
        ]);
        
        // 4. USERS (ASESOR & ASESI)
        $this->call([
            AsesorSeeder::class,    // Seeder for Asesor
            AsesiSeeder::class,     // Seeder for Asesi
            ValidatorSeeder::class, 
            PenyusunSeeder::class,
            PenyusunValidatorSeeder::class,
        ]);

        // Create dummy users if seeders didn't populate enough
        if (Asesor::count() < 5) Asesor::factory(10)->create();
        if (Asesi::count() < 5) Asesi::factory(10)->create();

        // 5. JADWAL & TRANSAKSI
        $this->call([
            JadwalSeeder::class,
            DataSertifikasiAsesiSeeder::class, // Main Transaction Table
        ]);

        // 6. INSTRUMEN ASESMEN & FORMULIR
        $this->call([
            // APL 02
            // PraasesmenSeeder::class, // (If exists)

            // AK 01 - 07
            BuktiAk01Seeder::class,
            ResponBuktiAk01Seeder::class,

            PoinAk02Seeder::class,
            SkenarioIa02Seeder::class, // IA 02 inside AK? or separate
            PoinAk03Seeder::class,
            // Ak04?
            KomentarAk05Seeder::class,
            PemenuhanDimensiAk06Seeder::class,
            FrAk07MasterSeeder::class,
            PersyaratanModifikasiAk07Seeder::class,
            // CatatanKeteranganAk07Seeder::class,
            // PoinPotensiAk07Seeder::class,

            // MAPA
            StandarIndustriMapa01Seeder::class,
            // TujuanAssesmenMapa01::class, (Uncomment if fixed)

            // IA 01 - 11
            Ia01DummySeeder::class,
            // Ia02 covered by SkenarioIa02Seeder? available in list
            // DummyIA05Seeder::class,
            LembarJawabIa05Seeder::class,
            
            Ia06Seeder::class,
            SoalIa06MasterSeeder::class,
            // KunciIa06TransaksiSeeder::class,

            Ia07Seeder::class, // Pertanyaan Lisan?
            // PertanyaanLisanSeeder::class,

            // Ia10Seeder::class,
            
            // IA 11
            \Database\Seeders\IA11\PerformaIA11Seeder::class,
            \Database\Seeders\IA11\SpesifikasiIA11Seeder::class,
        ]);

        // 7. EXTRAS
        $this->call([
            // SoalDanKunciSeeder::class,
            // KonfirmasiOrangRelevanSeeder::class,
            TenSchemesSeeder::class, // Might duplicate Skemas, check logic inside if needed
            JuniorWebDevSeeder::class, // Specific Scheme Seeder
        ]);
    }
}
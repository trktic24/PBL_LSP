<?php

namespace Database\Seeders;


use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage; // <<< IMPORT STORAGE
use App\Models\Skema;
use App\Models\Asesor;
use App\Models\Asesi;
use App\Models\Tuk; // Pastikan Model Tuk ada
use App\Models\Schedule;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 🛑 LOGIKA PEMBERSIHAN FOLDER GAMBAR SEBELUM SEEDING
        $imageFolder = 'skema_images';
        // Hapus folder lama
        Storage::disk('public')->deleteDirectory($imageFolder);
        // Buat folder baru (agar pasti ada)
        Storage::disk('public')->makeDirectory($imageFolder);

        // Panggil seeder
        $this->call([
            // Pindahkan CategorySeeder ke atas agar data kategori tersedia lebih dulu
            CategorySeeder::class,
            
            RoleSeeder::class,
            UserSeeder::class,
            CountrySeeder::class,
            JenisTukSeeder::class,
            MasterTukSeeder::class,
            SkemaSeeder::class, // SkemaSeeder akan memanggil SkemaFactory yang membuat gambar
            AsesiSeeder::class,
            AsesorSeeder::class,
            TujuanAssesmenMapa01::class,
            MasterPoinSiapaAsesmenSeeder::class,
            PoinHubunganStandarSeeder::class,
            KonfirmasiOrangRelevanSeeder::class,
            StandarIndustriMapa01Seeder::class,
            PemenuhanDimensiAk06Seeder::class,
            JadwalSeeder::class,
            StrukturOrganisasiSeeder::class,
        ]);

        // Panggil seeder Role dan User (Admin)
        $this->call([
            RoleSeeder::class, // Membuat role 1, 2, 3
            UserSeeder::class, // Membuat 1 user 'admin' (role_id 1)
            JenisTukSeeder::class, // Membuat 2 jenis_tukq
            CategorySeeder::class,
        ]);

        Asesor::factory(20)->create();
        Tuk::factory(20)->create();
        Skema::factory(20)->create();

        $this->call(SkemaDetailSeeder::class); 

        Asesi::factory(200)->create();
        Schedule::factory(50)->create();

        $this->call(DataSertifikasiAsesiSeeder::class);
    }
}
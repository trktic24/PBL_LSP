<?php

namespace Database\Seeders;


use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage; // <<< IMPORT STORAGE
use App\Models\Skema;

class DatabaseSeeder extends Seeder
{
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

    }
}
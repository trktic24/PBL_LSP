<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Asesor;
use App\Models\Asesi;
use App\Models\Tuk; // Pastikan Model Tuk ada
use App\Models\Skema;
use App\Models\Schedule;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Panggil seeder Role dan User (Admin)
        $this->call([
            RoleSeeder::class, // Membuat role 1, 2, 3
            UserSeeder::class, // Membuat 1 user 'admin' (role_id 1)
            JenisTukSeeder::class, // Membuat 2 jenis_tukq
        ]);

        
        Asesor::factory(10)->create();
        Asesi::factory(10)->create();
        Tuk::factory(10)->create();
        Skema::factory(10)->create();
        Schedule::factory(10)->create();
    }
}
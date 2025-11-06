<?php

namespace Database\Seeders;

// use App\Models\User; // Nggak kepake, bisa hapus
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // GANTI DI SINI:
        $this->call(RoleSeeder::class);
        
        // Baris ini udah bener
        \App\Models\Asesi::factory(20)->create();
        \App\Models\Skema::factory(20)->create();
        \App\Models\KelompokPekerjaan::factory(20)->create();
        \App\Models\UnitKompetensi::factory(20)->create();
        \App\Models\Elemen::factory(20)->create();
        \App\Models\KriteriaUnjukKerja::factory(20)->create();
        \App\Models\ResponApl2Ia01::factory(20)->create();
        \App\Models\JenisTuk::factory(20)->create();
        \App\Models\Tuk::factory(20)->create();
    }
}
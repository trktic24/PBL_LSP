<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\UnitKompetensi; // <-- Import model

class UnitKompetensiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Membuat 20 data Unit Kompetensi palsu
        UnitKompetensi::factory()->count(20)->create();
    }
}
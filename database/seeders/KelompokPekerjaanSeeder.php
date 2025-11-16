<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\KelompokPekerjaan; // <-- Import model

class KelompokPekerjaanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Membuat 10 data Kelompok Pekerjaan palsu
        // menggunakan Factory yang baru saja dibuat.
        KelompokPekerjaan::factory()->count(10)->create();
    }
}
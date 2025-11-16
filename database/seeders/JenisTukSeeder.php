<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\JenisTuk;

class JenisTukSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Buat 3 data dummy JenisTuk
        JenisTuk::factory()->count(2)->create();

        // Atau, Anda bisa buat data spesifik
        JenisTuk::create(['jenis_tuk' => 'Sewaktu']);
        JenisTuk::create(['jenis_tuk' => 'Tempat Kerja']);

    }
}

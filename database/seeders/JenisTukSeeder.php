<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\JenisTuk;

class JenisTUKSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Atau, Anda bisa buat data spesifik
        JenisTuk::create(['jenis_tuk' => 'Sewaktu']);    
        JenisTuk::create(['jenis_tuk' => 'Tempat Kerja']);
    }
}

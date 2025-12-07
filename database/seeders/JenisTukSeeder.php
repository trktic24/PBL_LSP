<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\JenisTuk; // Pastikan kamu udah bikin model ini

class JenisTukSeeder extends Seeder
{
    /**
     * Jalankan database seeds.
     */
    public function run(): void
    {
        JenisTuk::insert([
            ['jenis_tuk' => 'Sewaktu', 'created_at' => now(), 'updated_at' => now()],
            ['jenis_tuk' => 'Tempat Kerja', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}

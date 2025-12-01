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
        JenisTuk::insert([
            ['jenis_tuk' => 'Sewaktu', 'created_at' => now(), 'updated_at' => now()],
            ['jenis_tuk' => 'Tempat Kerja', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}

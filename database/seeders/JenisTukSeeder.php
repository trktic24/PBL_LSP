<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\JenisTuk; // Pastikan Model JenisTuk ada

class JenisTukSeeder extends Seeder
{
    public function run(): void
    {
        JenisTuk::create(['id_jenis_tuk' => 1, 'jenis_tuk' => 'Sewaktu']);
        JenisTuk::create(['id_jenis_tuk' => 2, 'jenis_tuk' => 'Tempat Kerja']);
    }
}
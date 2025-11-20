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
        // Kita pakai firstOrCreate biar aman kalau seeder-nya
        // kepanggil dua kali, datanya nggak bakal duplikat.

        JenisTuk::firstOrCreate([
            'jenis_tuk' => 'Sewaktu'
        ]);

        JenisTuk::firstOrCreate([
            'jenis_tuk' => 'Tempat Kerja'
        ]);
    }
}
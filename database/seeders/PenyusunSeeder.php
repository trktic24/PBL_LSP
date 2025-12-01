<?php

namespace Database\Seeders;

use App\Models\Penyusun;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PenyusunSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Penyusun::create([
            'penyusun' => 'Budi Santoso',
            'no_MET_penyusun' => 'MET.001.2023',
            'ttd' => null, // Placeholder
        ]);

        Penyusun::create([
            'penyusun' => 'Siti Aminah',
            'no_MET_penyusun' => 'MET.002.2023',
            'ttd' => null,
        ]);
    }
}

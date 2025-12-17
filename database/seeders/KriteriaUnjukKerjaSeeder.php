<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\KriteriaUnjukKerja;
use App\Models\Elemen;

class KriteriaUnjukKerjaSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil semua id_elemen yang sudah ada
        $elemenIds = Elemen::pluck('id_elemen')->toArray();

        if (empty($elemenIds)) {
            $this->command->info('Tidak ada data Elemen. Seeder dibatalkan.');
            return;
        }

        foreach ($elemenIds as $elemenId) {
            // Jumlah kriteria per Elemen, misal 3 per Elemen
            $jumlahPerElemen = 3;

            KriteriaUnjukKerja::factory()
                ->count($jumlahPerElemen)
                ->forElemen($elemenId) // pakai state khusus untuk id_elemen
                ->create();
        }

        $this->command->info('Semua Elemen berhasil diberikan KriteriaUnjukKerja.');
    }
}
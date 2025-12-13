<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\UnitKompetensi;
use App\Models\Elemen;
use Faker\Factory as Faker;

class ElemenSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        $units = UnitKompetensi::all();

        if ($units->isEmpty()) {
            $this->command->info('⚠️ Tidak ada Unit Kompetensi. Jalankan SkemaDetailSeeder dulu.');
            return;
        }

        foreach ($units as $unit) {

            // Buat 3–6 elemen per unit
            $jumlahElemen = rand(3, 6);

            for ($i = 1; $i <= $jumlahElemen; $i++) {
                Elemen::create([
                    'id_unit_kompetensi' => $unit->id_unit_kompetensi,
                    'elemen' => "Elemen $i - " . $faker->sentence(3),
                ]);
            }
        }

        $this->command->info('🎉 ElemenSeeder selesai menjalankan data elemen.');
    }
}
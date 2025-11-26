<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Skema;

class SkemaSeeder extends Seeder
{
    public function run(): void
    {
        // Mendefinisikan daftar nama secara eksplisit
        $uniqueNames = [
            'Junior Web Developer',
            'Ahli Digital Marketing',
            'Operator Komputer Madya',
            'Desainer Grafis Muda',
            'Network Administrator',
            'Data Analyst',
        ];

        foreach ($uniqueNames as $name) {
            // Membuat satu Skema untuk setiap nama,
            // menimpa nilai 'nama_skema' yang ada di factory
            Skema::factory()->create([
                'nama_skema' => $name,
            ]);
        }
    }
}

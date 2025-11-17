<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Categorie;      // <-- 1. Import model Categorie
use Illuminate\Support\Str;    // <-- 2. Import 'Str' untuk membuat slug

class CategorieSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 3. Buat daftar kategori yang Anda inginkan
        $categories = [
            'Pemrograman Web',
            'Digital Marketing',
            'Desain Grafis',
            'Jaringan Komputer',
            'Analisis Data',
            'Administrasi Perkantoran'
        ];

        // 4. Looping untuk membuat setiap kategori
        foreach ($categories as $categoryName) {
            Categorie::create([
                'nama_kategori' => $categoryName,
                'slug' => Str::slug($categoryName) // <-- 5. Otomatis buat slug
            ]);
        }
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Skema;
use App\Models\Category;

class SkemaCategorySeeder extends Seeder
{
    public function run(): void
    {
        // Ambil semua ID dari tabel categories
        $categoryIds = Category::pluck('id')->all();

        // Cek apakah ada kategori
        if (empty($categoryIds)) {
            // Tambahkan pesan jika tidak ada kategori, Anda harus buat seeder untuk kategori dulu!
            \Log::warning('Tidak ada data di tabel categories. Seeder dibatalkan.');
            return;
        }

        // Ambil semua data skema
        $skemas = Skema::all();

        foreach ($skemas as $skema) {
            // Pilih ID kategori secara acak dari yang sudah ada
            $randomCategoryId = $categoryIds[array_rand($categoryIds)];

            // Update kolom category_id
            $skema->category_id = $randomCategoryId;
            $skema->save();
        }
    }
}
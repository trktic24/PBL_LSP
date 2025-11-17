<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category; // <-- Pastikan ini di-import
use Illuminate\Support\Str; // <-- Import Str untuk membuat slug

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Data 12 Kategori dari gambar Anda
        $categories = [
            'Cloud Computing',
            'Data Science & AI',
            'UI/UX Design',
            'Database Administration',
            'Digital Marketing IT',
            'Business Analyst IT',
            'Mobile Development',
            'DevOps & Automation',
            'Network Engineering',
            'Cyber Security',
            'Web Development',
            'Game Development'
        ];

        // 2. Loop dan masukkan ke database
        foreach ($categories as $nama) {
            Category::create([
                'nama_kategori' => $nama,
                'slug' => Str::slug($nama)
            ]);
        }
    }
}
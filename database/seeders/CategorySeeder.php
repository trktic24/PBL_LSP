<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str; // Tambahkan ini jika menggunakan Str::slug

class CategorySeeder extends Seeder
{
    public function run(): void
    {
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
            'Game Development',
        ];

        foreach ($categories as $categoryName) {
            Category::create([
                'nama_kategori' => $categoryName,
                // Pastikan slug juga dibuat, karena di migration ada rule unique()
                'slug' => Str::slug($categoryName), 
            ]);
        }
        
        // Opsional: Anda bisa menggunakan Factory di sini untuk menambahkan data dummy
        // Category::factory(5)->create(); 
    }
}
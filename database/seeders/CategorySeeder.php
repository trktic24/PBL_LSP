<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str; // Tambahkan ini jika menggunakan Str::slug


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
            'Game Development',
        ];

        foreach ($categories as $categoryName) {
            try {
                Category::firstOrCreate(
                    ['slug' => Str::slug($categoryName)],
                    ['nama_kategori' => $categoryName]
                );
            } catch (\Illuminate\Database\UniqueConstraintViolationException $e) {
                // Ignore duplicate error as we want to ensure it exists
                continue;
            } catch (\Exception $e) {
                // Catch generic SQL errors (like integrity constraint violation 1062)
                if ($e->getCode() == 23000) {
                    continue;
                }
                throw $e;
            }
        }

        // Opsional: Anda bisa menggunakan Factory di sini untuk menambahkan data dummy
        // Category::factory(5)->create(); 
    }
}
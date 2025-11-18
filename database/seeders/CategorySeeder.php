<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category; // Pastikan model Category sudah di-import
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Jalankan database seeds.
     */
    public function run(): void
    {
        // Daftar 12 Kategori dari gambar kamu
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

        // Loop dan masukkan ke database
        foreach ($categories as $nama_kategori) {
            
            // firstOrCreate() -> Cek dulu, kalau belum ada, baru dibuat.
            // Ini aman kalau seeder-nya dijalanin berkali-kali.
            Category::firstOrCreate(
                [
                    // Buat slug otomatis dari nama
                    'slug' => Str::slug($nama_kategori) 
                ],
                [
                    'nama_kategori' => $nama_kategori
                ]
            );
        }
    }
}
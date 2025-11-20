<?php

namespace Database\Factories;

use App\Models\Skema;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class SkemaFactory extends Factory
{
    protected $model = Skema::class;

    public function definition(): array
    {
        // Ambil satu kategori secara acak yang SUDAH ADA di database
        // (Pastikan CategorySeeder dijalankan sebelum SkemaFactory)
        $category = Category::inRandomOrder()->first();

        return [
            // 1. Relasi Foreign Key (Wajib ada)
            'categorie_id' => $category ? $category->id : null, 
            // Jika kategori kosong, script akan error. Pastikan seeder jalan.

            // 2. Data Skema (Sesuai Migrasi Baru)
            // Mengganti 'kode_unit' menjadi 'nomor_skema'
            'nomor_skema' => 'SKM-' . $this->faker->unique()->numerify('###-####'), // Cth: SKM-001-2024
            
            'nama_skema' => $this->faker->jobTitle(),
            
            'deskripsi_skema' => $this->faker->paragraph(3),
            
            'harga' => $this->faker->randomElement([500000, 750000, 1000000, 1500000]),

            // 3. Path File Dummy (Sesuai struktur folder Controller)
            // Catatan: File ini tidak benar-benar ada, hanya path string di DB
            'SKKNI' => 'images/skema/skkni/dummy_skkni.pdf',
            'gambar' => 'images/skema/foto_skema/dummy_skema.jpg',
        ];
    }
}
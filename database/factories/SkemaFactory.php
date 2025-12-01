<?php

namespace Database\Factories;

use App\Models\Skema;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Category;

class SkemaFactory extends Factory
{
    /**
     * Properti statis untuk melacak urutan angka (counter)
     * agar gambar dihasilkan secara berurutan, bukan acak.
     */
    protected static $sequence = 0;

    protected $model = Skema::class;

    public function definition(): array
    {
        $categoryIds = Category::pluck('id')->all();
        if (empty($categoryIds)) {
            $categoryIds = [1];
        }

        // --- LOGIKA URUTAN GAMBAR ---
        // 1. Tambah urutan statis (1, 2, 3, ...)
        self::$sequence++;
        
        // 2. Hitung nomor gambar yang berulang dari 1 hingga 15 (siklus)
        // Jika sequence = 16, maka (16 - 1) % 15 = 0. Lalu + 1 = 1 (kembali ke skema1)
        $gambarNumber = ((self::$sequence - 1) % 15) + 1;
        
        $gambar = 'skema/skema' . $this->faker->numberBetween(1, 10) . '.jpg'; // Baris ini tidak terpakai, bisa dihapus atau diabaikan.
        
        // Baris yang Anda minta: menggunakan $gambarNumber yang berurutan
        $gambarFinal = 'skema' . $gambarNumber . '.jpg';
        // -----------------------------

        return [
            'nomor_skema' => 'J.' . $this->faker->numberBetween(100000, 999999) . '.' .
                            $this->faker->numberBetween(100, 999) . '.01',

            'nama_skema' => 'Skema Sertifikasi ' . $this->faker->randomElement([
                'Network Engineer', 'Software Developer', 'Database Administrator',
                'UI/UX Designer', 'Cybersecurity Analyst', 'Cloud Specialist',
                'AI Engineer', 'Data Scientist', 'IT Support Technician',
                'Hardware Engineer'
            ]),

            'deskripsi_skema' => $this->faker->paragraph(2),
            'SKKNI' => null,

            // INI YANG PENTING
            'gambar' => $gambarFinal, // Menggunakan variabel berurutan $gambarFinal

            'harga' => $this->faker->numberBetween(100000, 1000000),
            'category_id' => $this->faker->randomElement($categoryIds),
        ];
    }
}
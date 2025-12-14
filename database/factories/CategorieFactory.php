<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Categorie;      // <-- 1. Import model Categorie
use Illuminate\Support\Str;    // <-- 2. Import 'Str' untuk membuat slug

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Categorie>
 */
class CategorieFactory extends Factory
{
    /**
     * Tentukan model yang terhubung dengan factory ini.
     *
     * @var string
     */
    protected $model = Categorie::class; // <-- 3. Hubungkan ke model Anda

    /**
     * Definisikan status default model.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // 4. Kita buat nama kategori palsu DULUAN
        //    Kita gunakan ->unique() agar tidak ada nama yang sama
        //    yang bisa menyebabkan error 'unique' di database.
        $nama = fake()->unique()->words(2, true);

        return [
            // 5. Gunakan nama yang sudah dibuat
            'nama_kategori' => $nama,

            // 6. Buat slug secara otomatis dari $nama
            'slug' => Str::slug($nama),
        ];
    }
}

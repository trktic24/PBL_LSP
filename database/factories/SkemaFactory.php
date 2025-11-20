<?php

namespace Database\Factories;

// [PENTING] Tambahkan Model Skema dan Category
use App\Models\Skema;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Skema>
 */
class SkemaFactory extends Factory
{
    /**
     * Tentukan model yang sesuai dengan factory ini.
     */
    protected $model = Skema::class;

    /**
     * Tentukan status default model.
     */
    public function definition(): array
    {
        $nomorGambar = fake()->numberBetween(1, 12);
        return [
            // 'categorie_id' -> Mengambil ID acak dari tabel 'categories'
            // Ini akan ambil salah satu dari 12 kategori yang tadi kamu buat
            'categorie_id' => function () {
                // Ambil ID Kategori secara acak.
                // first() wajib ada kalau datanya mungkin kosong, tapi
                // karena kita panggil seeder dulu, ini pasti aman.
                return Category::inRandomOrder()->first()->id; 
            },

            // 'nomor_skema' -> Membuat kode unit unik
            'nomor_skema' => $this->faker->unique()->bothify('J.620100.###.##'),

            // 'nama_skema' -> Membuat nama skema palsu
            'nama_skema' => fake()->randomElement([
                'Junior Web Developer',
                'Ahli Digital Marketing',
                'Operator Komputer Madya',
                'Desainer Grafis Muda',
                'Network Administrator',
                'Data Analyst',
            ]),

            // 'deskripsi_skema' -> Membuat 2 paragraf deskripsi
            'deskripsi_skema' => $this->faker->paragraphs(2, true),

            // 'harga' -> Harga acak atau null
            'harga' => $this->faker->randomElement([500000, 750000, 1000000, 1250000, null]),

            // 'SKKNI' -> Nama file PDF palsu
            'SKKNI' => $this->faker->slug(3) . '_skkni.pdf',

            // 'gambar' -> Nama file gambar palsu
            'gambar' => 'skema' . $nomorGambar . '.jpg'
        ];
    }
}
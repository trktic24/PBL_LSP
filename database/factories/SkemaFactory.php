<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
// use App\Models\Skema; // Tidak perlu jika $model di-hardcode di bawah
use App\Models\Category;
use App\Models\KelompokPekerjaan; // <-- DITAMBAHKAN

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Skema>
 */
class SkemaFactory extends Factory
{
    /**
     * Tentukan model yang terhubung dengan factory ini.
     *
     * @var string
     */
    protected $model = \App\Models\Skema::class;

    /**
     * Definisikan status default model.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $nomorGambar = fake()->numberBetween(1, 12);
        return [
            // Sesuai dengan migration 'categorie_id'
            'categorie_id' => Category::inRandomOrder()->first()->id ?? Category::factory(),

            // Diubah dari 'kode_unit' menjadi 'nomor_skema' (DIUBAH)
            // Ditambahkan unique() karena ada constraint ->unique() di migration
            'nomor_skema' => fake()->unique()->numerify('J.620100.###.##'),

            // Nama skema (tetap sama)
            'nama_skema' => fake()->randomElement([
                'Junior Web Developer',
                'Ahli Digital Marketing',
                'Operator Komputer Madya',
                'Desainer Grafis Muda',
                'Network Administrator',
                'Data Analyst',
            ]),

            // Deskripsi skema (tetap sama)
            'deskripsi_skema' => fake()->paragraph(3, true),

            // Kolom 'harga' (BARU)
            'harga' => fake()->numberBetween(500000, 3000000),

            // SKKNI (tetap sama)
            'SKKNI' => '/storage/files/dummy_skkni.pdf',

            // Gambar (tetap sama)
            'gambar' => 'skema' . $nomorGambar . '.jpg',
        ];
    }
}

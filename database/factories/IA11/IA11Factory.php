<?php

namespace Database\Factories\IA11;

use App\Models\IA11\IA11;
use App\Models\IA11\SpesifikasiProdukIA11;
use App\Models\IA11\BahanProdukIA11;
use App\Models\IA11\SpesifikasiTeknisIA11;
use App\Models\IA11\PencapaianSpesifikasiIA11;
use App\Models\IA11\PencapaianPerformaIA11;
use App\Models\IA11\SpesifikasiIA11;
use App\Models\IA11\PerformaIA11;
use Illuminate\Database\Eloquent\Factories\Factory;

class IA11Factory extends Factory
{
    protected $model = IA11::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            // id_data_sertifikasi_asesi akan diisi saat dipanggil (misalnya: IA11::factory()->forDataSertifikasiAsesi(...))
            'rancangan_produk' => $this->faker->paragraph(2),
            'nama_produk' => $this->faker->word() . ' Prototype ' . $this->faker->unique()->randomNumber(3),
            'standar_industri' => $this->faker->randomElement(['SNI 01:2020', 'ISO 9001:2015', 'Standar Internal']),
            'tanggal_pengoperasian' => $this->faker->dateTimeBetween('-5 years', 'now')->format('Y-m-d'),
            'gambar_produk' => $this->faker->boolean(10) ? 'product/sample_image.png' : null,
            'rekomendasi' => $this->faker->randomElement(['kompeten', 'observasi']),
        ];
    }

    /**
     * Configure the model factory.
     * Attach 1:1, 1:M, and M:M relations after creation.
     */
    public function configure(): static
    {
        return $this->afterCreating(function (IA11 $ia11) {
            // 1. Create 1:1 Spesifikasi Produk Umum
            $ia11->spesifikasiProduk()->create([
                'dimensi_produk' => $this->faker->numberBetween(10, 500) . ' cm x ' . $this->faker->numberBetween(10, 500) . ' cm',
                'berat_produk' => $this->faker->randomFloat(2, 0.5, 50) . ' kg',
            ]);

            // 2. Create 1:M Bahan Produk (2-4 items)
            $ia11->bahanProduk()->createMany([['nama_bahan' => 'Baja Karbon ' . $this->faker->randomElement(['Rendah', 'Tinggi'])], ['nama_bahan' => 'Plastik Injeksi Model ' . $this->faker->randomNumber(1)], ['nama_bahan' => 'Komponen Listrik Standar IEC']]);

            // 3. Create 1:M Spesifikasi Teknis (2-3 items)
            $ia11->spesifikasiTeknis()->createMany([['data_teknis' => 'Daya Input Maksimal 220V/5A'], ['data_teknis' => 'Ketahanan Suhu Operasional -10°C sampai 60°C']]);

            // 4. Create M:M Pivot Data
            $this->seedPivotData($ia11);
        });
    }

    /**
     * Helper untuk membuat data pivot M:M
     */
    protected function seedPivotData(IA11 $ia11): void
    {
        // Catatan: Pastikan tabel master SpesifikasiIA11 dan PerformaIA11 sudah terisi datanya
        $spesifikasiIds = SpesifikasiIA11::pluck('id_spesifikasi_ia11')->take(4);
        $performaIds = PerformaIA11::pluck('id_performa_ia11')->take(4);

        // 4a. Pencapaian Spesifikasi
        foreach ($spesifikasiIds as $masterId) {
            PencapaianSpesifikasiIA11::create([
                'id_ia11' => $ia11->id_ia11,
                'id_spesifikasi_ia11' => $masterId,
                'hasil_reviu' => $this->faker->boolean(80) ? 1 : 0,
                'catatan_temuan' => $this->faker->boolean(30) ? $this->faker->sentence(3) : null, // 30% chance ada catatan
            ]);
        }

        // 4b. Pencapaian Performa
        foreach ($performaIds as $masterId) {
            PencapaianPerformaIA11::create([
                'id_ia11' => $ia11->id_ia11,
                'id_performa_ia11' => $masterId,
                'hasil_reviu' => $this->faker->boolean(80) ? 1 : 0,
                'catatan_temuan' => $this->faker->boolean(30) ? $this->faker->sentence(3) : null,
            ]);
        }
    }
}

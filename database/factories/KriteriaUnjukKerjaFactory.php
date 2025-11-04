<?php

namespace Database\Factories;

// 1. BENERIN NAMANYA: KriteriaUnjukKerja
use App\Models\KriteriaUnjukKerja;
use App\Models\Elemen;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\KriteriaUntukKerja>
 */
class KriteriaUnjukKerjaFactory extends Factory
{
    /**
     * Data realistis. Kita jadiin 'static' biar nggak dibuat ulang terus.
     */
    private static $kriteriaData = [
        ['no' => '1.1', 'text' => 'Konsep data dan struktur data diidentifikasi sesuai dengan konteks permasalahan'],
        ['no' => '1.2', 'text' => 'Alternatif struktur data dibandingkan kelebihan dan kekurangannya untuk konteks permasalahan yang diselesaikan'],
        ['no' => '2.1', 'text' => 'Struktur data diimplementasikan sesuai dengan bahasa pemrograman yang akan dipergunakan'],
        ['no' => '2.2', 'text' => 'Akses terhadap data dinyatakan dalam algoritma yang efisiensi sesuai bahasa pemrograman yang akan dipakai'],
        ['no' => '1.1', 'text' => 'Rancangan user interface diidentifikasi sesuai kebutuhan'],
        ['no' => '1.2', 'text' => 'Komponen user interface dialog diidentifikasi sesuai konteks rancangan proses'],
        ['no' => '1.3', 'text' => 'Urutan dari akses komponen user interface dialog dijelaskan'],
        ['no' => '1.4', 'text' => 'Simulasi (mock-up) dari aplikasi yang akan dikembangkan dibuat'],
        ['no' => '2.1', 'text' => 'Menu program sesuai dengan rancangan program diterapkan'],
        ['no' => '2.2', 'text' => 'Penempatan user interface dialog diatur secara sekuensial'],
        // ... (data lu yang lain masukin sini semua) ...
        ['no' => '3.1', 'text' => 'Perbaikan terhadap kesalahan kompilasi maupun build dirumuskan'],
        ['no' => '3.2', 'text' => 'Perbaikan dilakukan'],
    ];
    
    /**
     * 2. BENERIN NAMANYA: KriteriaUnjukKerja
     */
    protected $model = KriteriaUnjukKerja::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Pilih random dari data kriteria yang ada
        $selectedKriteria = fake()->randomElement(self::$kriteriaData);

        return [
            // 3. BENERIN LOGIC FK: Ambil Elemen yang SUDAH ADA, jangan bikin baru
            // Ini WAJIB jalan SETELAH ElemenFactory
            'id_elemen' => Elemen::all()->random()->id_elemen,
            
            'no_kriteria' => $selectedKriteria['no'],
            'kriteria' => $selectedKriteria['text'],
        ];
    }

    /**
     * State untuk kriteria dengan elemen tertentu
     */
    public function forElemen(int $elemenId): static
    {
        return $this->state(fn (array $attributes) => [
            'id_elemen' => $elemenId,
        ]);
    }

    /**
     * State untuk kriteria dengan nomor tertentu (1.1, 1.2, dst)
     */
    public function withNumber(string $number): static
    {
        // 4. BENERIN BUG: Pake array data yang LENGKAP
        $kriteria = collect(self::$kriteriaData)->firstWhere('no', $number);
        
        return $this->state(fn (array $attributes) => [
            'no_kriteria' => $kriteria['no'] ?? $number,
            'kriteria' => $kriteria['text'] ?? fake()->sentence(),
        ]);
    }
}

<?php

namespace Database\Factories;

use App\Models\IA09;
use App\Models\Asesi;
use App\Models\Asesor;
use App\Models\Skema;
use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;

class IA09Factory extends Factory
{
    /**
     * Nama model yang sesuai dengan factory.
     *
     * @var string
     */
    protected $model = IA09::class;

    /**
     * Definisikan status default model.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // PENTING: Jika Primary Key adalah 'id', ganti .id_asesi menjadi .id
        $asesiId = Asesi::inRandomOrder()->first()->id_asesi ?? 1;
        $asesorId = Asesor::inRandomOrder()->first()->id_asesor ?? 1;
        $skemaId = Skema::inRandomOrder()->first()->id_skema ?? 1;

        // Data palsu untuk JSON columns (berbentuk array PHP)
        $fakeQuestions = [
            ['id' => 1, 'pertanyaan' => $this->faker->sentence(5), 'jawaban_asesi' => $this->faker->paragraph(1), 'rekomendasi' => $this->faker->randomElement(['K', 'BK'])],
            ['id' => 2, 'pertanyaan' => $this->faker->sentence(5), 'jawaban_asesi' => $this->faker->paragraph(1), 'rekomendasi' => $this->faker->randomElement(['K', 'BK'])],
        ];

        $fakeUnits = [
            ['kode' => 'UNIT.01', 'unit' => 'Unit Kompetensi A', 'rekomendasi' => $this->faker->randomElement(['K', 'BK'])],
            ['kode' => 'UNIT.02', 'unit' => 'Unit Kompetensi B', 'rekomendasi' => $this->faker->randomElement(['K', 'BK'])],
        ];

        return [
            'asesi_id' => $asesiId,
            'asesor_id' => $asesorId,
            'skema_id' => $skemaId,
            'tanggal_asesmen' => $this->faker->dateTimeBetween('-1 year', 'now')->format('Y-m-d'),
            'tuk' => $this->faker->randomElement(['TUK Mandiri', 'TUK Sewaktu', 'TUK Tempat Kerja']),
            'rekomendasi_asesor' => $this->faker->randomElement(['K', 'BK']),
            'catatan_asesor' => $this->faker->optional()->paragraph(2),
            
            // Perbaikan: Kirim array PHP (tanpa json_encode) karena Model IA09 menggunakan $casts
            'questions' => $fakeQuestions, 
            'units' => $fakeUnits,
            
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
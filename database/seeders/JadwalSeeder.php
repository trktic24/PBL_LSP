<?php

namespace Database\Factories;

use App\Models\Jadwal; // <-- Pastikan model lo ada di sini
use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon; // <-- Kita pakai Carbon untuk mainan tanggal

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Jadwal>
 */
class JadwalFactory extends Factory
{
    /**
     * Tentukan model yang terkait dengan factory.
     *
     * @var string
     */
    protected $model = Jadwal::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // --- LOGIKA TANGGAL ---
        // 1. Kita buat tanggal pelaksanaan random (antara 1 bln lalu s/d 2 bln ke depan)
        // Ini kuncinya biar ada data "akan datang" dan "sudah lewat"
        $tanggalPelaksanaan = $this->faker->dateTimeBetween('-1 month', '+2 months');

        // 2. Kita buat tanggal tutup pendaftaran (misal, 14 hari sblm pelaksanaan)
        // Kita 'clone' biar variabel aslinya nggak berubah
        $tanggalTutup = (clone $tanggalPelaksanaan)->modify('-14 days');


        return [
            // Ambil nama skema dari data dummy $skemas di HomeController lo
            'nama_skema' => $this->faker->randomElement([
                'Junior Web Developer', 'Network Administrator', 'Database Engineer',
                'UI/UX Designer', 'Cyber Security', 'Mobile Developer',
                'Data Analyst', 'Game Developer', 'IoT Specialist',
                'Cloud Engineer', 'AI Engineer', 'Fullstack Developer'
            ]),

            // --- DATA TANGGAL YANG SUDAH KITA BUAT DI ATAS ---
            'tanggal' => $tanggalPelaksanaan,
            'tanggal_tutup' => $tanggalTutup,

            // --- Data Waktu (sesuai dummy lo) ---
            'waktu_mulai' => $this->faker->randomElement(['08:00', '09:00', '10:00']),
            'waktu_selesai' => $this->faker->randomElement(['15:00', '16:00', '17:00']),

            // --- Data Teks Lainnya (sesuai dummy lo) ---
            'tuk' => $this->faker->randomElement([
                'Politeknik Negeri Semarang', 'Lab RPL Gedung D4', 
                'Gedung Inkubator Bisnis Polines', 'Lab Jaringan Gedung D3'
            ]),
            
            'deskripsi' => $this->faker->paragraph(3),
            
            // Kita buat formatnya mirip list
            'persyaratan' => "1. " . $this->faker->sentence(5) . "\n2. " . $this->faker->sentence(4) . "\n3. " . $this->faker->sentence(6),
            
            // --- Data Angka (sesuai dummy lo) ---
            'harga' => $this->faker->randomElement([350000, 450000, 500000, 550000]),
            
            // Jangan lupa created_at dan updated_at
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
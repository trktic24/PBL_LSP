<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\DataSertifikasiAsesi;
use App\Models\Asesi;
use App\Models\Jadwal;

class DataSertifikasiAsesiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $allAsesi = Asesi::all();   // Jumlah 200
        $allJadwal = Jadwal::all(); // Jumlah 60 (10 asesor x 6)

        if ($allAsesi->isEmpty() || $allJadwal->isEmpty()) return;

        $totalJadwal = $allJadwal->count();

        // Bagi rata 200 asesi ke 60 jadwal
        foreach ($allAsesi as $index => $asesi) {
            
            $jadwal = $allJadwal[$index % $totalJadwal];

            DataSertifikasiAsesi::factory()->create([
                'id_asesi'  => $asesi->id_asesi,
                'id_jadwal' => $jadwal->id_jadwal,
            ]);
        }
    }
}
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
        $allAsesi = Asesi::all();
        $allJadwal = Jadwal::all();

        if ($allAsesi->isEmpty() || $allJadwal->isEmpty()) {
            return;
        }

        // Shuffle asesi agar pengambilannya acak
        $shuffledAsesi = $allAsesi->shuffle();
        $asesiIterator = 0;
        $totalAsesi = $shuffledAsesi->count();

        foreach ($allJadwal as $jadwal) {

            // Bersihkan data lama agar sesuai requirement (5-6 per jadwal)
            // Hati-hati: ini akan menghapus data sertifikasi lama untuk jadwal ini
            if ($jadwal->dataSertifikasiAsesi()->exists()) {
                $jadwal->dataSertifikasiAsesi()->delete();
            }

            // Tentukan jumlah asesi untuk jadwal ini (5 atau 6)
            $jumlahAsesi = rand(1, 2);

            for ($i = 0; $i < $jumlahAsesi; $i++) {
                // Jika stok asesi habis, recycle (ulang dari awal)
                if ($asesiIterator >= $totalAsesi) {
                    $asesiIterator = 0;
                    $shuffledAsesi = $allAsesi->shuffle(); // Acak ulang biar variatif
                }

                $asesi = $shuffledAsesi[$asesiIterator];
                $asesiIterator++;

                // Cek agar tidak duplikat asesi di jadwal yang sama (meski kecil kemungkinan setelah shuffle)
                // Tapi karena kita delete() di atas, aman untuk insert baru.
                // Namun, kita perlu pastikan $asesi tidak dipilih 2x dalam loop $i ini (sudah dihandle oleh iterator linear)

                DataSertifikasiAsesi::factory()->create([
                    'id_asesi' => $asesi->id_asesi,
                    'id_jadwal' => $jadwal->id_jadwal,
                ]);
            }
        }
    }
}
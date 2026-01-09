<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SoalIa05;       // Pastikan Model Soal bener
use App\Models\LembarJawabIa05; // Pastikan Model Lembar Jawab bener
use App\Models\DataSertifikasiAsesi;

class LembarJawabIa05Seeder extends Seeder
{
   public function run()
    {
        $targetIdSertifikasi = 490; 

        // Debug 1: Cek Asesi
        $asesi = \App\Models\DataSertifikasiAsesi::find($targetIdSertifikasi);
        if (!$asesi) { dd("ASESI ID $targetIdSertifikasi GAK KETEMU WIR!"); }

        // Debug 2: Cek Soal
        $daftarSoal = \App\Models\SoalIa05::all();
        dump("Jumlah Soal Ditemukan: " . $daftarSoal->count()); // <--- Debug ini

        if ($daftarSoal->count() == 0) { dd("SOAL KOSONG!"); }

        foreach ($daftarSoal as $soal) {
            // Debug 3: Cek ID Soal yang mau diinput
            dump("Mencoba input soal ID: " . $soal->id_soal_ia05); 
            
            try {
                \App\Models\LembarJawabIA05::create([ // Ganti updateOrCreate jadi create dulu biar kelihatan errornya
                    'id_data_sertifikasi_asesi' => $targetIdSertifikasi,
                    'id_soal_ia05' => $soal->id_soal_ia05,
                    'jawaban_asesi_ia05' => 'a',
                    'pencapaian_ia05' => null,
                ]);
            } catch (\Exception $e) {
                dd("ERROR WIR: " . $e->getMessage()); // <--- Ini bakal nangkep errornya
            }
        }
    }
}
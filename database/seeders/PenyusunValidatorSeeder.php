<?php

namespace Database\Seeders;

use App\Models\DataSertifikasiAsesi;
use App\Models\Penyusun;
use App\Models\PenyusunValidator;
use App\Models\Validator;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PenyusunValidatorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sertifikasi = DataSertifikasiAsesi::first();
        $penyusun = Penyusun::first();
        $validator = Validator::first();

        if ($sertifikasi && $penyusun && $validator) {
            PenyusunValidator::create([
                'id_data_sertifikasi_asesi' => $sertifikasi->id_data_sertifikasi_asesi,
                'id_penyusun' => $penyusun->id_penyusun,
                'id_validator' => $validator->id_validator,
                'tanggal_validasi' => now(),
            ]);
        }
    }
}

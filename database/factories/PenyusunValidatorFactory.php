<?php

namespace Database\Factories;

use App\Models\PenyusunValidator;
use App\Models\Penyusun; // Import Model Penyusun
use App\Models\Validator; // Import Model Validator
use App\Models\DataSertifikasiAsesi; // Import Model DataSertifikasiAsesi
use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;

class PenyusunValidatorFactory extends Factory
{
    /**
     * Nama Model yang sesuai dengan Factory ini.
     *
     * @var string
     */
    protected $model = PenyusunValidator::class;

    /**
     * Definisikan status default Model.
     *
     * @return array
     */
    public function definition()
    {
        // Ambil ID dari entitas terkait yang sudah ada.
        $penyusunId = Penyusun::inRandomOrder()->first()->id ?? null;
        $validatorId = Validator::inRandomOrder()->first()->id ?? null;
        $dataSertifikasiId = DataSertifikasiAsesi::inRandomOrder()->first()->id_data_sertifikasi_asesi ?? 1;
        
        // Tentukan apakah baris ini akan menyimpan data Penyusun atau Validator
        // Kita gunakan logika acak untuk memilih salah satunya, karena di tabel
        // perantara, satu baris biasanya hanya mereferensi salah satu (penyusun ATAU validator).
        $isPenyusun = $this->faker->boolean; 

        return [
            // id_data_sertifikasi_asesi harus ada
            'id_data_sertifikasi_asesi' => $dataSertifikasiId,
            
            // Logika untuk mengisi id_penyusun atau id_validator (salah satu akan NULL)
            'id_penyusun' => $isPenyusun ? $penyusunId : null,
            'id_validator' => $isPenyusun ? null : $validatorId,
            
            // Tanggal validasi/penyusunan
            'tanggal_validasi' => $this->faker->dateTimeBetween('-1 year', 'now'),
            
            // Kolom lain (jika ada) bisa ditambahkan di sini
            // 'kolom_lain' => $this->faker->sentence(2), 
        ];
    }
}
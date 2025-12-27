<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ListForm;
use App\Models\Skema;

class ListFormSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Ambil semua data Skema yang ada
        $dataSkema = Skema::all();

        // 2. Loop setiap skema untuk dibuatkan pengaturan form-nya
        foreach ($dataSkema as $skema) {
            
            ListForm::updateOrCreate(
                // Kondisi pengecekan (biar gak duplikat per skema)
                ['id_skema' => $skema->id_skema],
                
                // Data yang akan di-insert atau di-update (SEMUA TRUE)
                [
                    // FASE 1
                    'apl_01' => true,
                    'apl_02' => true,

                    // FASE 2
                    'fr_ia_01' => true,
                    'fr_ia_02' => true,
                    'fr_ia_03' => true,
                    'fr_ia_04' => true,
                    'fr_ia_05' => true,
                    'fr_ia_06' => true,
                    'fr_ia_07' => true,
                    'fr_ia_08' => true,
                    'fr_ia_09' => true,
                    'fr_ia_10' => true,
                    'fr_ia_11' => true,

                    // FASE 3
                    'fr_ak_01' => true,
                    'fr_ak_02' => true,
                    'fr_ak_03' => true,
                    'fr_ak_04' => true,
                    'fr_ak_05' => true,
                    'fr_ak_06' => true,

                    // FASE 4
                    'fr_mapa_01' => true,
                    'fr_mapa_02' => true,
                ]
            );
        }
    }
}
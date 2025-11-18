<?php

namespace Database\Seeders;

use App\Models\Asesi; // Model Asesi yang akan dibuat datanya
use Illuminate\Database\Seeder;

class AsesiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Membuat 5 data Asesi baru, yang masing-masing otomatis membuat 1 User baru
        Asesi::factory()->count(5)->create(); 
    }
}
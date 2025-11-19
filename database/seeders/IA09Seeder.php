<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\IA09;

class IA09Seeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        IA09::factory()->count(20)->create();
    }
}
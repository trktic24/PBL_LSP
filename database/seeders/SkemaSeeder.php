<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Skema;

class SkemaSeeder extends Seeder
{
    public function run(): void
    {
        Skema::factory()->count(10)->create();
    }
}
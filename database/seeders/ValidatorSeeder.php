<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Validator;

class ValidatorSeeder extends Seeder
{
    public function run(): void
    {
        Validator::factory()->count(10)->create();
    }
}

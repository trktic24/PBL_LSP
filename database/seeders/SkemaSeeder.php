<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Skema;

class SkemaSeeder extends Seeder
{
    public function run(): void
    {
        // Pastikan folder tujuan ada
        $storagePath = storage_path('app/public/skema');
        if (!\Illuminate\Support\Facades\File::exists($storagePath)) {
            \Illuminate\Support\Facades\File::makeDirectory($storagePath, 0755, true);
        }

        // Copy dummy images dari public/images/skema ke storage/app/public/skema
        $sourcePath = public_path('images/skema');
        if (\Illuminate\Support\Facades\File::exists($sourcePath)) {
            $files = \Illuminate\Support\Facades\File::files($sourcePath);
            foreach ($files as $file) {
                \Illuminate\Support\Facades\File::copy($file->getPathname(), $storagePath . '/' . $file->getFilename());
            }
        }

        Skema::factory(35)->create();
    }
}

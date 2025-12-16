<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class DeploymentMigratePublicAssets extends Command
{
    protected $signature = 'deployment:migrate-public-assets';
    protected $description = 'Migrate Type B (Public Dynamic) assets to storage/app/public and update DB paths.';

    public function handle()
    {
        $this->info('🚀 Starting Deployment Migration (Type B Assets)...');

        $this->migrateBerita();
        $this->migrateSkema();
        
        $this->info('🎉 Migration Complete!');
    }

    private function migrateBerita()
    {
        $this->info('--- Migrating Berita ---');
        $oldPath = public_path('images/berita');
        $newPath = storage_path('app/public/berita');

        // 1. Move Files
        if (File::exists($oldPath)) {
            if (!File::exists($newPath)) {
                File::makeDirectory($newPath, 0755, true);
            }
            
            $files = File::files($oldPath);
            foreach ($files as $file) {
                $filename = $file->getFilename();
                $target = $newPath . '/' . $filename;
                if (!File::exists($target)) {
                    File::move($file->getPathname(), $target);
                    $this->info("Moved: $filename");
                }
            }
        } else {
            $this->info("No source folder found at $oldPath. Skipping move.");
        }

        // 2. Update DB using UNPREPARED statement (Final Attempt)
        try {
            DB::unprepared("UPDATE beritas SET gambar = REPLACE(gambar, 'images/berita/', 'berita/') WHERE gambar LIKE 'images/berita/%'");
            $this->info("Updated Berita DB records via unprepared statement.");
        } catch (\Exception $e) {
            $this->error("DB Update Failed: " . $e->getMessage());
        }
    }

    private function migrateSkema()
    {
        $this->info('--- Migrating Skema ---');
        
        // A. SKKNI (PDFs)
        $oldSkkni = public_path('images/skema/skkni');
        $newSkkni = storage_path('app/public/skema/skkni');
        $this->moveFolderContent($oldSkkni, $newSkkni);
        
        // A. SKKNI (PDFs)
        $oldSkkni = public_path('images/skema/skkni');
        $newSkkni = storage_path('app/public/skema/skkni');
        $this->moveFolderContent($oldSkkni, $newSkkni);
        
        try {
            DB::unprepared("UPDATE skema SET SKKNI = REPLACE(SKKNI, 'images/skema/skkni/', 'skema/skkni/') WHERE SKKNI LIKE 'images/skema/skkni/%'");
            $this->info("Updated Skema SKKNI records.");
        } catch (\Exception $e) {
             $this->error("DB Update Failed (SKKNI): " . $e->getMessage());
        }

        // B. Foto Skema
        $oldFoto = public_path('images/skema/foto_skema');
        $newFoto = storage_path('app/public/skema/foto_skema');
        $this->moveFolderContent($oldFoto, $newFoto);

        try {
            DB::unprepared("UPDATE skema SET gambar = REPLACE(gambar, 'images/skema/foto_skema/', 'skema/foto_skema/') WHERE gambar LIKE 'images/skema/foto_skema/%'");
            $this->info("Updated Skema Gambar records.");
        } catch (\Exception $e) {
             $this->error("DB Update Failed (Foto): " . $e->getMessage());
        }
    }

    private function moveFolderContent($source, $target)
    {
        if (File::exists($source)) {
            if (!File::exists($target)) {
                File::makeDirectory($target, 0755, true);
            }
            
            $files = File::files($source);
            foreach ($files as $file) {
                $filename = $file->getFilename();
                $dest = $target . '/' . $filename;
                if (!File::exists($dest)) {
                    File::move($file->getPathname(), $dest);
                    $this->info("Moved: $filename");
                }
            }
        }
    }
}

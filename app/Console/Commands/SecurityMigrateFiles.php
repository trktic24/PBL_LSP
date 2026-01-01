<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class SecurityMigrateFiles extends Command
{
    protected $signature = 'security:migrate-files';
    protected $description = 'Strictly moves sensitive files to private vault and cleans up public directories.';

    public function handle()
    {
        $this->info("🔒 Starting Security Migration: Public -> Private Vault");
        $this->newLine();

        // GROUPS DEFINITION
        $tasks = [
            // GROUP A: public/images/
            ['source' => 'images/ttd_asesi', 'target' => 'ttd_asesi', 'type' => 'folder'],
            ['source' => 'images/tanda_tangan', 'target' => 'tanda_tangan', 'type' => 'folder'],
            ['source' => 'images/bukti_apl02', 'target' => 'bukti_apl02', 'type' => 'folder'],
            // bukti_dasar is special, handled separately
            
            // GROUP B: public/uploads/
            ['source' => 'uploads/bukti_asesi', 'target' => 'bukti_asesi', 'type' => 'folder'],

            // GROUP C: storage/app/public/
            ['source' => 'asesor_docs', 'target' => 'asesor_docs', 'is_storage_link' => true], 
        ];

        // 1. SPECIAL HANDLING: BUKTI DASAR (Mixed content)
        $this->handleBuktiDasar();

        // 2. STANDARD GROUPS
        foreach ($tasks as $task) {
            if (isset($task['is_storage_link']) && $task['is_storage_link']) {
                $this->moveStoragePublicFolder($task['source'], $task['target']);
            } else {
                $this->movePublicFolder($task['source'], $task['target']);
            }
        }

        // 3. DATABASE PATH NORMALIZATION (Crucial)
        $this->normalizeDatabasePaths();

        $this->newLine();
        $this->info("✅ Security Migration Completed.");
    }

    private function handleBuktiDasar()
    {
        $this->line("Processing 'bukti_dasar' (Mixed Content)...");
        $sourcePath = public_path('images/bukti_dasar');
        
        if (!File::exists($sourcePath)) {
            $this->warn("Skipping: $sourcePath not found.");
            return;
        }

        // 1. Rescue Checkmark
        // We move checkmark.jpg to public/images/assets/checkmark.jpg if it exists
        if (File::exists($sourcePath . '/checkmark.jpg')) {
            $assetDir = public_path('images/assets');
            if (!File::exists($assetDir)) File::makeDirectory($assetDir, 0755, true);
            
            File::move($sourcePath . '/checkmark.jpg', $assetDir . '/checkmark.jpg');
            $this->info("Rescued checkmark.jpg to images/assets/");
        }

        // 2. Move the rest (User Directories)
        $files = File::directories($sourcePath); // Only directories (users generally have folders)
        // Also check for individual files that aren't checkmark?
        $allFiles = File::files($sourcePath);
        
        foreach ($allFiles as $file) {
             $relativePath = $file->getRelativePathname();
             Storage::disk('private_docs')->put('bukti_dasar/' . $relativePath, File::get($file));
             File::delete($file);
        }
        
        foreach ($files as $dir) {
            $dirName = basename($dir);
            // Move entire directory logic
            // Since copying directory to storage is manual
            $filesInDir = File::allFiles($dir);
            foreach($filesInDir as $file) {
                 $relParams = $dirName . '/' . $file->getRelativePathname(); // 202/file.jpg
                 Storage::disk('private_docs')->put('bukti_dasar/' . $relParams, File::get($file));
            }
            File::deleteDirectory($dir);
        }

        // 3. Cleanup
        if (File::isEmptyDirectory($sourcePath)) {
            File::deleteDirectory($sourcePath);
            $this->info("Cleaned up empty folder: images/bukti_dasar");
        }
    }

    private function movePublicFolder($relativePath, $targetName)
    {
        $sourcePath = public_path($relativePath);
        if (!File::exists($sourcePath)) {
            $this->warn("Skipping Group Item: $relativePath not found.");
            return;
        }

        $this->line("Moving $relativePath -> private_docs/$targetName");
        
        $files = File::allFiles($sourcePath);
        foreach ($files as $file) {
            $rel = $file->getRelativePathname();
            Storage::disk('private_docs')->put($targetName . '/' . $rel, File::get($file));
        }

        // Cleanup
        File::deleteDirectory($sourcePath);
        $this->info("Moved & Deleted Source: $relativePath");
    }

    private function moveStoragePublicFolder($storageName, $targetName)
    {
        // Path is storage/app/public/$storageName
        $sourcePath = storage_path("app/public/$storageName");
        
        if (!File::exists($sourcePath)) {
             $this->warn("Skipping Storage Item: $storageName not found.");
             return;
        }

        $this->line("Moving storage/app/public/$storageName -> private_docs/$targetName");
        
        $files = File::allFiles($sourcePath);
        foreach ($files as $file) {
            $rel = $file->getRelativePathname();
             Storage::disk('private_docs')->put($targetName . '/' . $rel, File::get($file));
        }
        
        File::deleteDirectory($sourcePath);
        $this->info("Moved & Deleted Storage Source: $storageName");
    }

    private function normalizeDatabasePaths()
    {
        $this->info("Normalizing Database Paths...");

        // GROUP A
        // remove 'images/ttd_asesi/' -> 'ttd_asesi/' ??? Wait, if user wants 'ttd_asesi' folder, 
        // and DB has 'images/ttd_asesi/file.jpg', we just want 'ttd_asesi/file.jpg'.
        // So we strip 'images/'.
        
        // 1. Tanda Tangan Asesi
        // Note: Earlier I merged them. If DB has 'images/ttd_asesi', and I move to 'ttd_asesi', 
        // I just replace 'images/' with ''? No, 'images/ttd_asesi' -> 'ttd_asesi'.
        
        DB::table('asesi')->where('tanda_tangan', 'like', 'images/ttd_asesi/%')
            ->update(['tanda_tangan' => DB::raw("REPLACE(tanda_tangan, 'images/ttd_asesi/', 'ttd_asesi/')")]);

        DB::table('asesi')->where('tanda_tangan', 'like', 'images/tanda_tangan/%')
            ->update(['tanda_tangan' => DB::raw("REPLACE(tanda_tangan, 'images/tanda_tangan/', 'tanda_tangan/')")]);

        // 2. Bukti APL 02
        DB::table('respon_apl02_ia01')->where('bukti_asesi_apl02', 'like', 'images/bukti_apl02/%')
            ->update(['bukti_asesi_apl02' => DB::raw("REPLACE(bukti_asesi_apl02, 'images/bukti_apl02/', 'bukti_apl02/')")]);

        // 3. Bukti Dasar
        // 'images/bukti_dasar/...' -> 'bukti_dasar/...'
        DB::table('bukti_dasar')->where('bukti_dasar', 'like', 'images/bukti_dasar/%')
            ->update(['bukti_dasar' => DB::raw("REPLACE(bukti_dasar, 'images/bukti_dasar/', 'bukti_dasar/')")]);

        // GROUP B
        // 'uploads/bukti_asesi/...' -> 'bukti_asesi/...'
        DB::table('bukti_dasar')->where('bukti_dasar', 'like', 'uploads/bukti_asesi/%')
            ->update(['bukti_dasar' => DB::raw("REPLACE(bukti_dasar, 'uploads/bukti_asesi/', 'bukti_asesi/')")]);
            
        // GROUP C
        // Asesor docs currently stored as 'public/asesor_docs/...'? Or just 'asesor_docs' in some cases?
        // RegisteredUserController used: $storagePath = "public/asesor_docs/{$user->id_user}";
        // So DB has 'public/asesor_docs/...'.
        // We want 'asesor_docs/...'.
        
        // Asesor table columns?
        $asesorCols = ['ktp', 'pas_foto', 'NPWP_foto', 'rekening_foto', 'CV', 'ijazah', 'sertifikat_asesor', 'sertifikasi_kompetensi', 'tanda_tangan'];
        foreach ($asesorCols as $col) {
             DB::table('asesor')->where($col, 'like', 'public/asesor_docs/%')
                ->update([$col => DB::raw("REPLACE($col, 'public/asesor_docs/', 'asesor_docs/')")]);
                
             // Also handle 'public/asesor_files/' if legacy
              DB::table('asesor')->where($col, 'like', 'public/asesor_files/%')
                ->update([$col => DB::raw("REPLACE($col, 'public/asesor_files/', 'asesor_files/')")]);
        }
        
        $this->info("Database Paths Normalized.");
    }
}

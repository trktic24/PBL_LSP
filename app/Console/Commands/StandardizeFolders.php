<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class StandardizeFolders extends Command
{
    protected $signature = 'security:standardize-folders';
    protected $description = 'Standardizes folder names (tanda_tangan -> ttd_asesi) in private storage and DB.';

    public function handle()
    {
        $this->info("🔄 Standardizing Folders...");

        // 1. Filesystem: Merge tanda_tangan -> ttd_asesi
        $disk = Storage::disk('private_docs');
        $oldFolder = 'tanda_tangan';
        $newFolder = 'ttd_asesi';

        if ($disk->exists($oldFolder)) {
            $files = $disk->allFiles($oldFolder);
            $count = 0;
            
            // Ensure target exists
            if (!$disk->exists($newFolder)) {
                $disk->makeDirectory($newFolder);
            }

            foreach ($files as $file) {
                // $file is 'tanda_tangan/filename.jpg'
                $filename = basename($file);
                $newPath = $newFolder . '/' . $filename;
                
                if (!$disk->exists($newPath)) {
                    $disk->move($file, $newPath);
                    $count++;
                } else {
                    // Collision? Just delete old if dup, or rename. 
                    // Assuming filename uniqueness or okay to overwrite if identical content?
                    // Safe approach: Rename if collision
                     $timestamp = time();
                     $newPath = $newFolder . '/' . $timestamp . '_' . $filename;
                     $disk->move($file, $newPath);
                     $count++;
                }
            }
            
            // Delete empty old folder
            if (empty($disk->allFiles($oldFolder))) {
                $disk->deleteDirectory($oldFolder);
            }
            
            $this->info("Moved $count files from '$oldFolder' to '$newFolder'.");
        } else {
            $this->info("Folder '$oldFolder' not found. Skipping move.");
        }

        // 2. Database: Update paths
        // We need to update paths starting with 'tanda_tangan/' to 'ttd_asesi/'
        $affected = DB::table('asesi')
            ->where('tanda_tangan', 'like', 'tanda_tangan/%')
            ->update(['tanda_tangan' => DB::raw("REPLACE(tanda_tangan, 'tanda_tangan/', 'ttd_asesi/')")]);
            
        $this->info("Updated $affected Asesi records in DB.");

        $this->info("✅ Standardization Complete: All Asesi signatures are now in '$newFolder'.");
    }
}

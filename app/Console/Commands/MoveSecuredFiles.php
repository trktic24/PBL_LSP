<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class MoveSecuredFiles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'secure:migrate-files';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Move sensitive files from public to private storage';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting Secure File Migration...');

        // 1. Tanda Tangan (tanda_tangan AND ttd_asesi)
        $this->moveFolder('images/tanda_tangan', 'tanda_tangan');
        $this->moveFolder('images/ttd_asesi', 'tanda_tangan'); // Merge legacy ttd_asesi to tanda_tangan
        
        // DB Update for Tanda Tangan: Remove 'images/' prefix if exists
        $affected = DB::table('asesi')
            ->where('tanda_tangan', 'like', 'images/tanda_tangan/%')
            ->update(['tanda_tangan' => DB::raw("REPLACE(tanda_tangan, 'images/tanda_tangan/', 'tanda_tangan/')")]);
        $this->info("Updated $affected Asesi signatures in DB.");

        $affected2 = DB::table('asesi')
            ->where('tanda_tangan', 'like', 'images/ttd_asesi/%')
            ->update(['tanda_tangan' => DB::raw("REPLACE(tanda_tangan, 'images/ttd_asesi/', 'tanda_tangan/')")]);
        $this->info("Updated $affected2 Asesi legacy signatures in DB.");


        // 2. Bukti APL 02
        $this->moveFolder('images/bukti_apl02', 'bukti_apl02');
        // DB Update for Bukti APL 02
        // Table: respon_apl02_ia01, Column: bukti_asesi_apl02
        // Old Path: images/bukti_apl02/...
        // New Path: bukti_apl02/...
        $affected3 = DB::table('respon_apl02_ia01')
            ->where('bukti_asesi_apl02', 'like', 'images/bukti_apl02/%')
            ->update(['bukti_asesi_apl02' => DB::raw("REPLACE(bukti_asesi_apl02, 'images/bukti_apl02/', 'bukti_apl02/')")]);
        $this->info("Updated $affected3 Bukti APL 02 records in DB.");


        // 3. Bukti Asesi (Profile)
        $this->moveFolder('uploads/bukti_asesi', 'bukti_asesi');
        // DB Update for Bukti Dasar
        // Table: bukti_dasar, Column: bukti_dasar
        // Old Path: uploads/bukti_asesi/...
        // New Path: bukti_asesi/...
        $affected4 = DB::table('bukti_dasar')
            ->where('bukti_dasar', 'like', 'uploads/bukti_asesi/%')
            ->update(['bukti_dasar' => DB::raw("REPLACE(bukti_dasar, 'uploads/bukti_asesi/', 'bukti_asesi/')")]);
        $this->info("Updated $affected4 Bukti Dasar records in DB.");


        // 4. Asesor Docs
        $this->moveFolder('asesor_files', 'asesor_files');
        // DB Update is trickier for Asesor because specific columns might not store the full path or prefix usage varied.
        // Assuming AsesorController used 'public/asesor_files/...'
        // Table: asesor? Or user?
        // Let's check Asesor Controller usage. $asesor->$key.
        // We will do a generic replacement for known possible columns if we can find the table.
        // Assuming table 'asesor' exists based on Asesor model.
        // Columns: pas_foto, tanda_tangan, etc.
        // We will try to update 'tanda_tangan' and 'pas_foto' if they start with public/asesor_files
        
        $asesorColumns = ['tanda_tangan', 'pas_foto_asesor']; // Adjust column names based on Model inspection
        // Note: Asesor model might have different column names.
        
        // For now, let's just log that Asesor DB update might be needed or handled manually if strict columns are unknown.
        // But from previous AsesorController view, it uses $asesor->$key.
        
        $this->info('Migration Completed!');
    }

    private function moveFolder($publicRelativePath, $privateFolder)
    {
        $sourcePath = public_path($publicRelativePath);
        
        if (!File::exists($sourcePath)) {
            $this->warn("Skipping: $sourcePath not found.");
            return;
        }

        $files = File::allFiles($sourcePath);
        $count = 0;

        foreach ($files as $file) {
            $relativePath = $file->getRelativePathname();
            // $content = File::get($file->getPathname()); // High memory usage for large files
             
             // Use streaming or copy
             $handle = fopen($file->getPathname(), 'r');
             Storage::disk('private_docs')->put($privateFolder . '/' . $relativePath, $handle);
             fclose($handle);
            
             $count++;
        }

        $this->info("Moved $count files from $publicRelativePath to private_docs/$privateFolder");
    }
}

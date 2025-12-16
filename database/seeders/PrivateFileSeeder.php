<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;

class PrivateFileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('🌱 Seeding Private Files for Teammates...');

        // 1. Source File
        $sourceFile = public_path('images/default_pic.jpg');
        
        // Fallback checks
        if (!File::exists($sourceFile)) {
             $sourceFile = public_path('images/default_pic.jpg'); 
        }
        if (!File::exists($sourceFile)) {
            // Create a dummy file if absolutely nothing exists
            $sourceFile = public_path('images/dummy_generated.txt');
            File::put($sourceFile, 'Dummy content for missing file.');
        }
        
        $this->command->info("   Using source: $sourceFile");
        $dummyFileName = 'dummy_file.' . File::extension($sourceFile);

        // 2. Define Targets (Folder Name relative to private_docs root)
        $targets = [
            'tanda_tangan',      // For Asesi Tanda Tangan
            'bukti_apl02',       // For Bukti APL 02
            'bukti_asesi',       // For Bukti Asesi / Bukti Dasar
            'asesor_docs',       // For Asesor Documents
            'bukti_dasar'        // Explicitly ensuring this exists too based on refactor
        ];

        // 3. Execution: Create Folders & Copy Dummy
        foreach ($targets as $folder) {
            // Ensure directory exists in private_docs
            if (!Storage::disk('private_docs')->exists($folder)) {
                Storage::disk('private_docs')->makeDirectory($folder);
            }
            
            // Put file
            // Note: We put it at root of the folder, e.g. tanda_tangan/dummy_file.jpg
            Storage::disk('private_docs')->putFileAs(
                $folder, 
                new \Illuminate\Http\File($sourceFile), 
                $dummyFileName
            );
            
            $this->command->info("   ✅ Placed dummy in: private_uploads/$folder/$dummyFileName");
        }

        // 4. Update Database
        $this->command->info('   Updating Database Records...');

        // A. ASESI (tanda_tangan)
        // Path logic: tanda_tangan/dummy_file.jpg
        $dbPathTtd = "tanda_tangan/$dummyFileName";
        $affected = DB::table('asesi')->update(['tanda_tangan' => $dbPathTtd]);
        $this->command->info("   Updated $affected Asesi records (tanda_tangan).");

        // B. RESPON APL 02 (bukti_asesi_apl02)
        $dbPathApl02 = "bukti_apl02/$dummyFileName";
        $affected2 = DB::table('respon_apl02_ia01')->update(['bukti_asesi_apl02' => $dbPathApl02]);
        $this->command->info("   Updated $affected2 Bukti APL 02 records.");

        // C. BUKTI DASAR (bukti_dasar)
        // Note: Refactor used 'bukti_dasar' folder.
        $dbPathBukti = "bukti_dasar/$dummyFileName"; 
        $affected3 = DB::table('bukti_dasar')->update(['bukti_dasar' => $dbPathBukti]);
        $this->command->info("   Updated $affected3 Bukti Dasar records.");

        // D. ASESOR (Many columns)
        $dbPathAsesor = "asesor_docs/$dummyFileName";
        $asesorCols = [
            'ktp', 'pas_foto', 'NPWP_foto', 'rekening_foto', 
            'CV', 'ijazah', 'sertifikat_asesor', 'sertifikasi_kompetensi', 'tanda_tangan'
        ];
        
        $updateData = [];
        foreach($asesorCols as $col) {
            $updateData[$col] = $dbPathAsesor;
        }
        
        $affected4 = DB::table('asesor')->update($updateData);
        $this->command->info("   Updated $affected4 Asesor records (All Docs).");

        $this->command->info('✨ Private File Seeding Complete!');
    }
}

<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class AuditFileMigration extends Command
{
    protected $signature = 'audit:check';
    protected $description = 'Deep integrity check of secure file migration';

    private $stats = [
        'total_db' => 0,
        'secure_found' => 0,
        'missing' => 0,
        'public_leftover' => 0,
    ];

    public function handle()
    {
        $this->info('Starting Deep Integrity Check...');
        $this->newLine();

        // 1. DATABASE vs STORAGE CHECK
        $this->section('1. Database vs Storage Integrity Check');
        
        // Define scope: Table => [Columns]
        $scope = [
            'asesi' => ['tanda_tangan'],
            'respon_apl02_ia01' => ['bukti_asesi_apl02'], // Note: User migration used respon_apl02_ia01
            'bukti_dasar' => ['bukti_dasar'],
            'asesor' => [
                'pas_foto', 'NPWP_foto', 'rekening_foto', 
                'CV', 'ijazah', 'sertifikat_asesor', 
                'sertifikasi_kompetensi', 'tanda_tangan'
            ],
        ];

        foreach ($scope as $table => $columns) {
            $this->checkTable($table, $columns);
        }

        // 2. CODEBASE STATIC ANALYSIS
        $this->section('2. Codebase Static Analysis');
        $this->checkCodebase();

        // 3. ORPHANED FILE CHECK
        $this->section('3. Orphaned File Check');
        $this->checkOrphans($scope);

        // 4. FINAL SUMMARY
        $this->showSummary();
    }

    private function section($title)
    {
        $this->newLine();
        $this->line("<options=bold,reverse> $title </>");
        $this->newLine();
    }

    private function checkTable($tableName, $columns)
    {
        $this->info("Scanning table: $tableName");

        // Get all records that have non-null values in relevant columns
        // We do this column by column to be precise
        foreach ($columns as $col) {
            $records = DB::table($tableName)->whereNotNull($col)->where($col, '!=', '')->pluck($col, $this->getPrimaryKey($tableName));
            
            $bar = $this->output->createProgressBar($records->count());
            $bar->start();

            foreach ($records as $id => $path) {
                $this->stats['total_db']++;

                // A. Check Secure Storage
                // Path in DB should be relative to private_docs root
                $existsSecure = Storage::disk('private_docs')->exists($path);

                if ($existsSecure) {
                    $this->stats['secure_found']++;
                } else {
                    $this->stats['missing']++;
                    $this->newLine();
                    $this->error("MISSING: [$tableName:$id] $col => $path (Not found in private_docs)");
                }

                // B. Check Public Leftover
                // Logic: Try to reconstruct old public path.
                // This is a guess because we don't know exactly where it was, 
                // but usually it was public/images/... or public/uploads/...
                // If the path in DB is clean (e.g. 'tanda_tangan/file.png'), we check likely public locations.
                
                $likelyPublicPaths = [
                    public_path($path),
                    public_path('images/' . $path),
                    public_path('uploads/' . $path),
                    public_path('asesor_files/' . $path), // For asesor
                ];

                foreach ($likelyPublicPaths as $pubPath) {
                    if (File::exists($pubPath) && !is_dir($pubPath)) {
                        $this->stats['public_leftover']++;
                        // Only warn if it's NOT a generic asset (some avatars might be default)
                        // Assuming these are specific user uplods
                        $this->newLine();
                        $this->warn("PUBLIC LEFTOVER: [$tableName:$id] $col => $pubPath");
                        break; // Found one instance, enough to warn
                    }
                }

                $bar->advance();
            }
            $bar->finish();
            $this->newLine();
        }
    }

    private function getPrimaryKey($table)
    {
        $map = [
            'asesi' => 'id_asesi',
            'asesor' => 'id_asesor',
            'respon_apl02_ia01' => 'id_respon_apl02',
            'bukti_dasar' => 'id_bukti_dasar',
        ];
        return $map[$table] ?? 'id';
    }

    private function checkCodebase()
    {
        $path = base_path('app');
        $views = resource_path('views');
        
        $patterns = [
            'move(public_path' => 'Potential insecure upload move',
            "asset('images/ttd" => 'Potential public asset link to sensitive TTD',
            "asset('images/bukti" => 'Potential public asset link to sensitive Bukti',
            "asset('uploads/bukti" => 'Potential public asset link to sensitive Bukti',
            "Storage::url" => 'Check if SecureFileController route should be used instead',
        ];

        $files = array_merge(
            File::allFiles($path),
            File::allFiles($views)
        );

        $violations = 0;

        foreach ($files as $file) {
            $content = File::get($file->getRealPath());
            foreach ($patterns as $pattern => $msg) {
                if (strpos($content, $pattern) !== false) {
                    // Filter out allowed usages if any (simple suppression logic can be added here)
                    // For now, raw report
                     if (strpos($file->getFilename(), 'AuditFileMigration') !== false) continue; // Skip self

                    $this->warn("Potential Issue in " . $file->getRelativePathname() . ":");
                    $this->line("  Found: \"$pattern\" -> $msg");
                    $violations++;
                }
            }
        }

        if ($violations == 0) {
            $this->info("No obvious codebase violations found.");
        }
    }

    private function checkOrphans($scope)
    {
        $allDbPaths = collect();
        foreach ($scope as $table => $columns) {
            foreach ($columns as $col) {
                $paths = DB::table($table)->whereNotNull($col)->pluck($col);
                $allDbPaths = $allDbPaths->merge($paths);
            }
        }
        $allDbPaths = $allDbPaths->unique();

        $allFiles = Storage::disk('private_docs')->allFiles(); // This can be heavy if huge, but fine for now
        $orphans = 0;

        foreach ($allFiles as $file) {
            if (!$allDbPaths->contains($file)) {
                $this->line("Orphaned File: $file (Not found in DB, but exists in storage)");
                $orphans++;
            }
        }

        if ($orphans == 0) {
            $this->info("No orphaned files found.");
        } else {
            $this->warn("Found $orphans orphaned files.");
        }
    }

    private function showSummary()
    {
        $this->table(
            ['Metric', 'Count', 'Status'],
            [
                ['Total Files in DB', $this->stats['total_db'], 'INFO'],
                ['Successfully Secured', $this->stats['secure_found'], $this->stats['secure_found'] == $this->stats['total_db'] ? 'OK' : 'WARNING'],
                ['Missing / Broken Links', $this->stats['missing'], $this->stats['missing'] > 0 ? 'CRITICAL' : 'OK'],
                ['Leftover in Public', $this->stats['public_leftover'], $this->stats['public_leftover'] > 0 ? 'RISK' : 'OK'],
            ]
        );
    }
}

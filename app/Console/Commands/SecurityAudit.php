<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Http;

class SecurityAudit extends Command
{
    protected $signature = 'security:audit';
    protected $description = 'Final verification of security refactor: Orphans, DB Links, and Route Security.';

    public function handle()
    {
        $this->info("🛡️  STARTING FINAL SECURITY AUDIT  🛡️");
        $this->newLine();

        $rows = [];

        // 1. ORPHAN CHECK (Public Leftovers)
        $this->section("1. Public Directory Check");
        $publicDirs = [
            'images/ttd_asesi',
            'images/tanda_tangan',
            'images/bukti_apl02',
            'images/bukti_dasar', // We expect user folders to be gone, checkmark.jpg might be in assets now
            'uploads/bukti_asesi',
            'asesor_docs' // In public/asesor_docs via symlink? Or just public/asesor_docs folder?
        ];

        foreach ($publicDirs as $dir) {
            $path = public_path($dir);
            $status = '✅ CLEAN';
            $details = 'Directory not found (Good)';

            if (File::exists($path)) {
                if (File::isDirectory($path)) {
                    $files = File::allFiles($path);
                    if (count($files) > 0) {
                        $status = '⚠️  WARNING';
                        $details = count($files) . " files remaining in public!";
                    } else {
                        $status = '✅ CLEAN';
                        $details = 'Empty directory';
                    }
                } else {
                    // It's a file?
                    $status = '⚠️  WARNING';
                    $details = 'Path exists as file!';
                }
            } else {
                // Check storage link path for assessor_docs specially?
                // logic covers public_path('asesor_docs') which is where it would be served.
            }
            $rows[] = ["Public: $dir", $status, $details];
        }

        // 2. DATABASE LINK CHECK
        $this->section("2. Database Integrity Check (Random Sample)");
        
        // Asesi Sample
        $asesis = DB::table('asesi')->whereNotNull('tanda_tangan')->inRandomOrder()->limit(3)->get();
        foreach ($asesis as $asesi) {
            $path = $asesi->tanda_tangan;
            $exists = Storage::disk('private_docs')->exists($path);
            $rows[] = [
                "DB: Asesi TTD ({$asesi->id_asesi})", 
                $exists ? '✅ FOUND' : '❌ MISSING', 
                $path
            ];
        }

        // Asesor Sample
        $asesors = DB::table('asesor')->whereNotNull('tanda_tangan')->inRandomOrder()->limit(3)->get();
        foreach ($asesors as $asesor) {
            $path = $asesor->tanda_tangan;
            $exists = Storage::disk('private_docs')->exists($path);
            $rows[] = [
                "DB: Asesor TTD ({$asesor->id_asesor})", 
                $exists ? '✅ FOUND' : '❌ MISSING', 
                $path
            ];
        }
        
        // 3. ROUTE SECURITY CHECK
        $this->section("3. Secure Route Protection");
        
        // A. Static Check: Definitions
        $route = Route::getRoutes()->getByName('secure.file');
        if ($route) {
            $middleware = $route->gatherMiddleware();
            $hasAuth = in_array('auth', $middleware) || in_array('web', $middleware); // Usually 'auth' is explicit
            // Check specifically for 'auth' or a custom guard
            $isProtected = false;
            foreach($middleware as $m) {
                if ($m === 'auth' || str_starts_with($m, 'auth:')) $isProtected = true;
            }
            
            $rows[] = [
                "Route Definition",
                $isProtected ? '✅ PROTECTED' : '❌ OPEN',
                "Middleware: " . implode(', ', $middleware)
            ];
        } else {
            $rows[] = ["Route Definition", '❌ MISSING', "Route 'secure.file' not found"];
        }

        // B. Dynamic Check: Simulate Request
        // We try to access a dummy file path. Should get 302 (Redirect to login) or 403.
        try {
            // Need absolute URL.
            $url = route('secure.file', ['path' => 'test-integrity-check.txt']);
            // We use cURL or Http facade simply. 
            // Note: This requires the server to be running and accessible. 
            // If artisan serve is running on port 8000, we try that.
            // Using logic: if we are CLI, 'route()' generates URL based on APP_URL in .env.
            
            // To properly simulate "Without User", we just make a request. 
            // The console environment is not authenticated by default.
            
            $response = Http::withoutVerifying()->get($url);
            $status = $response->status();
            
            if ($status === 403 || $status === 401 || $status === 302 || $status === 200) {
                 // 200 is BAD if we didn't authenticate? Wait, console isn't auth'd.
                 // So 200 means PUBLIC ACCESS (FAIL).
                 if ($status === 200) {
                      // Check content. If it's the login page (HTML), it counts as 200 OK but it's actually a redirect follow? 
                      // Http::get follows redirects by default?
                      // Laravel login page returns 200.
                      $content = $response->body();
                      if (str_contains($content, 'login') || str_contains($content, 'Login')) {
                          $finalStatus = '✅ LOCKED';
                          $msg = "Redirects to Login (Status $status)";
                      } else {
                          $finalStatus = '❌ EXPOSED';
                          $msg = "Returned 200 OK (Content: " . substr($content, 0, 20) . "...)";
                      }
                 } else {
                      $finalStatus = '✅ LOCKED';
                      $msg = "Status: $status (Denied)";
                 }
            } else {
                 $finalStatus = '❓ UNKNOWN';
                 $msg = "Status: $status";
            }
            
            $rows[] = ["HTTP Access Check", $finalStatus, $msg . " URL: $url"];

        } catch (\Exception $e) {
            $rows[] = ["HTTP Access Check", '⚠️  ERROR', $e->getMessage()];
        }


        $this->table(['Check Item', 'Status', 'Details'], $rows);
        $this->newLine();
    }

    private function section($title)
    {
        $this->newLine();
        $this->info($title);
        $this->line(str_repeat('-', strlen($title)));
    }
}

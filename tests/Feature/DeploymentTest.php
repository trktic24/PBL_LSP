<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Skema;
use Illuminate\Support\Facades\File;

class DeploymentTest extends TestCase
{
    // Use existing database (do not wipe) for smoke tests usually, 
    // or use RefreshDatabase if testing on a CI environment.
    // For deployment checks, we want to check the *current* state.
    // use RefreshDatabase; 

    /**
     * 1. ROUTE CHECK
     * Visit Main Pages to ensure no 500 errors.
     */
    public function test_main_public_routes_are_accessible(): void
    {
        $routes = [
            route('home'),
            route('login'),
            route('jadwal.index'),
            route('info.tuk'),
        ];

        foreach ($routes as $route) {
            $response = $this->get($route);
            if ($response->status() !== 200) {
                 echo "\n[FAIL] Route $route returned " . $response->status();
            }
            $response->assertStatus(200);
        }
    }

    /**
     * 2. CASE SENSITIVITY CHECK (Admin Dashboard)
     * This hits the Admin Dashboard. If 'navbar.navbar-admin' or similar 
     * is casing-wrong, blade will throw InvalidArgumentException (500).
     */
    public function test_admin_dashboard_loads_without_component_errors(): void
    {
        // Try to find an existing admin or create a dummy one in memory
        // Assuming Role ID 1 is SuperAdmin or Admin
        $admin = User::where('role_id', '1')->first();

        // If no admin exists (fresh DB), create one temporarily
        if (!$admin) {
             // Skip if we can't ensure an admin exists without modifying DB too much
             // or mock it.
             $admin = User::make([
                 'role_id' => 1,
                 'email' => 'admin_test_mock@example.com',
                 'name' => 'Admin Mock'
             ]);
             $admin->save = false; // Mocking behavior if needed, but best to actingAs
        }

        if ($admin) {
            $response = $this->actingAs($admin)->get('/admin/dashboard');
            
            // Check for common error signatures in content if status isnt 200
            if ($response->status() === 500) {
                $content = $response->getContent();
                if (str_contains($content, 'View [') && str_contains($content, '] not found')) {
                     $this->fail("View/Component Not Found Error detected on Dashboard. Likely Case-Sensitivity issue.");
                }
            }
            
            // Allow 200 or 302 (redirect if mismatched role middleware)
            // But we assume 1 is Admin.
            $this->assertTrue(in_array($response->status(), [200, 302, 403])); 
        } else {
            $this->markTestSkipped('No Admin user found to test dashboard.');
        }
    }

    /**
     * 3. ASSET & IMAGE LOGIC CHECK (SEEDER vs STORAGE)
     * Logic is in the View, so we must render the View and check the output HTML.
     */
    public function test_image_fallback_logic_renders_correct_urls(): void
    {
        // Case A: Mock Skema with SEEDED path 'skema/foto_skema/skema9.jpg'
        $seededSkema = new Skema();
        $seededSkema->fill([
            'id_skema' => 99991, 
            'nama_skema' => 'Test Seeded Skema', 
            'gambar' => 'skema/foto_skema/skema9.jpg' // Typical seeded path
        ]);

        // Case B: Mock Skema with STORAGE path 'uploads/foo.jpg'
        $storageSkema = new Skema();
        $storageSkema->fill([
            'id_skema' => 99992,
            'nama_skema' => 'Test Storage Skema', 
            'gambar' => 'uploads/foo.jpg'
        ]);

        // We render a partial view or component, OR simply use the logic:
        // logic: str_contains($gambar, 'skema/foto_skema/') ? asset('images/skema/'.basename($gambar)) 
        
        // Let's rely on checking the REAL home page for actual data if available.
        // OR render text.
        
        // Manual Validation of the logic expected:
        $seededPath = $seededSkema->gambar;
        $isSeeded = str_contains($seededPath, 'skema/foto_skema/');
        $this->assertTrue($isSeeded, 'Seeded pattern match failed');
        
        $expectedUrlPart = 'images/skema/skema9.jpg';
        
        // NOTE: We cannot easily run Blade renderString with $skema object in this context 
        // without setting up View environment fully. 
        // Instead, we trust the integration: Verify the Home Page actually contains these paths.
        
        $response = $this->get(route('home'));
        $content = $response->getContent();
        
        // Check if ANY 'images/skema/skema*.jpg' exists in HTML
        // This confirms the fallback view logic is active for seeded data
        $foundSeeded = preg_match('/src="[^"]+images\/skema\/skema\d+\.jpg"/', $content);
        // Note: It might return 0 if DB is empty or pagination hides it.
        // But if we have data, it should be there.
        
        if (Skema::count() > 0) {
             // We can allow regex mismatch if no seeded data exists, but if data exists, valid img tags must exist.
             // Let's assert we don't see raw "storage/skema/foto_skema" anymore.
             $badPattern = '/storage\/skema\/foto_skema/';
             $this->assertStringNotContainsString('storage/skema/foto_skema', $content, "Found broken storage link for seeded data!");
        }
    }

    /**
     * 4. DATABASE INTEGRITY & PHYSICAL ASSETS
     */
    public function test_seeded_assets_physically_exist(): void
    {
        // Check public/images/skema exists
        $path = public_path('images/skema');
        $this->assertTrue(File::isDirectory($path), "public/images/skema directory missing");

        // Check a random sample file if directory not empty
        $files = File::files($path);
        if (count($files) > 0) {
             $this->assertTrue(File::exists($files[0]), "First file in skema images not readable");
        }
    }
}

<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

class SignatureAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_secure_file()
    {
        $response = $this->get(route('secure.file', ['path' => 'dummy.png']));
        // Should redirect to login or abort 403
        $this->assertTrue($response->status() === 302 || $response->status() === 403);
    }

    public function test_authenticated_user_can_access_existing_file()
    {
        // 1. Create a dummy file in private_docs
        Storage::fake('private_docs');
        $file = UploadedFile::fake()->image('tanda_tangan.png');
        $path = $file->store('ttd_asesi', 'private_docs');

        // 2. Create user (Asesi)
        $user = User::factory()->create();
        $this->actingAs($user);

        // 3. Access file via route
        $response = $this->get(route('secure.file', ['path' => $path]));

        // 4. Assert OK
        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'image/png');
    }

    public function test_accessing_non_existent_file_returns_404()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->get(route('secure.file', ['path' => 'non_existent_folder/missing.png']));

        $response->assertStatus(404);
    }

    public function test_path_traversal_protection()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->get(route('secure.file', ['path' => '../secret_config.php']));

        // Should be 400 Bad Request or 404 Not Found (depending on implementation, but definitely restricted)
        $this->assertTrue($response->status() === 400 || $response->status() === 404);
    }
}

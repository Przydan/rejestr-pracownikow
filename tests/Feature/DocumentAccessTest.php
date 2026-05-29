<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Document;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class DocumentAccessTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Role::create(['name' => 'administrator']);
        Role::create(['name' => 'kierownik']);
        Role::create(['name' => 'pracownik']);
    }

    public function test_manager_can_upload_document_for_employee(): void
    {
        Storage::fake('public');

        $manager = User::factory()->create();
        $manager->roles()->attach(Role::where('name', 'kierownik')->first());

        $employee = User::factory()->create();
        $employee->roles()->attach(Role::where('name', 'pracownik')->first());

        $file = UploadedFile::fake()->create('contract.pdf', 500);

        $response = $this->actingAs($manager)->post('/manager/documents', [
            'user_id' => $employee->id,
            'name' => 'Annual Contract',
            'document' => $file,
            'category' => 'Legal',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('documents', [
            'name' => 'Annual Contract',
            'user_id' => $employee->id,
            'uploaded_by' => $manager->id,
        ]);

        $document = Document::first();
        Storage::disk('public')->assertExists($document->file_path);
    }

    public function test_employee_can_only_see_their_own_documents(): void
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        $manager = User::factory()->create();

        $doc1 = Document::create([
            'name' => 'Doc 1',
            'file_path' => 'docs/1.pdf',
            'user_id' => $user1->id,
            'uploaded_by' => $manager->id,
        ]);

        $response = $this->actingAs($user2)->get('/documents');
        $response->assertDontSee('Doc 1');

        $response = $this->actingAs($user1)->get('/documents');
        $response->assertSee('Doc 1');
    }
}

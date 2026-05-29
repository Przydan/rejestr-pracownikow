<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NewsletterTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Role::create(['name' => 'administrator']);
        Role::create(['name' => 'kierownik']);
        Role::create(['name' => 'pracownik']);
    }

    public function test_everyone_can_see_newsletter(): void
    {
        $user = User::factory()->create();
        $user->roles()->attach(Role::where('name', 'pracownik')->first());

        $response = $this->actingAs($user)->get('/biuletyn');

        $response->assertStatus(200);
    }

    public function test_manager_can_create_post(): void
    {
        $manager = User::factory()->create();
        $manager->roles()->attach(Role::where('name', 'kierownik')->first());

        $response = $this->actingAs($manager)->post('/biuletyn', [
            'title' => 'Test Post',
            'content' => 'This is a test post content.',
            'category' => 'Test',
        ]);

        $response->assertRedirect('/biuletyn');
        $this->assertDatabaseHas('posts', ['title' => 'Test Post']);
    }

    public function test_employee_cannot_create_post(): void
    {
        $user = User::factory()->create();
        $user->roles()->attach(Role::where('name', 'pracownik')->first());

        $response = $this->actingAs($user)->post('/biuletyn', [
            'title' => 'Test Post',
            'content' => 'This is a test post content.',
        ]);

        $response->assertStatus(403);
    }
}

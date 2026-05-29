<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MessagingTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Role::create(['name' => 'administrator']);
        Role::create(['name' => 'kierownik']);
        Role::create(['name' => 'pracownik']);
    }

    public function test_employee_can_start_thread(): void
    {
        $user = User::factory()->create();
        $user->roles()->attach(Role::where('name', 'pracownik')->first());

        $response = $this->actingAs($user)->post('/contact', [
            'subject' => 'Help needed',
            'content' => 'I have a question about my contract.',
        ]);

        $this->assertDatabaseHas('threads', ['subject' => 'Help needed', 'user_id' => $user->id]);
        $this->assertDatabaseHas('messages', ['content' => 'I have a question about my contract.']);
    }

    public function test_manager_can_reply_to_thread(): void
    {
        $user = User::factory()->create();
        $user->roles()->attach(Role::where('name', 'pracownik')->first());

        $thread = $user->threads()->create(['subject' => 'Test']);

        $manager = User::factory()->create();
        $manager->roles()->attach(Role::where('name', 'kierownik')->first());

        $response = $this->actingAs($manager)->post("/manager/contact/{$thread->id}/reply", [
            'content' => 'I will look into it.',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('messages', ['content' => 'I will look into it.', 'user_id' => $manager->id]);
    }

    public function test_employee_cannot_see_others_threads(): void
    {
        $user1 = User::factory()->create();
        $user1->roles()->attach(Role::where('name', 'pracownik')->first());

        $user2 = User::factory()->create();
        $user2->roles()->attach(Role::where('name', 'pracownik')->first());

        $thread = $user2->threads()->create(['subject' => 'Private']);

        $response = $this->actingAs($user1)->get("/contact/{$thread->id}");

        $response->assertStatus(403);
    }
}

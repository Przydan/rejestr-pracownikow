<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SessionPersistenceTest extends TestCase
{
    use RefreshDatabase;

    public function test_session_is_preserved_when_visiting_home_page()
    {
        // 1. Setup: Create administrator role and user
        $adminRole = Role::create(['name' => 'administrator']);
        $user = User::factory()->create();
        $user->roles()->attach($adminRole);

        // 2. Log in the user
        $this->actingAs($user);

        // 3. Visit admin dashboard - should be accessible
        $this->get('/admin/dashboard')
            ->assertStatus(200);

        // 4. Visit home page - should be accessible
        $this->get('/')
            ->assertStatus(200);

        // 5. Visit admin dashboard again - should still be accessible
        $this->get('/admin/dashboard')
            ->assertStatus(200);
    }
}

<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->adminRole = Role::create(['name' => 'administrator']);
        $this->adminUser = User::factory()->create();
        $this->adminUser->roles()->attach($this->adminRole);
    }

    public function test_can_access_user_index()
    {
        $this->actingAs($this->adminUser);

        $this->get(route('admin.users.index'))
            ->assertStatus(200)
            ->assertSee('User Management');
    }

    public function test_can_access_user_create()
    {
        $this->actingAs($this->adminUser);

        $this->get(route('admin.users.create'))
            ->assertStatus(200)
            ->assertSee('Create User');
    }

    public function test_can_access_user_edit()
    {
        $user = User::factory()->create();
        $this->actingAs($this->adminUser);

        $this->get(route('admin.users.edit', $user->id))
            ->assertStatus(200)
            ->assertSee('Edit User');
    }
}

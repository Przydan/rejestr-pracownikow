<?php

namespace Tests\Feature\Admin;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Create an admin user and act as them
        $adminRole = Role::create(['name' => 'administrator']);
        $admin = User::factory()->create();
        $admin->roles()->attach($adminRole);

        $this->actingAs($admin);

        Storage::fake('public');
    }

    public function test_index_returns_users_with_roles_and_department(): void
    {
        $role = Role::factory()->create();
        $user = User::factory()->create(['department' => 'IT']);
        $user->roles()->attach($role);

        $response = $this->get(route('admin.users.index'));

        $response->assertStatus(200);
        $response->assertSee($user->name);
        $response->assertSee('IT');
        $response->assertSee($role->name);
    }

    public function test_store_creates_user_and_attaches_role(): void
    {
        $role = Role::factory()->create();
        $photo = UploadedFile::fake()->image('avatar.jpg');

        $data = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'employee_id' => 'EMP-001',
            'role_id' => $role->id,
            'photo' => $photo,
        ];

        $response = $this->post(route('admin.users.store'), $data);

        $response->assertRedirect();
        $this->assertDatabaseHas('users', [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'employee_id' => 'EMP-001',
        ]);

        $user = User::where('email', 'john@example.com')->first();
        $this->assertTrue($user->roles->contains($role));
        $this->assertNotNull($user->photo_path);
        Storage::disk('public')->assertExists($user->photo_path);
    }

    public function test_store_validation_errors(): void
    {
        $response = $this->post(route('admin.users.store'), []);

        $response->assertSessionHasErrors(['name', 'email', 'password', 'employee_id', 'role_id']);
    }

    public function test_update_updates_user_and_syncs_roles(): void
    {
        $role1 = Role::factory()->create();
        $role2 = Role::factory()->create();
        $user = User::factory()->create();
        $user->roles()->attach($role1);

        $photo = UploadedFile::fake()->image('new_avatar.jpg');

        $data = [
            'name' => 'Jane Doe',
            'email' => 'jane@example.com',
            'employee_id' => 'EMP-002',
            'role_id' => $role2->id,
            'photo' => $photo,
        ];

        $response = $this->put(route('admin.users.update', $user), $data);

        $response->assertRedirect();
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Jane Doe',
            'email' => 'jane@example.com',
            'employee_id' => 'EMP-002',
        ]);

        $this->assertTrue($user->fresh()->roles->contains($role2));
        $this->assertFalse($user->fresh()->roles->contains($role1));
    }

    public function test_destroy_deletes_user_and_files(): void
    {
        $user = User::factory()->create();
        $photo = UploadedFile::fake()->image('avatar.jpg');

        // Mock photo storage
        $path = 'employees/photos/avatar.jpg';
        Storage::disk('public')->put($path, 'content');
        $user->update(['photo_path' => $path]);

        $response = $this->delete(route('admin.users.destroy', $user));

        $response->assertRedirect();
        $this->assertModelMissing($user);
        Storage::disk('public')->assertMissing($path);
    }
}

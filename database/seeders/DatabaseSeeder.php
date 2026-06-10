<?php

namespace Database\Seeders;

use App\Models\Document;
use App\Models\Message;
use App\Models\Post;
use App\Models\Role;
use App\Models\Thread;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
        ]);

        $adminRole = Role::where('name', 'administrator')->first();
        $managerRole = Role::where('name', 'kierownik')->first();
        $employeeRole = Role::where('name', 'pracownik')->first();

        // 1. Create Admin
        $admin = User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Główny Administrator',
                'password' => Hash::make('password'),
                'employee_id' => 'EMP-0001',
                'department' => 'Administration',
                'is_active' => true,
            ]
        );
        $admin->roles()->sync([$adminRole->id]);

        // 2. Create Managers
        $managers = User::factory(3)->create()->each(function ($u) use ($managerRole) {
            $u->roles()->attach($managerRole);
        });

        // 3. Create Employees
        $employees = User::factory(20)->create()->each(function ($u) use ($employeeRole) {
            $u->roles()->attach($employeeRole);
        });

        // 4. Create Biuletyn Posts
        Post::factory(12)->create([
            'author_id' => $admin->id,
        ]);

        $aManager = $managers->first();
        Post::factory(5)->create([
            'author_id' => $aManager->id,
        ]);

//         // 5. Create Documents for employees
//         $employees->each(function ($employee) use ($admin) {
//             Document::factory(rand(1, 3))->create([
//                 'user_id' => $employee->id,
//                 'uploaded_by' => $admin->id,
//             ]);
//         });

        // 6. Create Messaging Threads
        $employees->random(10)->each(function ($employee) use ($managers) {
            $thread = Thread::factory()->create([
                'user_id' => $employee->id,
            ]);

            // Initial message from employee
            Message::factory()->create([
                'thread_id' => $thread->id,
                'user_id' => $employee->id,
            ]);

            // Potential reply from manager
            if (rand(0, 1)) {
                Message::factory()->create([
                    'thread_id' => $thread->id,
                    'user_id' => $managers->random()->id,
                ]);
            }
        });
    }
}

<?php

namespace Database\Seeders;

use App\Models\Schedule;
use App\Models\User;
use Illuminate\Database\Seeder;

class DefaultScheduleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $employees = User::whereHas('roles', fn ($q) => $q->where('name', 'pracownik'))->get();

        foreach ($employees as $user) {
            for ($i = 0; $i < 7; $i++) {
                Schedule::updateOrCreate(
                    ['user_id' => $user->id, 'day_of_week' => $i],
                    [
                        'start_time' => '08:00',
                        'end_time' => '16:00',
                        'is_working_day' => in_array($i, [1, 2, 3, 4, 5]), // Mon-Fri
                    ]
                );
            }
        }
    }
}

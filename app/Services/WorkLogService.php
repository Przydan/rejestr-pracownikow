<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Validation\ValidationException;

class WorkLogService
{
    public function validateAgainstSchedule(User $user, string $date, ?string $startTime, ?string $endTime): void
    {
        $carbonDate = Carbon::parse($date);
        $dayOfWeek = $carbonDate->dayOfWeek; // 0 (Sun) to 6 (Sat)

        $schedule = $user->schedules()->where('day_of_week', $dayOfWeek)->first();

        if (! $schedule || ! $schedule->is_working_day) {
            throw ValidationException::withMessages([
                'date' => 'Ten dzień jest oznaczony jako wolny w grafiku pracownika.',
            ]);
        }

        if ($startTime && $endTime) {
            $start = Carbon::parse($startTime);
            $end = Carbon::parse($endTime);
            $schedStart = Carbon::parse($schedule->start_time);
            $schedEnd = Carbon::parse($schedule->end_time);

            if ($start->lt($schedStart) || $end->gt($schedEnd)) {
                throw ValidationException::withMessages([
                    'start_time' => "Godziny pracy muszą mieścić się w grafiku ({$schedule->start_time} - {$schedule->end_time}).",
                ]);
            }
        }
    }
}

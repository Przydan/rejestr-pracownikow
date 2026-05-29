<?php

declare(strict_types=1);

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ScheduleController extends Controller
{
    public function index(Request $request): View
    {
        $users = User::whereHas('roles', fn ($q) => $q->where('name', 'pracownik'))
            ->when($request->search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%");
            })
            ->get();

        $selectedUser = null;
        $schedules = [];

        if ($request->user_id) {
            $selectedUser = User::with('schedules')->findOrFail($request->user_id);

            // Ensure we have 7 days in the collection
            $schedules = collect(range(0, 6))->map(function ($day) use ($selectedUser) {
                return $selectedUser->schedules->firstWhere('day_of_week', $day) ?? new Schedule([
                    'user_id' => $selectedUser->id,
                    'day_of_week' => $day,
                    'start_time' => '08:00',
                    'end_time' => '16:00',
                    'is_working_day' => in_array($day, [1, 2, 3, 4, 5]), // Mon-Fri
                ]);
            });
        }

        return view('manager.schedules.index', compact('users', 'schedules', 'selectedUser'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'schedules' => 'required|array|size:7',
            'schedules.*.start_time' => 'required|date_format:H:i',
            'schedules.*.end_time' => 'required|date_format:H:i|after:schedules.*.start_time',
            'schedules.*.is_working_day' => 'nullable|boolean',
        ]);

        foreach ($validated['schedules'] as $day => $data) {
            Schedule::updateOrCreate(
                ['user_id' => $validated['user_id'], 'day_of_week' => $day],
                [
                    'start_time' => $data['start_time'],
                    'end_time' => $data['end_time'],
                    'is_working_day' => isset($data['is_working_day']) ? (bool) $data['is_working_day'] : false,
                ]
            );
        }

        return back()->with('success', 'Grafik został zaktualizowany.');
    }
}

<?php

declare(strict_types=1);

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\WorkLog;
use App\Services\WorkLogService;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class WorkLogController extends Controller
{
    use AuthorizesRequests;

    public function __construct(protected WorkLogService $workLogService) {}

    public function index(Request $request): View
    {
        $users = User::whereHas('roles', fn ($q) => $q->where('name', 'pracownik'))
            ->when($request->search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%");
            })
            ->get();

        $selectedUser = null;
        $workLogs = [];

        if ($request->user_id) {
            $selectedUser = User::findOrFail($request->user_id);
            $month = $request->get('month', date('m'));
            $year = $request->get('year', date('Y'));

            $workLogs = $selectedUser->workLogs()
                ->whereMonth('date', $month)
                ->whereYear('date', $year)
                ->with(['supervisor', 'comments.user'])
                ->orderBy('date', 'desc')
                ->get();
        }

        return view('manager.work-logs.index', compact('users', 'workLogs', 'selectedUser'));
    }

    public function store(Request $request): RedirectResponse
    {
        $this->authorize('create', WorkLog::class);

        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'description' => 'nullable|string',
        ]);

        $user = User::findOrFail($validated['user_id']);

        // Validate against schedule
        $this->workLogService->validateAgainstSchedule(
            $user,
            $validated['date'],
            $validated['start_time'],
            $validated['end_time']
        );

        // Calculate hours
        $start = Carbon::parse($validated['start_time']);
        $end = Carbon::parse($validated['end_time']);
        $hours = $start->diffInMinutes($end) / 60;

        WorkLog::updateOrCreate(
            ['user_id' => $validated['user_id'], 'date' => $validated['date']],
            [
                'start_time' => $validated['start_time'],
                'end_time' => $validated['end_time'],
                'hours' => $hours,
                'description' => $validated['description'],
                'added_by' => auth()->id(),
            ]
        );

        return back()->with('success', 'Godziny zostały zapisane.');
    }

    public function comment(Request $request, WorkLog $workLog): RedirectResponse
    {
        $this->authorize('comment', $workLog);

        $validated = $request->validate([
            'content' => 'required|string',
        ]);

        $workLog->comments()->create([
            'user_id' => auth()->id(),
            'content' => $validated['content'],
        ]);

        return back()->with('success', 'Komentarz został dodany.');
    }
}

<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\WorkLog;
use App\Services\WorkLogService;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserWorkLogController extends Controller
{
    use AuthorizesRequests;

    public function __construct(protected WorkLogService $workLogService) {}

    public function index(Request $request): View
    {
        $month = $request->get('month', date('m'));
        $year = $request->get('year', date('Y'));

        $workLogs = auth()->user()->workLogs()
            ->whereMonth('date', $month)
            ->whereYear('date', $year)
            ->with(['supervisor', 'comments.user'])
            ->orderBy('date', 'desc')
            ->get();

        return view('user.work-logs.index', compact('workLogs'));
    }

    public function store(Request $request): RedirectResponse
    {
        $this->authorize('create', WorkLog::class);

        $validated = $request->validate([
            'date' => 'required|date|before_or_equal:today',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'description' => 'nullable|string',
        ]);

        // Validate against schedule
        $this->workLogService->validateAgainstSchedule(
            auth()->user(),
            $validated['date'],
            $validated['start_time'],
            $validated['end_time']
        );

        // Calculate hours
        $start = Carbon::parse($validated['start_time']);
        $end = Carbon::parse($validated['end_time']);
        $hours = $start->diffInMinutes($end) / 60;

        // Check if log already exists
        $existing = auth()->user()->workLogs()->where('date', $validated['date'])->first();
        if ($existing) {
            return back()->with('error', 'Wpis dla tej daty już istnieje. Skontaktuj się z kierownikiem w celu zmiany.');
        }

        auth()->user()->workLogs()->create([
            'date' => $validated['date'],
            'start_time' => $validated['start_time'],
            'end_time' => $validated['end_time'],
            'hours' => $hours,
            'description' => $validated['description'],
            'added_by' => auth()->id(),
        ]);

        return back()->with('success', 'Godziny zostały dodane.');
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

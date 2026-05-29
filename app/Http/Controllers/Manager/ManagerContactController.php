<?php

declare(strict_types=1);

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Http\Requests\Contact\ReplyThreadRequest;
use App\Http\Requests\Contact\StoreThreadRequest;
use App\Models\Message;
use App\Models\Thread;
use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ManagerContactController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $request): View
    {
        $users = User::whereHas('roles', fn ($q) => $q->where('name', 'pracownik'))
            ->when($request->search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->get();

        $selectedUser = null;
        $query = Thread::with(['user', 'messages' => fn ($q) => $q->latest()->limit(1)]);

        if ($request->user_id) {
            $selectedUser = User::findOrFail($request->user_id);
            $query->where('user_id', $selectedUser->id);
        }

        // Global Search
        if ($request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('subject', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($qu) use ($search) {
                        $qu->where('name', 'like', "%{$search}%");
                    })
                    ->orWhereHas('messages', function ($qm) use ($search) {
                        $qm->where('content', 'like', "%{$search}%");
                    });
            });
        }

        // Filter: Status (Default: Open)
        $status = $request->get('status', 'open');
        if ($status !== 'all') {
            $query->where('status', $status);
        }

        // Filter: Unread
        if ($request->filter === 'unread') {
            $query->whereHas('messages', function ($q) {
                $q->where('is_read', false)->where('user_id', '!=', auth()->id());
            });
        }

        // Sorting
        if ($request->sort === 'waiting_longest') {
            $query->whereHas('messages', function ($q) {
                $q->where('user_id', '!=', auth()->id())
                    ->whereRaw('id IN (SELECT MAX(id) FROM messages GROUP BY thread_id)');
            })->orderBy('last_message_at', 'asc');
        } else {
            $query->orderBy('last_message_at', 'desc');
        }

        $threads = $query->get();

        return view('manager.contact.index', compact('users', 'threads', 'selectedUser'));
    }

    public function markAllRead(): RedirectResponse
    {
        Message::where('is_read', false)
            ->where('user_id', '!=', auth()->id())
            ->update(['is_read' => true]);

        return back()->with('success', 'Wszystkie wiadomości zostały oznaczone jako przeczytane.');
    }

    public function open(Thread $thread): RedirectResponse
    {
        $this->authorize('update', $thread);

        $thread->update(['status' => 'open']);

        return back()->with('success', 'Wątek został ponownie otwarty.');
    }

    public function store(StoreThreadRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $thread = Thread::create([
            'user_id' => $request->user_id ?? auth()->id(), // For manager, user_id is explicitly sent
            'subject' => $validated['subject'],
        ]);

        $thread->messages()->create([
            'user_id' => auth()->id(),
            'content' => $validated['content'],
            'is_read' => true,
        ]);

        return redirect()->route('manager.contact.show', $thread)->with('success', 'Wątek został utworzony.');
    }

    public function show(Thread $thread): View
    {
        $this->authorize('view', $thread);

        // Mark messages from the employee as read
        $thread->messages()->where('user_id', $thread->user_id)->update(['is_read' => true]);

        $thread->load('messages.user', 'user');

        $users = User::whereHas('roles', fn ($q) => $q->where('name', 'pracownik'))->get();

        return view('manager.contact.show', compact('thread', 'users'));
    }

    public function reply(ReplyThreadRequest $request, Thread $thread): RedirectResponse
    {
        $this->authorize('reply', $thread);

        $thread->messages()->create([
            'user_id' => auth()->id(),
            'content' => $request->validated()['content'],
        ]);

        return back()->with('success', 'Odpowiedź została wysłana.');
    }

    public function close(Thread $thread): RedirectResponse
    {
        $this->authorize('update', $thread);

        $thread->update(['status' => 'closed']);

        return back()->with('success', 'Wątek został zamknięty.');
    }

    public function destroy(Thread $thread): RedirectResponse
    {
        $this->authorize('delete', $thread);

        $thread->delete();

        return redirect()->route('manager.contact.index')->with('success', 'Wątek został usunięty.');
    }
}

<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\Contact\ReplyThreadRequest;
use App\Http\Requests\Contact\StoreThreadRequest;
use App\Models\Thread;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ContactController extends Controller
{
    use AuthorizesRequests;

    public function index(): View
    {
        // Eager load messages to prevent N+1 in the view (for the 'unread' check)
        $threads = auth()->user()->threads()->with(['messages' => function ($query) {
            $query->latest();
        }])->latest()->get();

        return view('contact.index', compact('threads'));
    }

    public function create(): View
    {
        return view('contact.create');
    }

    public function store(StoreThreadRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $thread = auth()->user()->threads()->create([
            'subject' => $validated['subject'],
        ]);

        $thread->messages()->create([
            'user_id' => auth()->id(),
            'content' => $validated['content'],
            'is_read' => true,
        ]);

        return redirect()->route('contact.show', $thread)->with('success', 'Wiadomość została wysłana.');
    }

    public function show(Thread $thread): View
    {
        $this->authorize('view', $thread);

        // Mark messages from others as read
        $thread->messages()->where('user_id', '!=', auth()->id())->update(['is_read' => true]);

        $thread->load('messages.user');

        return view('contact.show', compact('thread'));
    }

    public function reply(ReplyThreadRequest $request, Thread $thread): RedirectResponse
    {
        $this->authorize('reply', $thread);

        $validated = $request->validated();

        $thread->messages()->create([
            'user_id' => auth()->id(),
            'content' => $validated['content'],
        ]);

        return back()->with('success', 'Odpowiedź została wysłana.');
    }
}

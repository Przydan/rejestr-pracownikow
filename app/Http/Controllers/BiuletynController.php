<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\Biuletyn\StorePostRequest;
use App\Models\Post;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class BiuletynController extends Controller
{
    use AuthorizesRequests;

    public function index(): View
    {
        $posts = Post::with('author')->latest()->paginate(10);

        return view('biuletyn.index', compact('posts'));
    }

    public function create(): View
    {
        $this->authorize('create', Post::class);

        return view('biuletyn.create');
    }

    public function store(StorePostRequest $request): RedirectResponse
    {
        // Authorization handled in StorePostRequest
        $request->user()->posts()->create($request->validated());

        return redirect()->route('biuletyn.index')->with('success', 'Wpis został dodany do biuletynu.');
    }

    public function show(Post $biuletyn): View
    {
        return view('biuletyn.show', ['post' => $biuletyn]);
    }

    public function edit(Post $biuletyn): View
    {
        $this->authorize('update', $biuletyn);

        return view('biuletyn.edit', ['post' => $biuletyn]);
    }

    public function update(StorePostRequest $request, Post $biuletyn): RedirectResponse
    {
        $this->authorize('update', $biuletyn);

        $biuletyn->update($request->validated());

        return redirect()->route('biuletyn.index')->with('success', 'Wpis został zaktualizowany.');
    }

    public function destroy(Post $biuletyn): RedirectResponse
    {
        $this->authorize('delete', $biuletyn);

        $biuletyn->delete();

        return redirect()->route('biuletyn.index')->with('success', 'Wpis został usunięty.');
    }
}

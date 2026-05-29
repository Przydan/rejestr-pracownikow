<?php

declare(strict_types=1);

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Http\Requests\Document\StoreDocumentRequest;
use App\Models\Document;
use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ManagerDocumentController extends Controller
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
        $documents = [];

        if ($request->user_id) {
            $selectedUser = User::findOrFail($request->user_id);
            // Eager load user to prevent N+1 in card view
            $documents = $selectedUser->documents()->with('user')->latest()->get();
        } else {
            $documents = Document::with('user')->latest()->get();
        }

        return view('manager.documents.index', compact('users', 'documents', 'selectedUser'));
    }

    public function store(StoreDocumentRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $filePath = $request->file('document')->store('documents', 'public');

        Document::create([
            'name' => $validated['name'],
            'file_path' => $filePath,
            'user_id' => $validated['user_id'],
            'category' => $validated['category'],
            'uploaded_by' => auth()->id(),
        ]);

        return back()->with('success', 'Dokument został dodany.');
    }

    public function destroy(Document $document): RedirectResponse
    {
        $this->authorize('delete', $document);

        Storage::disk('public')->delete($document->file_path);
        $document->delete();

        return back()->with('success', 'Dokument został usunięty.');
    }
}

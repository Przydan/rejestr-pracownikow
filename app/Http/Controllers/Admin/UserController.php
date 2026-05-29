<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserStoreRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class UserController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $request): View
    {
        $this->authorize('viewAny', User::class);

        $users = User::with('roles')
            ->when($request->search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('employee_id', 'like', "%{$search}%")
                        ->orWhere('department', 'like', "%{$search}%");
                });
            })
            ->get();

        return view('admin.users.index', compact('users'));
    }

    public function create(): View
    {
        $this->authorize('create', User::class);

        $roles = Role::all();

        return view('admin.users.create', compact('roles'));
    }

    public function store(UserStoreRequest $request): RedirectResponse
    {
        $this->authorize('create', User::class);

        $validated = $request->validated();

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('employees/photos', 'public');
        }

        $roleId = $validated['role_id'];
        unset($validated['role_id'], $validated['photo']);

        $user = User::create(array_merge($validated, [
            'password' => Hash::make($validated['password']),
            'photo_path' => $photoPath,
        ]));

        $user->roles()->attach($roleId);

        return redirect()->route('admin.users.index')->with('success', 'Użytkownik został utworzony.');
    }

    public function show(User $user): RedirectResponse
    {
        return redirect()->route('admin.users.edit', $user->id);
    }

    public function edit(User $user): View
    {
        $this->authorize('update', $user);

        $roles = Role::all();

        return view('admin.users.edit', compact('user', 'roles'));
    }

    public function update(UserUpdateRequest $request, User $user): RedirectResponse
    {
        $this->authorize('update', $user);

        $validated = $request->validated();

        if ($request->hasFile('photo')) {
            if ($user->photo_path) {
                Storage::disk('public')->delete($user->photo_path);
            }
            $validated['photo_path'] = $request->file('photo')->store('employees/photos', 'public');
        }

        $roleId = $validated['role_id'];
        $validated['is_active'] = $request->boolean('is_active', false);
        unset($validated['role_id'], $validated['photo']);

        $user->update($validated);
        $user->roles()->sync([$roleId]);

        return redirect()->route('admin.users.index')->with('success', 'Użytkownik został zaktualizowany.');
    }

    public function destroy(User $user): RedirectResponse
    {
        $this->authorize('delete', $user);

        if ($user->photo_path) {
            Storage::disk('public')->delete($user->photo_path);
        }

        foreach ($user->attachments as $attachment) {
            Storage::disk('public')->delete($attachment->file_path);
        }

        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'Użytkownik został usunięty.');
    }
}

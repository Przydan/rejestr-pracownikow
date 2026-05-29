# Admin Dashboard Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Replace the empty Admin Dashboard with a functional operational hub providing system metrics and quick access to user management.

**Architecture:** Controller-based routing using a dedicated `AdminDashboardController` to fetch user statistics and render a Tailwind CSS grid view.

**Tech Stack:** PHP 8.4, Laravel 13, Blade, Tailwind CSS.

---

### Task 1: Controller Implementation

**Files:**
- Create: `app/Http/Controllers/Admin/AdminDashboardController.php`

- [ ] **Step 1: Create the controller with the `index` method**

```php
<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\View\View;

class AdminDashboardController extends Controller
{
    public function index(): View
    {
        return view('admin.dashboard', [
            'total_users' => User::count(),
            'admin_count' => User::whereHas('roles', fn($q) => $q->where('name', 'administrator'))->count(),
            'manager_count' => User::whereHas('roles', fn($q) => $q->where('name', 'kierownik'))->count(),
            'employee_count' => User::whereHas('roles', fn($q) => $q->where('name', 'pracownik'))->count(),
        ]);
    }
}
```

- [ ] **Step 2: Commit**
`git add app/Http/Controllers/Admin/AdminDashboardController.php`
`git commit -m "feat: add AdminDashboardController for stats overview"`

---

### Task 2: Routing Update

**Files:**
- Modify: `routes/web.php`

- [ ] **Step 1: Update the `/admin/dashboard` route to use the controller**

Replace:
```php
Route::get('/admin/dashboard', function () {
    return 'Admin Dashboard';
})->name('admin.dashboard');
```
With:
```php
use App\Http\Controllers\Admin\AdminDashboardController;
// ... inside the administrator middleware group
Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
```

- [ ] **Step 2: Commit**
`git add routes/web.php`
`git commit -m "feat: route admin dashboard to AdminDashboardController"`

---

### Task 3: View Implementation

**Files:**
- Create: `resources/views/admin/dashboard.blade.php`

- [ ] **Step 1: Create the dashboard view with stats grid and action buttons**

```blade
@extends('layouts.app')

@section('content')
<div class="space-y-8">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Panel Administratora</h1>
        <p class="text-gray-600">Przegląd statystyk systemu i szybki dostęp do zarządzania.</p>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">
        <!-- Total Users -->
        <div class="bg-white overflow-hidden shadow rounded-lg border border-gray-200 p-5">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                </div>
                <div class="ml-5">
                    <p class="text-sm font-medium text-gray-500 truncate">Wszyscy Użytkownicy</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $total_users }}</p>
                </div>
            </div>
        </div>

        <!-- Administrators -->
        <div class="bg-white overflow-hidden shadow rounded-lg border border-gray-200 p-5">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-red-100 text-red-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                </div>
                <div class="ml-5">
                    <p class="text-sm font-medium text-gray-500 truncate">Administratorzy</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $admin_count }}</p>
                </div>
            </div>
        </div>

        <!-- Managers -->
        <div class="bg-white overflow-hidden shadow rounded-lg border border-gray-200 p-5">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 text-green-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                </div>
                <div class="ml-5">
                    <p class="text-sm font-medium text-gray-500 truncate">Kierownicy</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $manager_count }}</p>
                </div>
            </div>
        </div>

        <!-- Employees -->
        <div class="bg-white overflow-hidden shadow rounded-lg border border-gray-200 p-5">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-indigo-100 text-indigo-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                </div>
                <div class="ml-5">
                    <p class="text-sm font-medium text-gray-500 truncate">Pracownicy</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $employee_count }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white shadow rounded-lg border border-gray-200 p-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Szybkie Akcje</h2>
        <div class="flex flex-wrap gap-4">
            <a href="{{ route('admin.users.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                Dodaj nowego pracownika
            </a>
            <a href="{{ route('admin.users.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                Zarządzaj użytkownikami
            </a>
        </div>
    </div>
</div>
@endsection
```

- [ ] **Step 2: Commit**
`git add resources/views/admin/dashboard.blade.php`
`git commit -m "feat: implement admin dashboard view with stats and quick actions"`

---

### Task 4: Final Verification

- [ ] **Step 1: Manual verification**
    - Log in as an Administrator.
    - Visit `/admin/dashboard`.
    - Verify that the 4 metric cards display correct numbers.
    - Click "Dodaj nowego pracownika" $\rightarrow$ verify it goes to User Create page.
    - Click "Zarządzaj użytkownikami" $\rightarrow$ verify it goes to User Index page.

- [ ] **Step 2: Run Pint to ensure style consistency**
`vendor/bin/pint --dirty --format agent`

- [ ] **Step 3: Commit final cleanup**
`git add .`
`git commit -m "chore: final cleanup and formatting for admin dashboard"`

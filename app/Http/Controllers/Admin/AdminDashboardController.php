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
            'admin_count' => User::whereHas('roles', fn ($q) => $q->where('name', 'administrator'))->count(),
            'manager_count' => User::whereHas('roles', fn ($q) => $q->where('name', 'kierownik'))->count(),
            'employee_count' => User::whereHas('roles', fn ($q) => $q->where('name', 'pracownik'))->count(),
        ]);
    }
}

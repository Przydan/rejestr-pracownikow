<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\View\View;

class ManagerDashboardController extends Controller
{
    public function index(): View
    {
        // Managers can see all users, but only edit them (no delete)
        $users = User::with('roles')->get();

        return view('manager.dashboard', compact('users'));
    }
}

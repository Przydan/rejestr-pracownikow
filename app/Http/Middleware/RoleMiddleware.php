<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     * @param  string  ...$roles
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (! Auth::check()) {
            return redirect('/login')->with('error', 'Forbidden');
        }

        $user = Auth::user();

        foreach ($roles as $role) {
            if ($user->hasRole($role)) {
                return $next($request);
            }
        }

        return redirect($this->getDashboardPath($user))->with('error', 'Forbidden');
    }

    /**
     * Get the dashboard path based on the user's roles.
     *
     * @param  User  $user
     */
    protected function getDashboardPath($user): string
    {
        if ($user->hasRole('administrator')) {
            return '/admin/dashboard';
        }

        if ($user->hasRole('kierownik')) {
            return '/manager/dashboard';
        }

        return '/dashboard';
    }
}

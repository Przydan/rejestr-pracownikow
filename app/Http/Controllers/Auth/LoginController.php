<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * Handle the login request and redirect based on user role.
     */
    public function store(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            if (! $user->is_active) {
                Auth::logout();

                return back()->withErrors([
                    'email' => 'Twoje konto jest nieaktywne. Skontaktuj się z administratorem.',
                ])->onlyInput('email');
            }

            $request->session()->regenerate();

            return $this->redirectBasedOnRole($user);
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    /**
     * Determine the redirection path based on user roles.
     *
     * @param  User  $user
     * @return RedirectResponse
     */
    protected function redirectBasedOnRole($user)
    {
        if ($user->hasRole('administrator')) {
            return redirect()->intended('/admin/dashboard');
        }

        if ($user->hasRole('kierownik')) {
            return redirect()->intended('/manager/dashboard');
        }

        return redirect()->intended('/dashboard');
    }

    /**
     * Log the user out of the application.
     */
    public function destroy(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}

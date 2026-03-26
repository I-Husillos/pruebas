<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Inertia\Response;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): Response
    {
        return Inertia::render('Auth/Login', [
            'canResetPassword' => Route::has('password.request'),
            'status' => session('status'),
        ]);
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        // Create a Passport personal access token and cache the raw string in session
        // so that BaseController can inject it into every Inertia page prop.
        /** @var User|null $user */
        $user = Auth::user();
        if ($user && method_exists($user, 'createToken')) {
            $result = $user->createToken('backoffice');
            $request->session()->put('passport_token', $result->accessToken);
        }

        return redirect()->intended(route('admin.dashboard', absolute: false));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        // Revoke the current backoffice token before logging out
        /** @var User|null $user */
        $user = Auth::guard('web')->user();
        if ($user) {
            $user->tokens()->where('name', 'backoffice')->delete();
        }

        Auth::guard('web')->logout();

        $request->session()->forget('passport_token');
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/es/es');
    }
}

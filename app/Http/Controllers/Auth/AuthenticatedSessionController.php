<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Support\Facades\Log;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();
        $request->session()->regenerate();

        $user = auth()->user();
        
        // DEBUG: Log the user info
        Log::info('=== LOGIN DEBUG ===');
        Log::info('User email: ' . $user->email);
        Log::info('User role column: ' . $user->role);
        Log::info('Spatie roles: ' . $user->getRoleNames());
        Log::info('Has admin: ' . ($user->hasRole('admin') ? 'YES' : 'NO'));
        Log::info('Has driver: ' . ($user->hasRole('driver') ? 'YES' : 'NO'));
        Log::info('Has customer: ' . ($user->hasRole('customer') ? 'YES' : 'NO'));

        $redirectUrl = null;
        
        if ($user->hasRole('admin')) {
            $redirectUrl = route('admin.dashboard');
            Log::info('Redirecting to admin.dashboard: ' . $redirectUrl);
        } elseif ($user->hasRole('driver')) {
            $redirectUrl = route('driver.dashboard');
            Log::info('Redirecting to driver.dashboard: ' . $redirectUrl);
        } else {
            $redirectUrl = route('customer.dashboard');
            Log::info('Redirecting to customer.dashboard: ' . $redirectUrl);
        }
        
        // Prevent caching of redirect to avoid session issues
        return redirect($redirectUrl)->withHeaders([
            'Cache-Control' => 'no-cache, no-store, must-revalidate',
            'Pragma' => 'no-cache',
            'Expires' => '0',
        ]);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        // Clear all session data first
        $request->session()->flush();
        
        // Logout the user
        Auth::guard('web')->logout();
        
        // Invalidate the session
        $request->session()->invalidate();
        
        // Regenerate CSRF token
        $request->session()->regenerateToken();
        
        // Clear any cached user data
        if (Auth::hasUser()) {
            Auth::logout();
        }
        
        // Redirect to home page with cache prevention headers
        return redirect('/')->withHeaders([
            'Cache-Control' => 'no-cache, no-store, must-revalidate',
            'Pragma' => 'no-cache',
            'Expires' => '0',
        ]);
    }
}
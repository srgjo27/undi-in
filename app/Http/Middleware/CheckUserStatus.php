<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckUserStatus
{
    /**
     * Handle an incoming request.
     * Check if user is active (not blocked)
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        // If user is authenticated but blocked (email_verified_at is null)
        if ($user && !$user->email_verified_at) {
            // Logout the blocked user
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            auth()->guard('web')->logout();

            // Redirect with error message
            return redirect()->route('login')->with(
                'error',
                'Your account has been blocked by administrator. Please contact support for assistance.'
            );
        }

        return $next($request);
    }
}

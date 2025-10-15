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

        if ($user && !$user->hasVerifiedEmail()) {
            session(['unverified_user_id' => $user->id]);

            auth()->guard('web')->logout();
            $request->session()->regenerate();

            return redirect()->route('verification.notice')->with(
                'error',
                'Silakan verifikasi email Anda terlebih dahulu sebelum mengakses halaman ini.'
            );
        }

        return $next($request);
    }
}

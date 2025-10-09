<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  $role  Peran yang diizinkan ('admin', 'seller', 'buyer')
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        $user = $request->user();

        if (!$user) {
            return redirect()->route('login')
                ->with('error', 'Please login to access this resource.');
        }

        $allowedRoles = ['admin', 'seller', 'buyer'];
        if (!in_array($role, $allowedRoles)) {
            abort(500, 'Invalid role configuration.');
        }

        if ($user->role !== $role) {
            Log::warning('Unauthorized access attempt', [
                'user_id' => $user->id,
                'user_role' => $user->role,
                'required_role' => $role,
                'ip' => $request->ip(),
                'url' => $request->fullUrl(),
                'user_agent' => $request->userAgent()
            ]);

            abort(403, 'You do not have permission to access this resource.');
        }

        return $next($request);
    }
}

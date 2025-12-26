<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class Verify2FA
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        // If user has 2FA enabled but hasn't verified this session
        if ($user && $user->twofa_enabled && ! $request->session()->get('2fa_verified')) {
            return redirect()->route('login.2fa');
        }

        return $next($request);
    }
}

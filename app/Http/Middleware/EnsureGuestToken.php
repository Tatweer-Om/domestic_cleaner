<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureGuestToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
   public function handle(Request $request, Closure $next): Response
{
    // Check if guest_token cookie exists
    if (!$request->cookie('guest_token')) {
        $token = (string) \Illuminate\Support\Str::uuid(); // Generate a UUID
        cookie()->queue(
            cookie(
                'guest_token', // Name
                $token,        // Value
                60 * 24 * 30,  // Minutes (30 days)
                null,          // Path
                null,          // Domain
                false,         // Secure
                true           // HttpOnly
            )
        );
    }

    return $next($request);
}
}

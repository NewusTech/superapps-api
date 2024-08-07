<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckAdminRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $roles = auth()->user();
        $roles = $roles->roles->first();
        if (str_contains($roles->name, 'Admin')) {
            return $next($request);
        }
        return response()->json(['message' => 'Anda Bukan Admin'], 403);
    }
}

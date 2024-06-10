<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        if (! $request->expectsJson()) {
            return response()->json([
                'sucess' => false,
                'data' => '',
                'message' => 'unauthorized'
            ]);
        }
    }

    protected function unauthenticated($request, array $guards)
    {
        abort(response()->json(
            [
                'status' => false,
                'data' => '401',
                'message' => 'UnAuthenticated',
            ], 401));
    }
}

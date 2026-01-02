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
        // If it's an API request (expects JSON or starts with /api), do NOT redirect
        // This prevents the "Route [login] not defined" error and lets Handler return JSON
        if ($request->expectsJson() || $request->is('api/*')) {
            return null;
        }

        // For web routes (if you ever add them), redirect to login page
        return route('login');
    }
}

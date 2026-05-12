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
        // Return null for JSON/API requests
        if ($request->expectsJson()) {
            return null;
        }

        // Check if the request URI contains 'backend' or 'frontend'
        if ($request->is('backend/*')) {
            return route('backend.login');
        }

        // Akan digunakan jika frontend telah tersedia
        if ($request->is('frontend/*')) {
            return route('frontend.login');
        }

        // Default redirect ke backend.login
        return route('backend.login');
    }
}

<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // Not logged in
        if (!auth()->check()) {
            abort(403, 'Not authorized');
        }

        // Logged in but not admin
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Admin access only');
        }

        return $next($request);
    }
}
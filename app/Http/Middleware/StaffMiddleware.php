<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class StaffMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // 1️⃣ Not logged in → redirect to login
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        // 2️⃣ Logged in but not staff → block
        if (auth()->user()->role !== 'staff') {
            abort(403, 'Unauthorized');
        }

        // 3️⃣ Correct staff → allow
        return $next($request);
    }
}
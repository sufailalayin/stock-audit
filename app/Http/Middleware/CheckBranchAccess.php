<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckBranchAccess
{
    public function handle(Request $request, Closure $next)
    {
        $user = auth()->user();

        // Only apply to admins
        if ($user->role === 'admin') {

            // If admin has no branch assigned → block
            if ($user->branches()->count() === 0) {
                abort(403, 'No branch assigned');
            }

            // If route has branch_id, validate access
            if ($request->route('branch')) {
                $branchId = $request->route('branch')->id;

                if (!$user->branches->contains('id', $branchId)) {
                    abort(403, 'Unauthorized branch access');
                }
            }
        }

        return $next($request);
    }
}
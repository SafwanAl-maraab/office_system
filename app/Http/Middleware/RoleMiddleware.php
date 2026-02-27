<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        $user = auth()->user();

        if (!$user || !$user->employee || !$user->employee->role) {
            abort(403, 'لا يوجد صلاحية');
        }

        if (!in_array($user->employee->role->name, $roles)) {
            abort(403, 'ليس لديك صلاحية');
        }

        return $next($request);
    }
}

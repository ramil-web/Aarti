<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RoleMiddleware
{
    public function handle($request, $next, $role)
    {
        if ($request->user()->role !== $role) {
            abort(403, 'Unauthorized');
        }
        return $next($request);
    }
}

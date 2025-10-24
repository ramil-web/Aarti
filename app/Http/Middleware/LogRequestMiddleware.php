<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LogRequestMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        Log::info('Request log', [
            'user_id' => auth()->id(),
            'method' => $request->method(),
            'url' => $request->fullUrl(),
            'body' => $request->all(),
            'timestamp' => now()
        ]);

        return $response;
    }
}

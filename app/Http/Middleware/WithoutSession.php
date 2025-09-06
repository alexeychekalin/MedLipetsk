<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class WithoutSession
{
    public function handle(Request $request, Closure $next)
    {
        // Отключаем старт сессии для SSE endpoints
        config(['session.driver' => 'array']);

        return $next($request);
    }
}

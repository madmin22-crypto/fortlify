<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureAppContext
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!isAppContext()) {
            abort(404);
        }

        return $next($request);
    }
}

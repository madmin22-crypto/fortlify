<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureMarketingContext
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!isMarketingContext()) {
            abort(404);
        }

        return $next($request);
    }
}

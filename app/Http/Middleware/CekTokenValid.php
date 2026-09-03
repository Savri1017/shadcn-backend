<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CekTokenValid
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
   public function handle(Request $request, Closure $next): Response
{
    if ($request->bearerToken() !== 'rahasia-123') {
        return response()->json(['message' => 'Unauthorized'], 401);
    }

    return $next($request);
}
}

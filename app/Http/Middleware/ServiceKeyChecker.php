<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class ServiceKeyChecker
{
    /**
     * Handle an incoming request.
     *
     * @param \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $serviceKey = config('app.x-api_key');

        if (is_null($serviceKey)) {
            Log::error('Missing API_KEY in environment');
            abort(Response::HTTP_UNAUTHORIZED);
        }

        if ($serviceKey !== $request->headers->get('X-Service-Key')) {
            abort(Response::HTTP_UNAUTHORIZED);
        }

        return $next($request);
    }
}

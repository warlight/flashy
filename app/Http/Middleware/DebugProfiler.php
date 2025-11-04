<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DebugProfiler
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next): mixed
    {
        $response = $next($request);

        if (
            config('app.debug') &&
            app()->bound('debugbar') &&
            app('debugbar')->isEnabled()
        ) {
            if ($response instanceof JsonResponse && is_object($response->getData())) {
                $response->setData($response->getData(true) + [
                        '_debugbar' => app('debugbar')->getData(),
                    ]);
            }
        }

        return $response;
    }
}

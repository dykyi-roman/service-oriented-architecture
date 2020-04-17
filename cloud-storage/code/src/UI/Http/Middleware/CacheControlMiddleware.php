<?php

declare(strict_types=1);

namespace App\UI\Http\Middleware;

use Closure;

class CacheControlMiddleware
{
    private const CACHE_AGE = 600;

    public function handle($request, Closure $next)
    {
        $response = $next($request);

        $response->setCache([
            'max_age' => self::CACHE_AGE,
            's_maxage' => self::CACHE_AGE,
            'public' => true,
        ]);

        return $response;
    }
}

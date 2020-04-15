<?php

declare(strict_types=1);

use App\UI\Http\Middleware\CacheControlMiddleware;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

/**
 * @coversDefaultClass CacheControlMiddleware
 */
class CacheControlMiddlewareTest extends TestCase
{
    /**
     * @covers ::handle
     */
    public function testExistCacheHeadersInResponse(): void
    {
        $middleware = new CacheControlMiddleware();

        /** @var Response $response */
        $response = $middleware->handle(new Request(), static function () {
            return new Response();
        });

        $this->assertTrue($response->headers->getCacheControlDirective('public'));
    }
}

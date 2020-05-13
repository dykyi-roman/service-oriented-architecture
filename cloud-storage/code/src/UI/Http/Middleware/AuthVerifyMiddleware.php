<?php

declare(strict_types=1);

namespace App\UI\Http\Middleware;

use App\Application\Common\Error;
use App\Application\Common\Response;
use App\Application\Security\Guard;
use App\Domain\Auth\Exception\AuthException;
use Closure;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;

class AuthVerifyMiddleware
{
    private Guard $guard;

    public function __construct(Guard $guard)
    {
        $this->guard = $guard;
    }

    public function handle($request, Closure $next)
    {
        $response = $next($request);

        if ('dev' === env('APP_ENV')) {
            return $response;
        }

        if (!array_key_exists('as', $request->route()[1])) {
            return $response;
        }
        $routeName = $request->route()[1]['as'];

        if (false === mb_strpos($routeName, 'api.')) {
            return $response;
        }

        try {
            $token = $request->headers->get('auth-token');
            if (null === $token) {
                throw AuthException::tokenIsNotFoundInHeaders('auth-token');
            }

            $this->guard->verify($token, env('JWT_PUBLIC_KEY'));

            return $response;
        } catch (AuthException $exception) {
            return $this->createResponseWithError($exception);
        }
    }

    private function createResponseWithError(Exception $exception): JsonResponse
    {
        $error = Error::create($exception->getMessage(), $exception->getCode());
        $response = new JsonResponse(Response::error($error), 401);
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }
}

<?php

declare(strict_types=1);

namespace App\Infrastructure\HttpClient\Middleware;

use Psr\Http\Message\RequestInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class AuthTokenMiddleware
{
    private ?TokenInterface $token;

    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->token = $tokenStorage->getToken();
    }

    public function __invoke(callable $handler): callable
    {
        return function (RequestInterface $request, array $options) use (&$handler) {
            if (null === $this->token) {
                return $handler($request, $options);
            }

            $request = $request->withHeader('auth-token', $this->token->getCredentials());

            return $handler($request, $options);
        };
    }
}

<?php

declare(strict_types=1);

namespace App\Application\Security\Service;

use App\Domain\Auth\Entity\User;
use App\Domain\Auth\Exception\AuthException;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\JWT;

final class Guard
{
    public function verify(string $token, string $key): User
    {
        if (!file_exists($key)) {
            throw AuthException::publicKeyIsNotFound($key);
        }

        $payload = null;
        try {
            $payload = JWT::decode($token, file_get_contents($key), ['RS256']);
        } catch (ExpiredException $exception) {
            //TODO:: Try to refresh key
            throw AuthException::tokenIsExpired();
        }

        if (null === $payload) {
            throw AuthException::tokenIsNotDecoded();
        }

        return User::createUserByJWTPayload($payload);
    }
}

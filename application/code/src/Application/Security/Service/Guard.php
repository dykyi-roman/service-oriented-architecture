<?php

declare(strict_types=1);

namespace App\Application\Security\Service;

use App\Domain\Auth\Entity\User;
use App\Domain\Auth\Exception\AuthException;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\JWT;
use UnexpectedValueException;

final class Guard
{
    public function verify(string $token, string $file): User
    {
        if (!file_exists($file)) {
            throw AuthException::publicKeyIsNotFound($file);
        }

        $payload = null;
        try {
            $payload = JWT::decode($token, file_get_contents($file), ['RS256']);
        } catch (ExpiredException $exception) {
            throw AuthException::tokenIsExpired();
        } catch (UnexpectedValueException $exception) {
            throw AuthException::tokenIsBroken();
        }

        if (null === $payload) {
            throw AuthException::tokenIsNotDecoded();
        }

        return User::createUserByJWTPayload($payload);
    }
}

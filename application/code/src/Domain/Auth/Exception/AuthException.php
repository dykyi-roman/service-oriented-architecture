<?php

declare(strict_types=1);

namespace App\Domain\Auth\Exception;

use RuntimeException;

class AuthException extends RuntimeException
{
    public static function unexpectedErrorInAuthoriseProcess(string $message): self
    {
        return new static(sprintf('Error in Authorise process: %s', $message), 1101);
    }

    public static function invalidCredentials(): self
    {
        return new static('Invalid credentials', 1102);
    }

    public static function publicKeyIsNotFound(string $key): self
    {
        return new static(sprintf('Public key is not found by path %s', $key), 1103);
    }

    public static function publicKeyIsNotUpdated(string $message): self
    {
        return new static(sprintf('Could not download a new public key. Reason: %s', $message), 1104);
    }

    public static function tokenIsExpired(): self
    {
        return new static('Token is expired', 1105);
    }

    public static function tokenIsNotDecoded(): self
    {
        return new static('Could not extract payload from token', 1106);
    }

    public static function tokenIsNotFoundInCookies(string $token): self
    {
        return new static(sprintf('Could not find "%s" token in the Cookies', $token), 1107);
    }
}

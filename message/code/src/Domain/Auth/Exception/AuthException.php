<?php

declare(strict_types=1);

namespace App\Domain\Auth\Exception;

use RuntimeException;

class AuthException extends RuntimeException
{
    public static function publicKeyIsNotFound(string $key): self
    {
        return new static(sprintf('Public key is not found by path %s', $key), 4100);
    }

    public static function publicKeyIsNotUpdated(string $message): self
    {
        return new static(sprintf('Could not download a new public key. Reason: %s', $message), 4101);
    }

    public static function tokenIsExpired(): self
    {
        return new static('Token is expired', 4102);
    }

    public static function tokenIsNotFoundInHeaders(string $tokenKey): self
    {
        return new static(sprintf('Token "%s" is not found in the headers', $tokenKey), 4103);
    }

    public static function tokenIsNotDecoded(): self
    {
        return new static('Could not extract payload from token', 4104);
    }
}

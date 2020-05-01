<?php

declare(strict_types=1);

namespace App\Domain\Auth\Exception;

use RuntimeException;

class AuthException extends RuntimeException
{
    public static function publicKeyIsNotFound(string $key): self
    {
        return new static(sprintf('Public key is not found by path %s', $key));
    }

    public static function publicKeyIsNotUpdated(string $message): self
    {
        return new static(sprintf('Could not download a new public key. Reason: %s', $message));
    }

    public static function tokenIsExpired(): self
    {
        return new static('Token is expired');
    }

    public static function tokenIsNotDecoded(): self
    {
        return new static('Could not extract payload from token');
    }
}

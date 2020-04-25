<?php

declare(strict_types=1);

namespace App\Domain\Auth\Exception;

use RuntimeException;

class AuthException extends RuntimeException
{
    public static function unexpectedErrorInAuthoriseProcess(): self
    {
        return new static('Unknown error in Authorise process');
    }

    public static function publicKeyIsNotFound(string $key): self
    {
        return new static(sprintf('Public key is not found by path %s', $key));
    }

    public static function tokenIsNotDecoded(): self
    {
        return new static('Could not extract payload from token');
    }

    public static function downloadPublicKey(): self
    {
        return new static('Could not download a new public key');
    }
}

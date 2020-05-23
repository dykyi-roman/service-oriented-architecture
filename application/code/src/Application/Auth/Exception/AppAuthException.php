<?php

declare(strict_types=1);

namespace App\Application\Auth\Exception;

use RuntimeException;

class AppAuthException extends RuntimeException
{
    public static function requireFieldsIsEmpty(): self
    {
        return new static('Fill in all required fields', 1401);
    }

    public static function codeInNotEqual(): self
    {
        return new static('code is not equal', 1402);
    }

    public static function domainException(string $exception, int $code): self
    {
        return new static($exception, $code);
    }
}

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

    public static function invalidEmailFormat(): self
    {
        return new static('Invalid email format', 1402);
    }

    public static function passwordLengthIsTooShort(int $length): self
    {
        return new static(sprintf('Your password is not secure. Min length should be "%d" symbol', $length), 1403);
    }

    public static function domainException(string $exception, int $code): self
    {
        return new static($exception, $code);
    }
}

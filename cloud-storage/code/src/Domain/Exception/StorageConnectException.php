<?php

declare(strict_types=1);

namespace App\Domain\Exception;

use RuntimeException;

final class StorageConnectException extends RuntimeException
{
    public static function connectProblem(): self
    {
        return new self('Connect is not initialization');
    }
}

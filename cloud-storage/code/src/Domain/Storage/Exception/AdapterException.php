<?php

declare(strict_types=1);

namespace App\Domain\Storage\Exception;

use Exception;

final class AdapterException extends Exception
{
    public const NO_SUPPORTED_MESSAGE = 'Adapter "%s" is not supported';

    public static function adapterListIsEmpty(array $adapters): self
    {
        $message = sprintf('Adapter list is empty. Choose one from this list: "%s"', implode(',', $adapters));

        return new self($message, 6200);
    }

    public static function adapterIsNotSupport(string $adapter): self
    {
        return new self(sprintf(self::NO_SUPPORTED_MESSAGE, $adapter), 6201);
    }
}

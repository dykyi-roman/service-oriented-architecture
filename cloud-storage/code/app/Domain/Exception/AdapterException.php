<?php

declare(strict_types=1);

namespace App\Domain\Exception;

use DomainException;

final class AdapterException extends DomainException
{
    public static function adapterListIsEmpty(array $adapters): self
    {
        return new self(sprintf('Adapter list is empty. Choose one from this list: "%s"', implode(',', $adapters)));
    }

    public static function adapterIsNotSupport(string $adapter): self
    {
        return new self(sprintf('adapter "%s" is not supported', $adapter));
    }
}

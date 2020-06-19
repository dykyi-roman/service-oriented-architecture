<?php

declare(strict_types=1);

namespace App\Domain\Storage\Exception;

use Exception;

final class StorageException extends Exception
{
    public static function couldNotFindAnyFiles(string $userId): self
    {
        return new self(sprintf('Could not find any files for user "%s"', $userId));
    }
}

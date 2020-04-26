<?php

declare(strict_types=1);

namespace App\Domain\Storage\Exception;

use Exception;

final class StorageException extends Exception
{
    public static function uploadProblem(string $message): self
    {
        return new static(sprintf('File is not upload. Reason: %s', $message));
    }

    public static function downloadProblem(string $message): self
    {
        return new static(sprintf('File is not download. Reason: %s', $message));
    }
}

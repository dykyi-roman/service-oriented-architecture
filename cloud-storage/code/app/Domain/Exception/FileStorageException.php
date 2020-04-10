<?php

declare(strict_types=1);

namespace App\Domain\Exception;

use DomainException;

final class FileStorageException extends DomainException
{
    public static function connectProblem(): self
    {
        return new self('Connect is not initialization');
    }

    public static function createFolderProblem(string $name): self
    {
        return new self(sprintf('Create folder "%s" problem', $name));
    }
}

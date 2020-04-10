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

    public static function deleteProblem(string $path): self
    {
        return new self(sprintf('Delete "%s" problem', $path));
    }

    public static function uploadProblem(string $file): self
    {
        return new self(sprintf('Upload "%s" problem', $file));
    }

    public static function downloadProblem(string $file, ?string $dir): self
    {
        return new self(sprintf('Download "%s" to "%s" problem ', $file, (string)$dir));
    }
}

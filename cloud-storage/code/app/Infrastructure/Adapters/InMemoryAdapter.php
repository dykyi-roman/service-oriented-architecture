<?php

declare(strict_types=1);

namespace App\Infrastructure\Adapters;

use App\Domain\StorageAdapterInterface;

final class InMemoryAdapter implements StorageAdapterInterface
{
    public function createFolder(string $name): array
    {
        return [];
    }

    public function upload(string $filePath, string $uploadFileDir, string $uploadFileExt): array
    {
        return [];
    }

    public function delete(string $path): array
    {
        return [];
    }

    public function download(string $filePath, string $downloadFilePath = null): array
    {
        return [];
    }
}

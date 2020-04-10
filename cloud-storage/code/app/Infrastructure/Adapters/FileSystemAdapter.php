<?php

declare(strict_types=1);

namespace App\Infrastructure\Adapters;

final class FileSystemAdapter extends AbstractAdapter implements StorageAdapterInterface
{
    public const ADAPTER = __CLASS__;

    public function createFolder(string $name): void
    {
        // Mock this method
    }

    public function upload(string $filePath, string $uploadFilePath): void
    {
        // Mock this method
    }

    public function delete(string $path): void
    {
        // Mock this method
    }

    public function download(string $filePath, string $downloadFilePath = null): string
    {
        return '';
    }
}

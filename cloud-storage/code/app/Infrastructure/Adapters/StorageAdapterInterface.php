<?php

declare(strict_types=1);

namespace App\Infrastructure\Adapters;

interface StorageAdapterInterface
{
    public function createFolder(string $name): void;

    public function delete(string $path): void;

    public function upload(string $filePath, string $uploadFilePath): void;

    public function download(string $filePath, string $downloadFilePath = null): string;
}

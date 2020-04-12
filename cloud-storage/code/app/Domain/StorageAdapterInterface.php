<?php

declare(strict_types=1);

namespace App\Domain;

interface StorageAdapterInterface
{
    public function createFolder(string $name): array;

    public function delete(string $path): array;

    public function upload(string $filePath, string $uploadFileDir, string $uploadFileExt): array;

    public function download(string $filePath): array;
}

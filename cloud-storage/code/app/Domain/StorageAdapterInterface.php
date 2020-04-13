<?php

declare(strict_types=1);

namespace App\Domain;

use App\Domain\ValueObject\UploadFile;

interface StorageAdapterInterface
{
    public function createFolder(string $name): array;

    public function upload(UploadFile $uploadFile): array;

    public function download(string $filePath): array;

    public function delete(string $path): array;
}

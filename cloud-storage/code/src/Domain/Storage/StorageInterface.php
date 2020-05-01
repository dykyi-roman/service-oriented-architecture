<?php

declare(strict_types=1);

namespace App\Domain\Storage;

use App\Domain\Storage\ValueObject\StorageResponse;
use App\Domain\Storage\ValueObject\UploadFile;

interface StorageInterface
{
    public function createFolder(string $name): StorageResponse;

    public function upload(UploadFile $uploadFile): StorageResponse;

    public function download(string $filePath): StorageResponse;

    public function delete(string $path): StorageResponse;
}

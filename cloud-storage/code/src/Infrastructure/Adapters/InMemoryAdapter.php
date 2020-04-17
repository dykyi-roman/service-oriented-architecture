<?php

declare(strict_types=1);

namespace App\Infrastructure\Adapters;

use App\Domain\StorageInterface;
use App\Domain\ValueObject\StorageResponse;
use App\Domain\ValueObject\UploadFile;

final class InMemoryAdapter implements StorageInterface
{
    public function createFolder(string $name): StorageResponse
    {
        return StorageResponse::createById($name);
    }

    public function upload(UploadFile $uploadFile): StorageResponse
    {
        if ('' === $uploadFile->file()) {
            return StorageResponse::empty();
        }

        $dir = '' === $uploadFile->fileDir() ? '' : $uploadFile->fileDir() . '/';
        
        return StorageResponse::create('test-id', $uploadFile->fileName(), '/' . $dir . $uploadFile->fileName());
    }

    public function download(string $path): StorageResponse
    {
        return StorageResponse::createByUrl($path);
    }

    public function delete(string $path): StorageResponse
    {
        return StorageResponse::empty();
    }
}

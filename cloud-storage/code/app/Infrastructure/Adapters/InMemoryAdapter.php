<?php

declare(strict_types=1);

namespace App\Infrastructure\Adapters;

use App\Domain\StorageAdapterInterface;
use App\Domain\ValueObject\UploadFile;

final class InMemoryAdapter implements StorageAdapterInterface
{
    public function createFolder(string $name): array
    {
        return ['id' => $name];
    }

    public function upload(UploadFile $uploadFile): array
    {
        if ('' === $uploadFile->file()) {
            return [];
        }

        $dir = '' === $uploadFile->fileDir() ? '' : $uploadFile->fileDir() . '/';
        return [
            'id' => '',
            'name' => $uploadFile->fileName(),
            'url' => '/' . $dir . $uploadFile->fileName()
        ];
    }

    public function download(string $path): array
    {
        return '' === $path ? [] : ['url' => $path];
    }

    public function delete(string $path): array
    {
        return [];
    }
}

<?php

declare(strict_types=1);

namespace App\Infrastructure\Adapters;

use App\Domain\StorageAdapterInterface;
use App\Domain\ValueObject\UploadFile;
use RuntimeException;

final class FileSystemAdapter implements StorageAdapterInterface
{
    public function createFolder(string $name): array
    {
        $path = sprintf('%s/app/%s', storage_path(), $name);
        if (!mkdir($concurrentDirectory = $path) && !is_dir($concurrentDirectory)) {
            throw new RuntimeException(sprintf('Directory "%s" was not created', $concurrentDirectory));
        }

        return ['id' => $path];
    }

    public function upload(UploadFile $uploadFile): array
    {
        $path = sprintf('%s/%s', $uploadFile->fileDir(), $uploadFile->fileName());
        $storage = sprintf('/storage/app/%s', ltrim($path, '/'));
        $result = move_uploaded_file($uploadFile->file(), '/code' . $storage);
        $url = sprintf('%s://%s/storage?path=%s', $_SERVER['REQUEST_SCHEME'], $_SERVER['HTTP_HOST'], $path);

        return [
            'id' => '',
            'name' => $uploadFile->fileName(),
            'url' => $result ? $url : ''
        ];
    }

    public function download(string $path): array
    {
        $url = sprintf('%s://%s/download?path=%s', $_SERVER['REQUEST_SCHEME'], $_SERVER['HTTP_HOST'], $path);

        return ['url' => $url];
    }

    public function delete(string $path): array
    {
        $file = '/code/storage/app/' . $path;
        is_file($file) ? unlink($file) : rmdir($file);

        return [];
    }
}

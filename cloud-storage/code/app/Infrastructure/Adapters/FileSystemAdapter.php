<?php

declare(strict_types=1);

namespace App\Infrastructure\Adapters;

use App\Domain\StorageAdapterInterface;
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

    public function upload(string $filePath, string $uploadFileDir, string $uploadFileExt): array
    {
        $fileName = sprintf('%s.%s', uniqid('', true), $uploadFileExt);
        $path = sprintf('%s/%s', $uploadFileDir, $fileName);
        $storage = sprintf('/storage/app/%s', $path);
        $result = move_uploaded_file($filePath, '/code/' . $storage);
        $url = sprintf('%s://%s/storage?path=%s', $_SERVER['REQUEST_SCHEME'], $_SERVER['HTTP_HOST'], $path);

        return [
            'id' => '',
            'name' => $fileName,
            'path' => $path,
            'url' => $result ? $url : ''
        ];
    }

    public function delete(string $path): array
    {
        $file = '/code/storage/app/' . $path;
        is_file($file) ? unlink($file) : rmdir($file);

        return [];
    }

    public function download(string $path): array
    {
        $url = sprintf('%s://%s/download?path=%s', $_SERVER['REQUEST_SCHEME'], $_SERVER['HTTP_HOST'], $path);

        return ['url' => $url];
    }
}

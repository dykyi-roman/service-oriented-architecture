<?php

declare(strict_types=1);

namespace App\Infrastructure\Adapters;

use App\Domain\StorageInterface;
use App\Domain\ValueObject\StorageResponse;
use App\Domain\ValueObject\UploadFile;
use RuntimeException;

final class FileStorageAdapter implements StorageInterface
{
    public function createFolder(string $name): StorageResponse
    {
        $path = sprintf('%s/app/%s', storage_path(), $name);
        if (!mkdir($concurrentDirectory = $path) && !is_dir($concurrentDirectory)) {
            throw new RuntimeException(sprintf('Directory "%s" was not created', $concurrentDirectory));
        }

        return StorageResponse::createById(sprintf('/app/%s', $name));
    }

    public function upload(UploadFile $uploadFile): StorageResponse
    {
        $path = sprintf('%s/%s', $uploadFile->fileDir(), $uploadFile->fileName());
        $storage = sprintf('/storage/app/%s', ltrim($path, '/'));
        $result = move_uploaded_file($uploadFile->file(), '/code' . $storage);
        if (!$result) {
            return StorageResponse::empty();
        }

        $url = sprintf('%s/storage?path=%s', $_SERVER['APP_URL'], $path);

        return StorageResponse::create('', $path, $result ? $url : '');
    }

    public function download(string $path): StorageResponse
    {
        $url = sprintf('%s/download?path=%s', $_SERVER['APP_URL'], $path);

        return StorageResponse::createByUrl($url);
    }

    public function delete(string $path): StorageResponse
    {
        $file = '/code/storage/app/' . $path;
        is_file($file) ? unlink($file) : $this->deleteDirectory($file);

        return StorageResponse::empty();
    }

    private function deleteDirectory(string $dir)
    {
        if (!file_exists($dir)) {
            return true;
        }

        if (!is_dir($dir)) {
            return unlink($dir);
        }

        foreach (scandir($dir) as $item) {
            if ($item === '.' || $item === '..') {
                continue;
            }

            if (!$this->deleteDirectory($dir . DIRECTORY_SEPARATOR . $item)) {
                return false;
            }
        }

        return rmdir($dir);
    }
}

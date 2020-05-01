<?php

declare(strict_types=1);

namespace App\Domain\Storage\ValueObject;

final class UploadFile
{
    private string $file;
    private string $fileDir;
    private string $fileName;

    public function __construct(string $file, string $fileExt, string $fileDir = '')
    {
        $this->file = $file;
        $this->fileDir = $fileDir;
        $this->fileName = sprintf('%s.%s', uniqid('', true), $fileExt);
    }

    public function isRootUploadDir(): bool
    {
        return '' === $this->fileDir;
    }

    public function file(): string
    {
        return $this->file;
    }

    public function fileDir(): string
    {
        return $this->fileDir;
    }

    public function fileName(): string
    {
        return $this->fileName;
    }
}

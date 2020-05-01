<?php

declare(strict_types=1);

namespace App\Domain\Storage\ValueObject;

final class StorageResponse
{
    private string $id;
    private string $path;
    private string $url;

    private function __construct(string $id, string $path, string $url)
    {
        $this->id = $id;
        $this->path = $path;
        $this->url = $url;
    }

    public static function create(string $id = '', string $path = '', string $url = ''): self
    {
        return new self($id, $path, $url);
    }

    public static function createById(string $id, string $path = '', string $url = ''): self
    {
        return new self($id, $path, $url);
    }

    public static function createByUrl(string $url, string $id = '', string $path = ''): self
    {
        return new self($id, $path, $url);
    }

    public static function empty(string $id = '', string $path = '', string $url = ''): self
    {
        return new self($id, $path, $url);
    }

    public function toArray(): array
    {
        $data = [
            'id' => $this->id,
            'path' => $this->path,
            'url' => $this->url,
        ];

        return array_filter($data, 'strlen');
    }
}

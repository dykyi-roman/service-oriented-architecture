<?php

declare(strict_types=1);

namespace App\Domain\ValueObject;

final class StorageResponse
{
    private string $id;
    private string $name;
    private string $url;

    private function __construct(string $id, string $name, string $url)
    {
        $this->id = $id;
        $this->name = $name;
        $this->url = $url;
    }

    public static function create(string $id = '', string $name = '', string $url = ''): self
    {
        return new self($id, $name, $url);
    }

    public static function createById(string $id, string $name = '', string $url = ''): self
    {
        return new self($id, $name, $url);
    }

    public static function createByUrl(string $url, string $id = '', string $name = ''): self
    {
        return new self($id, $name, $url);
    }

    public static function empty(string $id = '', string $name = '', string $url = ''): self
    {
        return new self($id, $name, $url);
    }

    public function toArray(): array
    {
        $data = [
            'id' => $this->id,
            'name' => $this->name,
            'url' => $this->url,
        ];

        return array_filter($data, 'strlen');
    }
}

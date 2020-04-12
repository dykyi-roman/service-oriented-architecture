<?php

declare(strict_types=1);

namespace App\Domain\ValueObject;

final class StorageResponse
{
    private string $id;

    public function __construct(string $id)
    {
        $this->id = $id;
    }

    public function id(): string
    {
        return $this->id;
    }
}

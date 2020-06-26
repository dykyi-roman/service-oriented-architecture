<?php

declare(strict_types=1);

namespace App\Application\Storage\DTO;

final class FileDTO
{
    public string $id;

    public function __construct(array $user)
    {
        $this->id = $user['id'];
    }
}

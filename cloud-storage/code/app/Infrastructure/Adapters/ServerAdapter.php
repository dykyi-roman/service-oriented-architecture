<?php

declare(strict_types=1);

namespace App\Infrastructure\Adapters;

use App\Domain\FileStorageInterface;

final class ServerAdapter implements FileStorageInterface
{
    public function createFolder(string $name): void
    {
        //Mock this method
    }
}

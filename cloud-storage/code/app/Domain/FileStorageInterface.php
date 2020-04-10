<?php

declare(strict_types=1);

namespace App\Domain;

interface FileStorageInterface
{
    public function createFolder(string $name): void;
}

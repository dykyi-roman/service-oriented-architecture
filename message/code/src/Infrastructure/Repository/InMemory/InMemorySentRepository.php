<?php

declare(strict_types=1);

namespace App\Infrastructure\Repository\InMemory;

use App\Domain\Sender\Document\Sent;
use App\Domain\Sender\Repository\SentPersistRepositoryInterface;

class InMemorySentRepository implements SentPersistRepositoryInterface
{
    public array $collection = [];

    public function save(Sent $sent): void
    {
        $this->collection[$sent->getId()] = $sent;
    }
}

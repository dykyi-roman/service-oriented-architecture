<?php

declare(strict_types=1);

namespace App\Infrastructure\Repository\InMemory;

use App\Domain\Sender\Document\NotSent;
use App\Domain\Sender\Repository\NotSentPersistRepositoryInterface;

class InMemoryNotSentRepository implements NotSentPersistRepositoryInterface
{
    public array $collection = [];

    public function save(NotSent $notSent): void
    {
        $this->collection[$notSent->getId()] = $notSent;
    }
}

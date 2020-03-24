<?php

declare(strict_types=1);

namespace App\Domain\Sender\Repository;

use App\Domain\Sender\Document\Sent;

interface SentPersistRepositoryInterface
{
    public function save(Sent $sent): void;
}

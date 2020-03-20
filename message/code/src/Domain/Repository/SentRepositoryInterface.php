<?php

declare(strict_types=1);

namespace App\Domain\Repository;

use App\Domain\Document\Sent;

interface SentRepositoryInterface
{
    public function save(Sent $sent): void;
}

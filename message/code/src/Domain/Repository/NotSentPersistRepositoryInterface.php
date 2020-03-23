<?php

declare(strict_types=1);

namespace App\Domain\Repository;

use App\Domain\Document\NotSent;

interface NotSentPersistRepositoryInterface
{
    public function save(NotSent $notSent): void;
}

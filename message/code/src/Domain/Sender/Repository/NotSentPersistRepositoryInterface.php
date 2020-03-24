<?php

declare(strict_types=1);

namespace App\Domain\Sender\Repository;

use App\Domain\Sender\Document\NotSent;

interface NotSentPersistRepositoryInterface
{
    public function save(NotSent $notSent): void;
}

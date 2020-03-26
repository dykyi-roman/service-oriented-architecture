<?php

declare(strict_types=1);

namespace App\Infrastructure\Repository\Doctrine;

use App\Domain\Sender\Document\NotSent;
use App\Domain\Sender\Repository\NotSentPersistRepositoryInterface;
use Doctrine\Bundle\MongoDBBundle\ManagerRegistry;
use Doctrine\Bundle\MongoDBBundle\Repository\ServiceDocumentRepository;

final class NotSentRepository extends ServiceDocumentRepository implements NotSentPersistRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, NotSent::class);
    }

    public function save(NotSent $notSent): void
    {
        $this->dm->persist($notSent);
        $this->dm->flush();
    }
}

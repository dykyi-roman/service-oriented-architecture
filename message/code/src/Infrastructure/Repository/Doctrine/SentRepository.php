<?php

declare(strict_types=1);

namespace App\Infrastructure\Repository\Doctrine;

use App\Domain\Sender\Document\Sent;
use App\Domain\Sender\Repository\SentPersistRepositoryInterface;
use Doctrine\Bundle\MongoDBBundle\ManagerRegistry;
use Doctrine\Bundle\MongoDBBundle\Repository\ServiceDocumentRepository;

final class SentRepository extends ServiceDocumentRepository implements SentPersistRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Sent::class);
    }

    public function save(Sent $sent): void
    {
        $this->dm->persist($sent);
        $this->dm->flush();
    }
}

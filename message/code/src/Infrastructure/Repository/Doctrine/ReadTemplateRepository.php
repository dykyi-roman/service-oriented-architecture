<?php

declare(strict_types=1);

namespace App\Infrastructure\Repository\Doctrine;

use App\Domain\Template\Document\Template;
use App\Domain\Template\Repository\ReadTemplateRepositoryInterface;
use Doctrine\Bundle\MongoDBBundle\ManagerRegistry;
use Doctrine\Bundle\MongoDBBundle\Repository\ServiceDocumentRepository;

final class ReadTemplateRepository extends ServiceDocumentRepository implements ReadTemplateRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Template::class);
    }

    /**
     * @inheritDoc
     * @return Template|null
     */
    public function findTemplate(string $name, string $type, string $lang): ?Template
    {
        return $this->findOneBy(
            [
                'name' => $name,
                'type' => $type,
                'lang' => $lang
            ]
        );
    }

    public function findById(string $id): ?Template
    {
        return $this->findOneBy(['id' => $id]);
    }

    public function all(): array
    {
        return $this->findAll();
    }
}

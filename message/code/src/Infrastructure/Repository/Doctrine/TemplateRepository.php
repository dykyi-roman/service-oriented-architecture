<?php

declare(strict_types=1);

namespace App\Infrastructure\Repository\Doctrine;

use App\Domain\Document\Template;
use App\Domain\Repository\TemplateRepositoryInterface;
use Doctrine\Bundle\MongoDBBundle\Repository\ServiceDocumentRepository;
use Doctrine\Bundle\MongoDBBundle\ManagerRegistry;

final class TemplateRepository extends ServiceDocumentRepository implements TemplateRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Template::class);
    }

    /**
     * @inheritDoc
     * @return Template|null|object
     */
    public function findTemplate(string $name, string $type, string $language): ?Template
    {
        return $this->findOneBy(
            [
                'name' => $name,
                'type' => $type,
                'lang' => $language
            ]
        );
    }
}

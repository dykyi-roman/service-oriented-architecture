<?php

declare(strict_types=1);

namespace App\Infrastructure\Repository\Doctrine;

use App\Domain\Template\Document\Template;
use App\Domain\Template\Repository\WriteTemplateRepositoryInterface;
use Doctrine\Bundle\MongoDBBundle\ManagerRegistry;
use Doctrine\Bundle\MongoDBBundle\Repository\ServiceDocumentRepository;

final class WriteTemplateRepository extends ServiceDocumentRepository implements WriteTemplateRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Template::class);
    }

    /**
     * @inheritDoc
     * @throws \Doctrine\ODM\MongoDB\MongoDBException
     */
    public function create(string $id, string $name, string $type, string $lang, string $subject, string $context): void
    {
        $template = new Template($id);
        $template->setName($name);
        $template->setType($type);
        $template->setLang($lang);
        $template->setSubject($subject);
        $template->setContext($context);

        $this->dm->persist($template);
        $this->dm->flush();
    }

    public function edit(string $id, string $subject, string $context): bool
    {
        $template = $this->find($id);
        if (null === $template) {
            return false;
        }

        $template->setSubject($subject);
        $template->setContext($context);

        $this->dm->persist($template);
        $this->dm->flush();

        return true;
    }

    public function remove(string $id): bool
    {
        $template = $this->find($id);
        if (null === $template) {
            return false;
        }

        $this->dm->remove($template);
        $this->dm->flush();

        return true;
    }
}

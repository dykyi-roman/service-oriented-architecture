<?php

declare(strict_types=1);

namespace App\Infrastructure\Repository\Doctrine;

use App\Domain\Document\Template;
use App\Domain\Repository\TemplatePersistRepositoryInterface;
use App\Domain\Repository\TemplateReadRepositoryInterface;
use Doctrine\Bundle\MongoDBBundle\Repository\ServiceDocumentRepository;
use Doctrine\Bundle\MongoDBBundle\ManagerRegistry;

final class TemplateRepository extends ServiceDocumentRepository implements TemplatePersistRepositoryInterface,
                                                                            TemplateReadRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Template::class);
    }

    /**
     * @inheritDoc
     * @return Template|null|object
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
        /** @var Template $template */
        $template = $this->find($id);
        if (!null === $template){
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
        if (!null === $template){
            return false;
        }

        $this->dm->remove($template);
        $this->dm->flush();

        return true;
    }
}

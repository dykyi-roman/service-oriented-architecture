<?php

declare(strict_types=1);

namespace App\Domain\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use App\Infrastructure\Repository\Doctrine\TemplateRepository;

/**
 * @MongoDB\Document(collection="template", repositoryClass=TemplateRepository::class)
 */
class Template
{
    /**
     * @MongoDB\Id
     */
    protected string $id;

    public function getId(): string
    {
        return $this->id;
    }
}

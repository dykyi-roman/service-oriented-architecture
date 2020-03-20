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

    /**
     * @MongoDB\Field(type="string")
     * @MongoDB\UniqueIndex()
     */
    protected string $name;

    /**
     * @MongoDB\Field(type="string")
     */
    protected string $type;

    /**
     * @MongoDB\Field(type="string")
     */
    protected string $language;

    /**
     * @MongoDB\Field(type="string")
     */
    protected string $subject;

    /**
     * @MongoDB\Field(type="string")
     */
    protected string $context;

    public function getId(): string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getLanguage(): string
    {
        return $this->language;
    }

    public function getSubject(): string
    {
        return $this->subject;
    }

    public function getContext(): string
    {
        return $this->context;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function setType(string $type): void
    {
        $this->type = $type;
    }

    public function setLanguage(string $language): void
    {
        $this->language = $language;
    }

    public function setSubject(string $subject): void
    {
        $this->subject = $subject;
    }

    public function setContext(string $context): void
    {
        $this->context = $context;
    }
}

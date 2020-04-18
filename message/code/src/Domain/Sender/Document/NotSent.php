<?php

declare(strict_types=1);

namespace App\Domain\Sender\Document;

use App\Infrastructure\Repository\Doctrine\NotSentRepository;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * @MongoDB\Document(collection="not_sent", repositoryClass=NotSentRepository::class)
 */
class NotSent
{
    /**
     * @MongoDB\Id(strategy="NONE")
     */
    protected string $id;

    /**
     * @MongoDB\Field(type="string")
     */
    protected string $error;

    /**
     * @MongoDB\Field(type="string")
     */
    protected string $template;

    /**
     * @MongoDB\Field(type="date")
     */
    protected \DateTime $date;

    public function __construct(string $id)
    {
        $this->id = $id;
        $this->date = new \DateTime();
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getError(): string
    {
        return $this->error;
    }

    public function getTemplate(): string
    {
        return $this->template;
    }

    public function setError(string $error): self
    {
        $this->error = $error;

        return $this;
    }

    public function setTemplate(string $template): self
    {
        $this->template = $template;

        return $this;
    }
}

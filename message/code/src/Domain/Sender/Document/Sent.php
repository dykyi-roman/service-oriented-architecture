<?php

declare(strict_types=1);

namespace App\Domain\Sender\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use App\Infrastructure\Repository\Doctrine\SentRepository;

/**
 * @MongoDB\Document(collection="sent", repositoryClass=SentRepository::class)
 */
class Sent
{
    /**
     * @MongoDB\Id(strategy="NONE")
     */
    protected string $id;

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

    public function getTemplate(): string
    {
        return $this->template;
    }

    public function getDate(): \DateTime
    {
        return $this->date;
    }

    public function setTemplate(string $template): self
    {
        $this->template = $template;

        return $this;
    }
}

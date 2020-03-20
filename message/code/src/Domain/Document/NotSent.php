<?php

declare(strict_types=1);

namespace App\Domain\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use App\Infrastructure\Repository\Doctrine\NotSentRepository;
use Ramsey\Uuid\UuidInterface;

/**
 * @MongoDB\Document(collection="not_sent", repositoryClass=NotSentRepository::class)
 */
class NotSent
{
    /**
     * @MongoDB\Id
     */
    protected string $id;

    /**
     * @MongoDB\Field(type="string", name="user_id")
     * @MongoDB\UniqueIndex()
     */
    protected string $userId;

    /**
     * @MongoDB\Field(type="error")
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

    public function __construct(UuidInterface $uuid)
    {
        $this->id = $uuid->toString();
        $this->date = new \DateTime();
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getUserId(): string
    {
        return $this->userId;
    }

    public function getError(): string
    {
        return $this->error;
    }

    public function getTemplate(): string
    {
        return $this->template;
    }

    public function setUserId(string $userId): self
    {
        $this->userId = $userId;

        return $this;
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

<?php

declare(strict_types=1);

namespace App\Domain\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use App\Infrastructure\Repository\Doctrine\NotSentRepository;

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

    public function setUserId(string $userId): void
    {
        $this->userId = $userId;
    }

    public function setError(string $error): void
    {
        $this->error = $error;
    }

    public function setTemplate(string $template): void
    {
        $this->template = $template;
    }
}

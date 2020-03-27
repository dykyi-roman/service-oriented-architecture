<?php

declare(strict_types=1);

namespace App\Application\Sender\Message;

final class MessageNotSent
{
    private string $userId;
    private string $template;
    private string $error;

    public function __construct(string $userId, string $template, string $error)
    {
        $this->userId = $userId;
        $this->template = $template;
        $this->error = $error ?? '';
    }

    public function getUserId(): string
    {
        return $this->userId;
    }

    public function getTemplate(): string
    {
        return $this->template;
    }

    public function getError(): string
    {
        return $this->error;
    }
}
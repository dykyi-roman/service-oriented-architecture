<?php

declare(strict_types=1);

namespace App\Application\Sender\Message;

final class MessageSent
{
    private string $userId;
    private string $template;

    public function __construct(string $userId, string $template)
    {
        $this->userId = $userId;
        $this->template = $template;
    }

    public function getUserId(): string
    {
        return $this->userId;
    }

    public function getTemplate(): string
    {
        return $this->template;
    }
}

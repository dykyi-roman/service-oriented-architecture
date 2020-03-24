<?php

declare(strict_types=1);

namespace App\Domain\Sender\Event;

use App\Domain\Template\ValueObject\Template;
use Symfony\Contracts\EventDispatcher\Event;

/**
 * @see PersistSentMessageSubscriber::class
 */
class NotSentEvent extends Event
{
    private string $userId;
    private string $error;
    private ?Template $template;

    public function __construct(string $userId, ?Template $template, string $error)
    {
        $this->userId = $userId;
        $this->template = $template;
        $this->error = $error;
    }

    public function getUserId(): string
    {
        return $this->userId;
    }

    public function getError(): string
    {
        return $this->error;
    }

    public function getTemplate(): ?Template
    {
        return $this->template;
    }
}

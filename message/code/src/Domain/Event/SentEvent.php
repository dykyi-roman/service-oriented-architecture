<?php

declare(strict_types=1);

namespace App\Domain\Event;

use App\Domain\ValueObject\Message;
use Symfony\Contracts\EventDispatcher\Event;

final class SentEvent extends Event
{
    private string $id;

    private Message $message;

    public function __construct(string $id, Message $message)
    {
        $this->id = $id;
        $this->message = $message;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getMessage(): Message
    {
        return $this->message;
    }
}

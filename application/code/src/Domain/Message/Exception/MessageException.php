<?php

declare(strict_types=1);

namespace App\Domain\Message\Exception;

use Exception;

class MessageException extends Exception
{
    public static function messageTypeIsNotSupport(): self
    {
        return new static('Message type is not support', 1201);
    }

    public static function sendProblem(string $message): self
    {
        return new static(sprintf('Send message problem. Reason: %s', $message), 1202);
    }
}

<?php

declare(strict_types=1);

namespace App\Domain\Sender\Exception;

use Exception;

class MessageException extends Exception
{

    public static function messageTypeIsNotSupport(): self
    {
        return new static('Message type is not support', 4200);
    }

    public static function messageSenderIsNotFound(): self
    {
        return new static('Message sender is not found', 4201);
    }

    public static function messageNotSent(): self
    {
        return new static('Message is not sent', 4202);
    }
}

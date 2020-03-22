<?php

declare(strict_types=1);

namespace App\Domain\Exception;

use Exception;

class MessageException extends Exception
{
    public static function messageTypeIsNotSupport(): self
    {
        return new static('Message type is not support');
    }

    public static function messageSenderIsNotFound(): self
    {
        return new static('Message sender is not found');
    }
}

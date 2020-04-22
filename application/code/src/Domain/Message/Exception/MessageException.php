<?php

declare(strict_types=1);

namespace App\Domain\Message\Exception;

use Exception;

class MessageException extends Exception
{
    public static function messageTypeIsNotSupport(): self
    {
        return new static('Message type is not support');
    }
}

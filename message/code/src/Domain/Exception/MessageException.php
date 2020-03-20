<?php

declare(strict_types=1);

namespace App\Domain\Exception;

use Exception;

class MessageException extends Exception
{
    public static function notSupportMessageType(): self
    {
        return new static('Message type is not support');
    }
}

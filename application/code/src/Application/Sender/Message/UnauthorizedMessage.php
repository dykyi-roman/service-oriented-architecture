<?php

declare(strict_types=1);

namespace App\Application\Sender\Message;

final class UnauthorizedMessage
{
    private const UNAUTHORIZE_ID = '00000000-0000-0000-0000-000000000001';

    public static function create(array $recipients, array $template): Message
    {
        return new Message(self::UNAUTHORIZE_ID, $recipients, $template);
    }
}

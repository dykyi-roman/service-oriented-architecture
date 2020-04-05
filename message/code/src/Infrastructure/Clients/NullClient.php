<?php

declare(strict_types=1);

namespace App\Infrastructure\Clients;

use App\Domain\Sender\MessageInterface;
use App\Domain\Sender\MessageSenderInterface;

final class NullClient implements MessageSenderInterface
{
    public function send(MessageInterface $message): bool
    {
        return true;
    }
}

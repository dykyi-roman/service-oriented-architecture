<?php

declare(strict_types=1);

namespace App\Domain\Sender;

interface MessageSenderInterface
{
    public function send(MessageInterface $message): void;
}

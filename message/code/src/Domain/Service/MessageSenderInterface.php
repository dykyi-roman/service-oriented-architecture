<?php

declare(strict_types=1);

namespace App\Domain\Service;

use App\Domain\ValueObject\MessageInterface;

interface MessageSenderInterface
{
    public function send(MessageInterface $message): void;
}

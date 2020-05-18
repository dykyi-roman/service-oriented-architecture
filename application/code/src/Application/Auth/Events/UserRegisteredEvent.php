<?php

declare(strict_types=1);

namespace App\Application\Auth\Events;

use App\Domain\Auth\ValueObject\Email;
use Ramsey\Uuid\UuidInterface;
use Symfony\Contracts\EventDispatcher\Event;

class UserRegisteredEvent extends Event
{
    private Email $email;
    private UuidInterface $uuid;

    public function __construct(UuidInterface $uuid, Email $email)
    {
        $this->uuid = $uuid;
        $this->email = $email;
    }

    public function uuid(): UuidInterface
    {
        return $this->uuid;
    }

    public function email(): Email
    {
        return $this->email;
    }
}

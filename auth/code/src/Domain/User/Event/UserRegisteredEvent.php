<?php

namespace App\Domain\User\Event;

use Immutable\ValueObject\Email;
use Ramsey\Uuid\UuidInterface;
use Symfony\Contracts\EventDispatcher\Event;

final class UserRegisteredEvent extends Event
{
    private Email $email;
    private UuidInterface $uuid;

    public function __construct(UuidInterface $uuid, Email $email)
    {
        $this->uuid = $uuid;
        $this->email = $email;
    }

    public function getEmail(): Email
    {
        return $this->email;
    }

    public function getUuid(): UuidInterface
    {
        return $this->uuid;
    }
}

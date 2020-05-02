<?php

declare(strict_types=1);

namespace App\Application\User\Command;

use App\Application\User\Handler\UserRegisterHandler;
use App\Domain\User\ValueObject\Phone;
use App\Domain\User\ValueObject\UserRegistrationRequest;
use Immutable\ValueObject\Email;
use Ramsey\Uuid\UuidInterface;

/**
 * @see UserRegisterHandler
 */
final class UserRegisterCommand
{
    private UuidInterface $uuid;

    private UserRegistrationRequest $request;

    public function __construct(UuidInterface $uuid, UserRegistrationRequest $request)
    {
        $this->uuid = $uuid;
        $this->request = $request;
    }

    public function getUuid(): UuidInterface
    {
        return $this->uuid;
    }

    public function getEmail(): Email
    {
        return $this->request->getEmail();
    }

    public function getPassword(): string
    {
        return $this->request->getPassword();
    }

    public function getPhone(): Phone
    {
        return $this->request->getPhone();
    }

    public function getFullName(): string
    {
        return $this->request->getFullName()->toString();
    }
}

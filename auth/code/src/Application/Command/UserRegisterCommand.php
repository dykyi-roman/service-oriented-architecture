<?php

declare(strict_types=1);

namespace App\Application\Command;

use App\Application\Handler\UserRegisterHandler;
use App\Domain\VO\UserRegistrationRequest;
use Ramsey\Uuid\UuidInterface;

/**
 * @see UserRegisterHandler
 */
final class UserRegisterCommand
{
    private UuidInterface $uuid;

    protected UserRegistrationRequest $request;

    public function __construct(UuidInterface $uuid, UserRegistrationRequest $request)
    {
        $this->uuid = $uuid;
        $this->request = $request;
    }

    public function getUuid(): UuidInterface
    {
        return $this->uuid;
    }

    public function getEmail(): string
    {
        return $this->request->getEmail();
    }

    public function getPassword(): string
    {
        return $this->request->getPassword();
    }

    public function getPhone(): string
    {
        return $this->request->getPhone();
    }

    public function getFullName(): string
    {
        return $this->request->getFullName()->getValue();
    }
}

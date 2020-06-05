<?php

declare(strict_types=1);

namespace App\Application\User\Command;

use App\Domain\User\Request\AdminRegistrationRequest;
use Immutable\ValueObject\Email;
use Ramsey\Uuid\UuidInterface;

/**
 * @see AdminRegisterHandler
 */
final class AdminRegisterCommand
{
    private UuidInterface $uuid;
    private AdminRegistrationRequest $request;

    public function __construct(UuidInterface $uuid, AdminRegistrationRequest $request)
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

    public function getFullName(): string
    {
        return $this->request->getFullName()->toString();
    }
}

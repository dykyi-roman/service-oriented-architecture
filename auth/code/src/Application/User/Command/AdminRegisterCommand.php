<?php

declare(strict_types=1);

namespace App\Application\User\Command;

use App\Domain\User\Request\AdminRegistrationRequest;
use App\Domain\User\ValueObject\FullName;
use Immutable\ValueObject\Email;
use Ramsey\Uuid\UuidInterface;

/**
 * @see AdminRegisterHandler
 */
final class AdminRegisterCommand
{
    public UuidInterface $uuid;
    public FullName $fullName;
    public Email $email;
    public string $password;

    public function __construct(UuidInterface $uuid, AdminRegistrationRequest $request)
    {
        $this->uuid = $uuid;
        $this->fullName = $request->getFullName();
        $this->email = $request->getEmail();
        $this->password = $request->getPassword();
    }
}

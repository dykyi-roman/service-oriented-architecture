<?php

declare(strict_types=1);

namespace App\Application\User\Command;

use App\Application\User\Handler\UserRegisterHandler;
use App\Domain\User\Request\UserRegistrationRequest;
use App\Domain\User\ValueObject\FullName;
use App\Domain\User\ValueObject\Phone;
use Immutable\ValueObject\Email;
use Ramsey\Uuid\UuidInterface;

/**
 * @see UserRegisterHandler
 */
final class UserRegisterCommand
{
    public UuidInterface $uuid;
    public FullName $fullName;
    public Email $email;
    public Phone $phone;
    public string $password;

    public function __construct(UuidInterface $uuid, UserRegistrationRequest $request)
    {
        $this->uuid = $uuid;
        $this->fullName = $request->getFullName();
        $this->email = $request->getEmail();
        $this->phone = $request->getPhone();
        $this->password = $request->getPassword();
    }
}

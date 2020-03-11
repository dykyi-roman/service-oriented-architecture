<?php

declare(strict_types=1);

namespace App\Application\Command;

use App\Application\Handler\UserRegisterHandler;
use App\Domain\VO\UserRegistrationRequest;

/**
 * @see UserRegisterHandler
 */
final class UserRegisterCommand
{
    protected UserRegistrationRequest $request;

    public function __construct(UserRegistrationRequest $request)
    {
        $this->request = $request;
    }

    public function getEmail(): string
    {
        return $this->request->getEmail();
    }

    public function getPassword(): string
    {
        return $this->request->getPassword();
    }

    public function getUsername(): string
    {
        return $this->request->getUsername();
    }
}

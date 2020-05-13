<?php

declare(strict_types=1);

namespace App\Application\Auth\Handler;

use App\Application\Auth\Command\LogoutUserCommand;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class LogoutUserHandler
{
    private TokenStorageInterface $tokenStorage;

    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

    public function __invoke(LogoutUserCommand $command)
    {
        $this->tokenStorage->setToken(null);
    }
}

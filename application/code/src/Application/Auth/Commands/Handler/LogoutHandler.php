<?php

declare(strict_types=1);

namespace App\Application\Auth\Commands\Handler;

use App\Application\Auth\Commands\Command\LogoutCommand;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class LogoutHandler
{
    private TokenStorageInterface $tokenStorage;

    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

    public function __invoke(LogoutCommand $command)
    {
        $this->tokenStorage->setToken(null);
    }
}

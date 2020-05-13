<?php

declare(strict_types=1);

namespace App\Application\Auth\Service;

use App\Application\Auth\Command\LoginUserCommand;
use App\Application\Auth\Exception\AppAuthException;
use App\Application\Auth\Request\LoginRequest;
use App\Domain\Auth\AuthAdapter;
use App\Domain\Auth\Exception\AuthException;
use SimpleBus\SymfonyBridge\Bus\CommandBus;

final class LoginUser
{
    private CommandBus $commandBus;
    private AuthAdapter $authAdapter;

    public function __construct(CommandBus $commandBus, AuthAdapter $authAdapter)
    {
        $this->commandBus = $commandBus;
        $this->authAdapter = $authAdapter;
    }

    public function login(LoginRequest $loginRequest): void
    {
        try {
            $tokens = $this->authAdapter->authorize($loginRequest->login(), $loginRequest->password());
            $this->commandBus->handle(new LoginUserCommand($tokens['token'], $tokens['refresh_token']));
        } catch (AuthException $exception) {
            throw AppAuthException::domainException($exception->getMessage(), $exception->getCode());
        }
    }
}

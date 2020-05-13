<?php

declare(strict_types=1);

namespace App\Application\Auth\Handler;

use App\Application\Auth\Command\LoginUserCommand;
use App\Application\Security\Service\Guard;
use App\Application\Security\Service\Token;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class LoginUserHandler
{
    private Guard $guard;
    private ParameterBagInterface $bag;
    private TokenStorageInterface $tokenStorage;

    public function __construct(TokenStorageInterface $tokenStorage, Guard $guard, ParameterBagInterface $bag)
    {
        $this->tokenStorage = $tokenStorage;
        $this->bag = $bag;
        $this->guard = $guard;
    }

    public function __invoke(LoginUserCommand $command)
    {
        $user = $this->guard->verify($command->getToken(), $this->bag->get('JWT_PUBLIC_KEY'));
        $token = new Token();
        $token->setUser($user);
        $token->setAuthToken($command->getToken());
        $token->setRefreshAuthToken($command->getRefreshToken());
        $this->tokenStorage->setToken($token);
    }
}

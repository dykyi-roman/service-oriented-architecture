<?php

declare(strict_types=1);

namespace App\Application\Auth\Service;

use App\Application\Auth\Events\UserRegisteredEvent;
use App\Application\Auth\Exception\AppAuthException;
use App\Application\Auth\Request\SignUpRequest;
use App\Domain\Auth\AuthAdapter;
use App\Domain\Auth\Exception\AuthException;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

final class SignUpUser
{
    private AuthAdapter $authAdapter;
    private EventDispatcherInterface $dispatcher;

    public function __construct(AuthAdapter $authAdapter, EventDispatcherInterface $dispatcher)
    {
        $this->authAdapter = $authAdapter;
        $this->dispatcher = $dispatcher;
    }

    public function signUp(SignUpRequest $signUpRequest): void
    {
        try {
            $uuid = $this->authAdapter->signUp(
                $signUpRequest->email(),
                $signUpRequest->password(),
                $signUpRequest->phone(),
                $signUpRequest->fullName()
            );
            $this->dispatcher->dispatch(new UserRegisteredEvent($uuid, $signUpRequest->email()));
        } catch (AuthException $exception) {
            throw AppAuthException::domainException($exception->getMessage(), $exception->getCode());
        }
    }
}

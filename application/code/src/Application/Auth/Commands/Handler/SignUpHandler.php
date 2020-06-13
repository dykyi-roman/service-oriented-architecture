<?php

declare(strict_types=1);

namespace App\Application\Auth\Commands\Handler;

use App\Application\Auth\Commands\Command\SignUpCommand;
use App\Application\Auth\Events\UserRegisteredEvent;
use App\Application\Auth\Exception\AppAuthException;
use App\Domain\Auth\AuthAdapter;
use App\Domain\Auth\Exception\AuthException;
use Ramsey\Uuid\Uuid;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

final class SignUpHandler
{
    private AuthAdapter $authAdapter;
    private EventDispatcherInterface $dispatcher;

    public function __construct(AuthAdapter $authAdapter, EventDispatcherInterface $dispatcher)
    {
        $this->authAdapter = $authAdapter;
        $this->dispatcher = $dispatcher;
    }

    public function __invoke(SignUpCommand $command)
    {
        try {
            $signUpRequest = $command->request();
            $response = $this->authAdapter->signUp(
                $signUpRequest->email(),
                $signUpRequest->password(),
                $signUpRequest->phone(),
                $signUpRequest->fullName()
            );
            $uuid = Uuid::fromString($response->getData()['uuid']);
            $this->dispatcher->dispatch(new UserRegisteredEvent($uuid, $signUpRequest->email()));
        } catch (AuthException $exception) {
            throw AppAuthException::domainException($exception->getMessage(), $exception->getCode());
        }
    }
}

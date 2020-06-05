<?php

declare(strict_types=1);

namespace App\Application\Auth\Commands\Handler;

use App\Application\Auth\Commands\Command\SignUpCommand;
use App\Application\Auth\Exception\AppAuthException;
use App\Domain\Auth\AuthAdapter;
use App\Domain\Auth\Exception\AuthException;

final class SignUpHandler
{
    private AuthAdapter $authAdapter;

    public function __construct(AuthAdapter $authAdapter)
    {
        $this->authAdapter = $authAdapter;
    }

    public function __invoke(SignUpCommand $command)
    {
        try {
            $signUpRequest = $command->request();
            $this->authAdapter->signUp(
                $signUpRequest->email(),
                $signUpRequest->password(),
                $signUpRequest->fullName()
            );
        } catch (AuthException $exception) {
            throw AppAuthException::domainException($exception->getMessage(), $exception->getCode());
        }
    }
}

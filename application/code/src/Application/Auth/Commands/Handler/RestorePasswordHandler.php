<?php

declare(strict_types=1);

namespace App\Application\Auth\Commands\Handler;

use App\Application\Auth\Commands\Command\RestorePasswordCommand;
use App\Application\Auth\Exception\AppAuthException;
use App\Domain\Auth\AuthAdapter;
use App\Domain\Auth\Exception\AuthException;
use App\Domain\TemporaryCode\ReadTemporaryCodeInterface;

final class RestorePasswordHandler
{
    private AuthAdapter $authAdapter;
    private ReadTemporaryCodeInterface $temporaryCode;

    public function __construct(AuthAdapter $authAdapter, ReadTemporaryCodeInterface $temporaryCode)
    {
        $this->authAdapter = $authAdapter;
        $this->temporaryCode = $temporaryCode;
    }

    /**
     * @throws AppAuthException
     */
    public function __invoke(RestorePasswordCommand $command): void
    {
        $key = $this->filter($command->contact());
        $code = $this->temporaryCode->read('password', $key);
        if ($code === $command->code()) {
            throw AppAuthException::codeInNotEqual();
        }

        try {
            $this->authAdapter->passwordRestore($command->contact(), $command->password());
        } catch (AuthException $exception) {
            throw AppAuthException::domainException($exception->getMessage(), $exception->getCode());
        }
    }

    private function filter(string $value): string
    {
        return str_replace(['@', '.', '+', ' '], '', $value);
    }
}

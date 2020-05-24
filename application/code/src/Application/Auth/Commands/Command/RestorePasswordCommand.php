<?php

declare(strict_types=1);

namespace App\Application\Auth\Commands\Command;

use App\Application\Auth\Exception\AppAuthException;
use App\Application\Auth\Request\RestorePasswordRequest;
use App\Domain\Auth\ValueObject\Password;

/**
 * @see RestorePasswordHandler::class
 */
final class RestorePasswordCommand
{
    private RestorePasswordRequest $request;

    /**
     * @throws AppAuthException
     */
    public function __construct(RestorePasswordRequest $request)
    {
        $this->request = $request;
    }

    public function contact(): string
    {
        return $this->request->contact();
    }

    public function code(): string
    {
        return $this->request->code();
    }

    public function password(): Password
    {
        return $this->request->password();
    }
}

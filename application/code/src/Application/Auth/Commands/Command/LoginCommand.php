<?php

declare(strict_types=1);

namespace App\Application\Auth\Commands\Command;

use App\Application\Auth\Request\LoginRequest;

/**
 * @see LoginHandler::class
 */
final class LoginCommand
{
    private LoginRequest $loginRequest;

    public function __construct(LoginRequest $loginRequest)
    {
        $this->loginRequest = $loginRequest;
    }

    public function request(): LoginRequest
    {
        return $this->loginRequest;
    }
}

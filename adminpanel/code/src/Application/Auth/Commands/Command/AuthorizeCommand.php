<?php

declare(strict_types=1);

namespace App\Application\Auth\Commands\Command;

/**
 * @see AuthorizeHandler::class
 */
final class AuthorizeCommand
{
    private string $token;
    private string $refreshToken;

    public function __construct(string $token, string $refreshToken)
    {
        $this->token = $token;
        $this->refreshToken = $refreshToken;
    }

    public function getToken(): string
    {
        return $this->token;
    }

    public function getRefreshToken(): string
    {
        return $this->refreshToken;
    }
}

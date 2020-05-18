<?php

declare(strict_types=1);

namespace App\Domain\Auth\ValueObject;

use App\Domain\Auth\Exception\AuthException;

final class Token
{
    private string $token;
    private string $refreshToken;

    public function __construct(array $tokens)
    {
        $this->assertWhenTokenError($tokens);

        $this->token = $tokens['token'] ?? '';
        $this->refreshToken = $tokens['refresh_token'] ?? '';
    }

    public function token(): string
    {
        return $this->token;
    }

    public function refreshToken(): string
    {
        return $this->refreshToken;
    }

    /**
     * @throws AuthException
     */
    public function assertWhenTokenError(array $tokens): void
    {
        if (!array_key_exists('token', $tokens)) {
            throw AuthException::invalidCredentials();
        }
    }
}

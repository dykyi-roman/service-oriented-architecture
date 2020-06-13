<?php

declare(strict_types=1);

namespace App\Domain\Auth\Response;

final class ApiAuthorizeResponse implements ApiResponseInterface
{
    private array $tokens;
    private string $token;
    private string $refreshToken;
    private int $errorCode;
    private string $errorMessage;

    public function __construct(array $tokens)
    {
        $this->tokens = $tokens;
        if ($this->isSuccess()) {
            $this->token = $tokens['token'] ?? '';
            $this->refreshToken = $tokens['refresh_token'] ?? '';
        }

        if ($this->hasError()) {
            $this->errorCode = 1;
            $this->errorMessage = 'Invalid credentials';
        }
    }

    public function token(): string
    {
        return (string) $this->token;
    }

    public function refreshToken(): string
    {
        return (string) $this->refreshToken;
    }

    public function isSuccess(): bool
    {
        return array_key_exists('token', $this->tokens);
    }

    public function getData(): array
    {
        return $this->tokens;
    }

    public function hasError(): bool
    {
        return !array_key_exists('token', $this->tokens);
    }

    public function getErrorMessage(): string
    {
        return $this->errorMessage;
    }

    public function getErrorCode(): int
    {
        return $this->errorCode;
    }
}

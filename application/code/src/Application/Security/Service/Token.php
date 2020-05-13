<?php

declare(strict_types=1);

namespace App\Application\Security\Service;

use Symfony\Component\Security\Core\Authentication\Token\AbstractToken;

final class Token extends AbstractToken
{
    private string $token = '';
    private string $refreshToken = '';

    public function getCredentials(): string
    {
        return $this->token;
    }

    public function setAuthToken(string $token): void
    {
        $this->token = $token;
    }

    public function setRefreshAuthToken(string $token): void
    {
        $this->refreshToken = $token;
    }

    public function __serialize(): array
    {
        return [$this->token, $this->refreshToken, parent::__serialize()];
    }

    public function __unserialize(array $data): void
    {
        [$this->token, $this->refreshToken, $parentData] = $data;
        parent::__unserialize($parentData);
    }
}

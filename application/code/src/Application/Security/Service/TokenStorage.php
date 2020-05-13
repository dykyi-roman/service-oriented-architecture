<?php

declare(strict_types=1);

namespace App\Application\Security\Service;

use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

final class TokenStorage implements TokenStorageInterface
{
    private SessionInterface $session;

    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }

    public function getToken(): ?TokenInterface
    {
        return $this->session->get('auth-token');
    }

    public function setToken(TokenInterface $token = null): void
    {
        $this->session->set('auth-token', $token ?: null);
    }
}

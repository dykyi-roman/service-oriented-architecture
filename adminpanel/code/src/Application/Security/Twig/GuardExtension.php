<?php

declare(strict_types=1);

namespace App\Application\Security\Twig;

use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

final class GuardExtension extends AbstractExtension
{
    private TokenStorageInterface $tokenStorage;

    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('isAuthenticated', [$this, 'isAuthenticated']),
        ];
    }

    public function isAuthenticated(): bool
    {
        $token = $this->tokenStorage->getToken();
        if (!$token) {
            return false;
        }

        return $token->isAuthenticated();
    }
}

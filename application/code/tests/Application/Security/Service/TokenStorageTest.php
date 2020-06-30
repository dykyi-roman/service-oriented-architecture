<?php

declare(strict_types=1);

namespace App\Tests\Application\Security\Service;

use App\Application\Security\Service\Token;
use App\Application\Security\Service\TokenStorage;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

/**
 * @coversDefaultClass TokenStorage
 */
class TokenStorageTest extends TestCase
{
    /**
     * @covers ::setToken
     */
    public function testPersistTokenObjectInTokenStorage(): void
    {
        $session = $this->createMock(SessionInterface::class);
        $session->expects(self::once())->method('set');

        $token = new Token();

        $tokenStorage = new TokenStorage($session);
        $tokenStorage->setToken($token);
    }

    /**
     * @covers ::setToken
     */
    public function testPersistNullTokenInTokenStorage(): void
    {
        $session = $this->createMock(SessionInterface::class);
        $session->expects(self::once())->method('set');

        $tokenStorage = new TokenStorage($session);
        $tokenStorage->setToken(null);
    }

    /**
     * @covers ::getToken
     */
    public function testGetTokenFromTokeStorage(): void
    {
        $session = $this->createMock(SessionInterface::class);
        $session->expects(self::once())->method('get');

        $tokenStorage = new TokenStorage($session);
        $tokenStorage->setToken(new Token());
        $tokenStorage->getToken();
    }
}

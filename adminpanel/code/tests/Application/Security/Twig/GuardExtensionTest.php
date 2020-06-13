<?php

declare(strict_types=1);

namespace App\Tests\Application\Security\Twig;

use App\Application\Security\Service\Token;
use App\Application\Security\Twig\GuardExtension;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * @coversDefaultClass GuardExtension
 */
final class GuardExtensionTest extends TestCase
{
    public function testAuthenticatedWhenTokenIsNull(): void
    {
        $tokenStorageInterface = $this->createMock(TokenStorageInterface::class);

        $guardExtension = new GuardExtension($tokenStorageInterface);
        $this->assertFalse($guardExtension->isAuthenticated());
    }

    /**
     * @covers ::isAuthenticated()
     */
    public function testAuthenticatedUser(): void
    {
        $token = new Token();
        $token->setAuthenticated(true);

        $tokenStorageInterface = $this->createMock(TokenStorageInterface::class);
        $tokenStorageInterface->method('getToken')->willReturn($token);

        $guardExtension = new GuardExtension($tokenStorageInterface);
        $this->assertTrue($guardExtension->isAuthenticated());
    }

    /**
     * @covers ::isAuthenticated()
     */
    public function testNotAuthenticatedUser(): void
    {
        $tokenStorageInterface = $this->createMock(TokenStorageInterface::class);
        $tokenStorageInterface->method('getToken')->willReturn(new Token());

        $guardExtension = new GuardExtension($tokenStorageInterface);
        $this->assertFalse($guardExtension->isAuthenticated());
    }
}

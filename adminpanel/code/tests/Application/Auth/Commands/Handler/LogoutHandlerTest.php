<?php

declare(strict_types=1);

namespace App\Tests\Application\Auth\Commands\Handler;

use App\Application\Auth\Commands\Command\LogoutCommand;
use App\Application\Auth\Commands\Handler\LogoutHandler;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * @coversDefaultClass LogoutHandler
 */
final class LogoutHandlerTest extends TestCase
{
    /**
     * @covers ::__invoke
     */
    public function testInvoke(): void
    {
        $tokenStorageMock = $this->createMock(TokenStorageInterface::class);
        $tokenStorageMock->expects(self::once())->method('setToken');

        $handler = new LogoutHandler($tokenStorageMock);
        $handler->__invoke(new LogoutCommand());
    }
}

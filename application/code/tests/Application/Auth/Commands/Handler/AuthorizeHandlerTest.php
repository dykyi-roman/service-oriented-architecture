<?php

declare(strict_types=1);

namespace App\Tests\Application\Auth\Commands\Handler;

use App\Application\Auth\Commands\Command\AuthorizeCommand;
use App\Application\Auth\Commands\Handler\AuthorizeHandler;
use App\Application\Security\Service\Guard;
use App\Domain\Auth\Entity\User;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

/**
 * @coversDefaultClass AuthorizeHandler
 */
final class AuthorizeHandlerTest extends TestCase
{
    /**
     * @covers ::__invoke
     */
    public function testInvoke(): void
    {
        $tokenStorageMock = $this->createMock(TokenStorage::class);
        $tokenStorageMock->expects(self::once())->method('setToken');

        $bag = $this->createMock(ParameterBagInterface::class);
        $bag->expects(self::once())->method('get')->willReturn('key');

        $guardMock = $this->createMock(Guard::class);
        $guardMock->expects(self::once())->method('verify')->willReturn(new User());

        $handler = new AuthorizeHandler($tokenStorageMock, $guardMock, $bag);
        $handler->__invoke(new AuthorizeCommand('token', 'refreshtoken'));
    }
}

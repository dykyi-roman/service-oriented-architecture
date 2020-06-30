<?php

declare(strict_types=1);

namespace App\Tests\Application\Auth\Commands\Handler;

use App\Application\Auth\Commands\Command\LoginCommand;
use App\Application\Auth\Commands\Handler\LoginHandler;
use App\Application\Auth\Exception\AppAuthException;
use App\Application\Auth\Request\LoginRequest;
use App\Domain\Auth\Exception\AuthException;
use App\Domain\Auth\Response\ApiAuthorizeResponse;
use App\Domain\Auth\Service\AuthAdapter;
use Faker\Factory;
use PHPUnit\Framework\TestCase;
use SimpleBus\SymfonyBridge\Bus\CommandBus;

/**
 * @coversDefaultClass LoginHandler
 */
final class LoginHandlerTest extends TestCase
{
    /**
     * @covers ::__invoke
     */
    public function testInvoke(): void
    {
        $faker = Factory::create();
        $token = new ApiAuthorizeResponse(['token' => 'token', 'refresh_token' => 'refresh_token']);

        $commandBusMock = $this->createMock(CommandBus::class);
        $commandBusMock->expects(self::once())->method('handle');

        $authAdapterMock = $this->createMock(AuthAdapter::class);
        $authAdapterMock->expects(self::once())->method('authorize')->willReturn($token);

        $handler = new LoginHandler($commandBusMock, $authAdapterMock);
        $handler->__invoke(new LoginCommand(new LoginRequest($faker->email, $faker->password)));
    }

    /**
     * @covers ::__invoke
     */
    public function testRaiseErrorInHandler(): void
    {
        $this->expectException(AppAuthException::class);

        $faker = Factory::create();
        $commandBusMock = $this->createMock(CommandBus::class);
        $commandBusMock->expects(self::never())->method('handle');

        $exception = AuthException::invalidCredentials('error');
        $authAdapterMock = $this->createMock(AuthAdapter::class);
        $authAdapterMock->expects(self::once())->method('authorize')->willThrowException($exception);

        $handler = new LoginHandler($commandBusMock, $authAdapterMock);
        $handler->__invoke(new LoginCommand(new LoginRequest($faker->email, $faker->password)));
    }
}

<?php

declare(strict_types=1);

namespace App\Tests\Application\Auth\Commands\Handler;

use App\Application\Auth\Commands\Command\SignUpCommand;
use App\Application\Auth\Commands\Handler\SignUpHandler;
use App\Application\Auth\Exception\AppAuthException;
use App\Application\Auth\Request\SignUpRequest;
use App\Domain\Auth\Response\ApiResponse;
use App\Domain\Auth\Service\AuthAdapter;
use Faker\Factory;
use PHPUnit\Framework\TestCase;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * @coversDefaultClass SignUpHandler
 */
final class SignUpHandlerTest extends TestCase
{
    /**
     * @covers ::__invoke
     */
    public function testInvoke(): void
    {
        $faker = Factory::create();

        $dispatcherMock = $this->createMock(EventDispatcherInterface::class);

        $response = new ApiResponse(['status' => ApiResponse::STATUS_SUCCESS, 'data' => ['uuid' => $faker->uuid]]);

        $authAdapterMock = $this->createMock(AuthAdapter::class);
        $authAdapterMock->expects(self::once())->method('signUp')->willReturn($response);

        $request = new SignUpRequest($faker->email, $faker->password, $faker->e164PhoneNumber, $faker->name, $faker->lastName);

        $handler = new SignUpHandler($authAdapterMock, $dispatcherMock);
        $handler->__invoke(new SignUpCommand($request));
    }

    /**
     * @covers ::__invoke
     */
    public function testRaiseErrorInHandler(): void
    {
        $this->expectException(AppAuthException::class);

        $dispatcherMock = $this->createMock(EventDispatcherInterface::class);

        $authAdapterMock = $this->createMock(AuthAdapter::class);
        $authAdapterMock->expects(self::never())->method('signUp');

        $request = new SignUpRequest('email', 'password', 'phone', 'name', 'last_name');

        $handler = new SignUpHandler($authAdapterMock, $dispatcherMock);
        $handler->__invoke(new SignUpCommand($request));
    }
}

<?php

declare(strict_types=1);

namespace App\Tests\Application\Auth\Commands\Handler;

use App\Application\Auth\Commands\Command\SignUpCommand;
use App\Application\Auth\Commands\Handler\SignUpHandler;
use App\Application\Auth\Exception\AppAuthException;
use App\Application\Auth\Request\SignUpRequest;
use App\Domain\Auth\AuthAdapter;
use Faker\Factory;
use PHPUnit\Framework\TestCase;

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

        $authAdapterMock = $this->createMock(AuthAdapter::class);
        $authAdapterMock->expects(self::once())->method('signUp');

        $request = new SignUpRequest($faker->email, $faker->password, $faker->name, $faker->lastName);

        $handler = new SignUpHandler($authAdapterMock);
        $handler->__invoke(new SignUpCommand($request));
    }

    /**
     * @covers ::__invoke
     */
    public function testRaiseErrorInHandler(): void
    {
        $this->expectException(AppAuthException::class);

        $authAdapterMock = $this->createMock(AuthAdapter::class);
        $authAdapterMock->expects(self::never())->method('signUp');

        $request = new SignUpRequest('email', 'password', 'name', 'last_name');

        $handler = new SignUpHandler($authAdapterMock);
        $handler->__invoke(new SignUpCommand($request));
    }
}

<?php

declare(strict_types=1);

namespace App\Tests\Domain\Auth\Service;

use App\Domain\Auth\Exception\AuthException;
use App\Domain\Auth\Response\ApiResponseInterface;
use App\Domain\Auth\Service\SignUp;
use App\Domain\Auth\ValueObject\Email;
use App\Domain\Auth\ValueObject\FullName;
use App\Domain\Auth\ValueObject\Password;
use App\Infrastructure\HttpClient\ResponseDataExtractor;
use Faker\Factory;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use Psr\Http\Client\ClientInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

/**
 * @coversDefaultClass SignUp
 */
final class SignUpTest extends TestCase
{
    /**
     * @covers ::createNewUser
     */
    public function testCreateNewUser(): void
    {
        $faker = Factory::create();

        $response = new Response(
            200,
            [],
            json_encode(
                ['status' => ApiResponseInterface::STATUS_SUCCESS, 'data' => ['test' => 'value']]
            )
        );

        $clientMock = $this->createMock(ClientInterface::class);
        $clientMock->expects(self::once())->method('sendRequest')->willReturn($response);

        $bagMock = $this->createMock(ParameterBagInterface::class);
        $bagMock->method('get')->willReturn('test-host');

        $signUp = new SignUp($clientMock, $bagMock, new ResponseDataExtractor());
        $response = $signUp->createNewUser(
            new Email($faker->email),
            new Password($faker->password),
            new FullName($faker->firstName, $faker->lastName)
        );

        $this->assertInstanceOf(ApiResponseInterface::class, $response);
        $this->assertTrue($response->isSuccess());
        $this->assertArrayHasKey('test', $response->getData());
    }

    /**
     * @covers ::createNewUser
     */
    public function testRaiseExceptionWhenCreateNewUser(): void
    {
        $this->expectException(AuthException::class);
        $faker = Factory::create();

        $exception = AuthException::unexpectedErrorInSignUpProcess('error');
        $clientMock = $this->createMock(ClientInterface::class);
        $clientMock->expects(self::once())->method('sendRequest')->willThrowException($exception);

        $bagMock = $this->createMock(ParameterBagInterface::class);
        $bagMock->method('get')->willReturn('test-host');

        $auth = new SignUp($clientMock, $bagMock, new ResponseDataExtractor());
        $auth->createNewUser(
            new Email($faker->email),
            new Password($faker->password),
            new FullName($faker->firstName, $faker->lastName)
        );
    }
}

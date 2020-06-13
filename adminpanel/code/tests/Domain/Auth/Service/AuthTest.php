<?php

declare(strict_types=1);

namespace App\Tests\Domain\Auth\Service;

use App\Domain\Auth\Exception\AuthException;
use App\Domain\Auth\Response\ApiAuthorizeResponse;
use App\Domain\Auth\Service\Auth;
use App\Domain\Auth\ValueObject\Email;
use App\Domain\Auth\ValueObject\Password;
use App\Infrastructure\HttpClient\ResponseDataExtractor;
use Faker\Factory;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use Psr\Http\Client\ClientInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

/**
 * @coversDefaultClass \App\Domain\Auth\Service\Auth
 */
final class AuthTest extends TestCase
{
    /**
     * @covers ::authorizeByEmail
     */
    public function testAuthorizeByEmail(): void
    {
        $faker = Factory::create();

        $response = new Response(200, [], json_encode(['token' => 'value', 'refresh_token' => 'value']));

        $clientMock = $this->createMock(ClientInterface::class);
        $clientMock->expects(self::once())->method('sendRequest')->willReturn($response);

        $bagMock = $this->createMock(ParameterBagInterface::class);
        $bagMock->method('get')->willReturn('test-host');

        $auth = new Auth($clientMock, $bagMock, new ResponseDataExtractor());
        $response = $auth->authorizeByEmail(new Email($faker->email), new Password($faker->password));

        $this->assertInstanceOf(ApiAuthorizeResponse::class, $response);
        $this->assertTrue($response->isSuccess());
        $this->assertArrayHasKey('token', $response->getData());
        $this->assertArrayHasKey('refresh_token', $response->getData());
    }

    /**
     * @covers ::authorizeByEmail
     */
    public function testRaiseAuthException(): void
    {
        $this->expectException(AuthException::class);
        $faker = Factory::create();

        $exception = AuthException::unexpectedErrorInAuthoriseProcess('error');
        $client = $this->createMock(ClientInterface::class);
        $client->expects(self::once())->method('sendRequest')->willThrowException($exception);

        $bag = $this->createMock(ParameterBagInterface::class);
        $bag->method('get')->willReturn('test-host');

        $auth = new Auth($client, $bag, new ResponseDataExtractor());
        $auth->authorizeByEmail(new Email($faker->email), new Password($faker->password));
    }
}

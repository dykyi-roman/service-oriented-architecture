<?php

declare(strict_types=1);

namespace App\Tests\Domain\Auth\Service;

use App\Domain\Auth\Response\ApiAuthorizeResponse;
use App\Domain\Auth\Response\ApiResponseInterface;
use App\Domain\Auth\Service\Auth;
use App\Domain\Auth\Service\AuthAdapter;
use App\Domain\Auth\Service\SignUp;
use App\Domain\Auth\ValueObject\Email;
use App\Domain\Auth\ValueObject\FullName;
use App\Domain\Auth\ValueObject\Password;
use App\Infrastructure\HttpClient\ResponseDataExtractor;
use Faker\Factory;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use Psr\Http\Client\ClientInterface;

/**
 * @coversDefaultClass AuthAdapter
 */
class AuthAdapterTest extends TestCase
{
    /**
     * @covers ::authorize
     */
    public function testAuthorizeFromAuthAdapter(): void
    {
        $faker = Factory::create();

        $response = new Response(
            200,
            [],
            json_encode(['token' => 'test-token', 'refresh_token' => 'test-refresh-token'])
        );

        $client = $this->createMock(ClientInterface::class);
        $client->expects(self::once())->method('sendRequest')->willReturn($response);

        $auth = new Auth($client, new ResponseDataExtractor(), 'test-host');
        $signUp = new SignUp($client, new ResponseDataExtractor(), 'test-host');

        $authAdapter = new AuthAdapter($auth, $signUp);
        $token = $authAdapter->authorize(new Email($faker->email), new Password($faker->password));

        $this->assertInstanceOf(ApiAuthorizeResponse::class, $token);
    }

    /**
     * @covers ::signUp
     */
    public function testSignUpFromAuthAdapter(): void
    {
        $faker = Factory::create();

        $response = new Response(
            200,
            [],
            json_encode(
                [
                    'status' => ApiResponseInterface::STATUS_SUCCESS,
                    'data' => ['uuid' => $faker->uuid]
                ]
            )
        );

        $client = $this->createMock(ClientInterface::class);
        $client->expects(self::once())->method('sendRequest')->willReturn($response);

        $auth = new Auth($client, new ResponseDataExtractor(), 'test-host');
        $signUp = new SignUp($client, new ResponseDataExtractor(), 'test-host');

        $authAdapter = new AuthAdapter($auth, $signUp);
        $response = $authAdapter->signUp(
            new Email($faker->email),
            new Password($faker->password),
            new FullName($faker->firstName, $faker->lastName)
        );

        $this->assertInstanceOf(ApiResponseInterface::class, $response);
        $this->assertTrue($response->isSuccess());
        $this->assertArrayHasKey('uuid', $response->getData());
    }
}

<?php

declare(strict_types=1);

namespace App\Tests\Domain\Auth\Response;

use App\Domain\Auth\Response\ApiAuthorizeResponse;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass ApiAuthorizeResponse
 */
final class ApiAuthorizeResponseTest extends TestCase
{
    /**
     * @covers ::getErrorCode()
     */
    public function testApiAuthorizeResponseWithEmptyTokens(): void
    {
        $response = new ApiAuthorizeResponse([]);

        $this->assertEquals(1, $response->getErrorCode());
        $this->assertEquals('Invalid credentials', $response->getErrorMessage());
        $this->assertEmpty($response->getData());
    }

    /**
     * @covers ::getData
     */
    public function testApiAuthorizeResponseWithExistTokens(): void
    {
        $response = new ApiAuthorizeResponse(['token' => 'test-token', 'refresh_token' => 'test-token']);

        $this->assertEquals(0, $response->getErrorCode());
        $this->assertEquals('', $response->getErrorMessage());
        $this->assertEquals('test-token', $response->token());
        $this->assertEquals('test-token', $response->refreshToken());
    }
}

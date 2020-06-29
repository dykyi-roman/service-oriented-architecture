<?php

declare(strict_types=1);

namespace App\Tests\Domain\Message\Response;

use App\Domain\Message\Response\ApiResponse;
use App\Domain\Message\Response\ApiResponseInterface;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \App\Domain\Auth\Response\ApiResponse
 */
final class ApiResponseTest extends TestCase
{
    /**
     * @covers ::isSuccess
     */
    public function testSuccessResponse(): void
    {
        $response = new ApiResponse(['status' => ApiResponseInterface::STATUS_SUCCESS, 'data' => ['test' => 'value']]);

        $this->assertTrue($response->isSuccess());
        $this->assertFalse($response->hasError());
        $this->assertEquals(0, $response->getErrorCode());
        $this->assertArrayHasKey('test', $response->getData());
    }

    /**
     * @covers ::getErrorMessage
     */
    public function testResponseWithError(): void
    {
        $response = new ApiResponse(
            [
                'status' => ApiResponseInterface::STATUS_ERROR,
                'code' => 1003,
                'error' => 'error message'
            ]
        );

        $this->assertTrue($response->hasError());
        $this->assertFalse($response->isSuccess());
        $this->assertEquals(1003, $response->getErrorCode());
        $this->assertEquals('error message', $response->getErrorMessage());
    }
}

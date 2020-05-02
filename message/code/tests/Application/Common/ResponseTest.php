<?php

declare(strict_types=1);

namespace App\Tests\Common\Application;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Application\Common\Response;
use App\Application\Common\Error;
use Generator;

/**
 * @coversDefaultClass Response
 */
class ResponseTest extends WebTestCase
{
    /**
     * @dataProvider successDataProvider
     * @covers ::success
     */
    public function testSuccessResponse(array $data): void
    {
        $this->assertSame(['status' => 'success', 'data' => $data], Response::success($data));
    }

    /**
     * @dataProvider errorDataProvider
     * @covers ::success
     */
    public function testErrorResponse(array $expected, string $message, int $code): void
    {
        $this->assertSame($expected, Response::error(Error::create($message, $code)));
    }

    public function errorDataProvider(): Generator
    {
        yield 'Test error response with empty data' => [['status' => 'error', 'code' => 0, 'error' => ''], '', 0];
        yield 'Test error response with data' => [['status' => 'error', 'code' => 777, 'error' => 'test'], 'test', 777];
    }

    public function successDataProvider(): Generator
    {
        yield 'Test success response with empty data' => [[]];
        yield 'Test success response with data' => [['test' => true]];
    }
}

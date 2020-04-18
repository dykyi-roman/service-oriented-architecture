<?php

declare(strict_types=1);

use App\Application\ResponseFactory;

/**
 * @coversDefaultClass ResponseFactory
 */
class ResponseFactoryTest extends TestCase
{
    /**
     * @covers ::success
     */
    public function testSuccessResponse(): void
    {
        $data = ['test' => true];

        $this->assertSame(['status' => 'success', 'data' => []], ResponseFactory::success());
        $this->assertSame(['status' => 'success', 'data' => $data], ResponseFactory::success($data));
    }

    /**
     * @covers ::success
     */
    public function testErrorResponse(): void
    {
        $this->assertSame(['status' => 'error', 'error' => ''], ResponseFactory::error(''));
        $this->assertSame(['status' => 'error', 'error' => 'test'], ResponseFactory::error('test'));
    }
}

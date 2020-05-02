<?php

declare(strict_types=1);

namespace App\Tests\Application\Template;

use App\Application\Template\Listener\ExceptionSubscriber;
use App\Application\Template\Exception\JsonSchemaException;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;

/**
 * @coversDefaultClass \App\Application\Template\Listener\ExceptionSubscriber
 */
class ExceptionSubscriberTest extends TestCase
{
    /**
     * @covers ::onKernelException
     */
    public function testOnKernelExceptionWithNotSupportedInterface(): void
    {
        $event = $this->createMock(ExceptionEvent::class);
        $event->method('getThrowable')->willReturn(new \RuntimeException());
        $event->expects(self::never())->method('setResponse');

        $subscriber = new ExceptionSubscriber();
        $subscriber->onKernelException($event);
    }

    /**
     * @covers ::onKernelException
     */
    public function testOnKernelExceptionWithSupportedInterface(): void
    {
        $message = 'test error message';
        $subscriber = new ExceptionSubscriber();
        $event = $this->createMock(ExceptionEvent::class);
        $event->method('getThrowable')->willReturn(new JsonSchemaException($message));
        $event->expects(self::once())->method('setResponse');

        $subscriber->onKernelException($event);

        $this->assertNull($event->hasResponse());
    }
}

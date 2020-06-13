<?php

declare(strict_types=1);

namespace App\Tests\Application\Security\Listener;

use App\Application\Security\Listener\ProhibitedActionForAuthorizedUserListener;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;

/**
 * @coversDefaultClass ProhibitedActionForAuthorizedUserListener
 */
final class ProhibitedActionForAuthorizedUserListenerTest extends TestCase
{
    /**
     * @covers ::__invoke()
     */
    public function testExitWhenAuthTokenIsNull(): void
    {
        $event = new RequestEvent(
            $this->createMock(HttpKernelInterface::class),
            new Request(),
            0
        );

        $listener = new ProhibitedActionForAuthorizedUserListener();
        $listener->__invoke($event);

        $this->assertNull($event->getResponse());
    }

    /**
     * @covers ::__invoke()
     */
    public function testSetRedirectResponse(): void
    {
        $requset = new Request();
        $requset->attributes->set('security', 'no');
        $requset->attributes->set('_route', 'test-route');
        $requset->cookies->set('auth-token', 'test');

        $event = new RequestEvent(
            $this->createMock(HttpKernelInterface::class),
            $requset,
            0
        );

        $listener = new ProhibitedActionForAuthorizedUserListener();
        $listener->__invoke($event);

        $this->assertInstanceOf(RedirectResponse::class, $event->getResponse());
    }
}

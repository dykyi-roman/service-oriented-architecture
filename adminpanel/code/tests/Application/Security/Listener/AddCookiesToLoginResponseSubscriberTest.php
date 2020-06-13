<?php

declare(strict_types=1);

namespace App\Tests\Application\Security\Listener;

use App\Application\Security\Listener\AddCookiesToLoginResponseSubscriber;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

/**
 * @coversDefaultClass AddCookiesToLoginResponseSubscriber
 */
final class AddCookiesToLoginResponseSubscriberTest extends TestCase
{
    /**
     * @covers ::onKernelResponse
     */
    public function testExitWhenRouteIsNull(): void
    {
        $event = new ResponseEvent(
            $this->createMock(HttpKernelInterface::class),
            new Request(),
            0,
            new Response()
        );

        $tokenStorageMock = $this->createMock(TokenStorageInterface::class);
        $tokenStorageMock->expects(self::never())->method('getToken')->willReturn(null);

        $subscriber = new AddCookiesToLoginResponseSubscriber($tokenStorageMock);
        $subscriber->onKernelResponse($event);

        $cookies = $event->getResponse()->headers->getCookies();
        $this->assertCount(0, $cookies);
    }

    /**
     * @covers ::onKernelResponse
     */
    public function testExitWhenRouteNameIsNotWebLoginPost(): void
    {
        $request = new Request();
        $request->attributes->set('_route', 'test.route.name');

        $event = new ResponseEvent(
            $this->createMock(HttpKernelInterface::class),
            $request,
            0,
            new Response()
        );

        $tokenStorageMock = $this->createMock(TokenStorageInterface::class);
        $tokenStorageMock->expects(self::never())->method('getToken')->willReturn(null);

        $subscriber = new AddCookiesToLoginResponseSubscriber($tokenStorageMock);
        $subscriber->onKernelResponse($event);

        $cookies = $event->getResponse()->headers->getCookies();
        $this->assertCount(0, $cookies);
    }

    /**
     * @covers ::onKernelResponse
     */
    public function testExitWhenTokenIsNull(): void
    {
        $request = new Request();
        $request->attributes->set('_route', 'web.login.post');

        $event = new ResponseEvent(
            $this->createMock(HttpKernelInterface::class),
            $request,
            0,
            new Response()
        );

        $tokenStorageMock = $this->createMock(TokenStorageInterface::class);
        $tokenStorageMock->expects(self::once())->method('getToken')->willReturn(null);

        $subscriber = new AddCookiesToLoginResponseSubscriber($tokenStorageMock);
        $subscriber->onKernelResponse($event);

        $cookies = $event->getResponse()->headers->getCookies();
        $this->assertCount(0, $cookies);
    }

    /**
     * @covers ::onKernelResponse
     */
    public function testPersistCookieInResponseHeader(): void
    {
        $request = new Request();
        $request->attributes->set('_route', 'web.login.post');

        $event = new ResponseEvent(
            $this->createMock(HttpKernelInterface::class),
            $request,
            0,
            new Response()
        );

        $token = $this->createMock(TokenInterface::class);
        $token->expects(self::once())->method('getCredentials')->willReturn('credential');

        $tokenStorageMock = $this->createMock(TokenStorageInterface::class);
        $tokenStorageMock->expects(self::once())->method('getToken')->willReturn($token);

        $subscriber = new AddCookiesToLoginResponseSubscriber($tokenStorageMock);
        $subscriber->onKernelResponse($event);

        $cookies = $event->getResponse()->headers->getCookies();
        $this->assertCount(1, $cookies);
    }
}

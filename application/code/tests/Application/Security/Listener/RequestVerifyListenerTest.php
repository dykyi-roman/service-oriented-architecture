<?php

declare(strict_types=1);

namespace App\Tests\Application\Security\Listener;

use App\Application\Security\Listener\RequestVerifyListener;
use App\Application\Security\Service\Guard;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * @coversDefaultClass RequestVerifyListener
 */
final class RequestVerifyListenerTest extends TestCase
{
    /**
     * @covers ::__invoke()
     */
    public function testExitWhenRequestAttributesSecurityEqualNo(): void
    {
        $guardMock = $this->createMock(Guard::class);
        $guardMock->expects(self::never())->method('verify');

        $tokenStorageMock = $this->createMock(TokenStorageInterface::class);
        $tokenStorageMock->expects(self::never())->method('setToken');

        $bagMock = $this->createMock(ParameterBagInterface::class);

        $request = new Request();
        $request->attributes->set('security', 'no');

        $event = new RequestEvent(
            $this->createMock(HttpKernelInterface::class),
            $request,
            0
        );

        $listener = new RequestVerifyListener($guardMock, $tokenStorageMock, $bagMock);
        $listener->__invoke($event);
    }

    /**
     * @covers ::__invoke()
     */
    public function testExitWhenRequestCookiesAuthTokenIsNull(): void
    {
        $guardMock = $this->createMock(Guard::class);
        $guardMock->expects(self::never())->method('verify');

        $tokenStorageMock = $this->createMock(TokenStorageInterface::class);
        $tokenStorageMock->expects(self::once())->method('setToken');

        $bagMock = $this->createMock(ParameterBagInterface::class);

        $request = new Request();
        $request->attributes->set('security', 'yes');

        $event = new RequestEvent(
            $this->createMock(HttpKernelInterface::class),
            $request,
            0
        );

        $listener = new RequestVerifyListener($guardMock, $tokenStorageMock, $bagMock);
        $listener->__invoke($event);

        $this->assertInstanceOf(RedirectResponse::class, $event->getResponse());
    }

    /**
     * @covers ::__invoke()
     */
    public function testSuccessGuardVerify(): void
    {
        $guardMock = $this->createMock(Guard::class);
        $guardMock->expects(self::once())->method('verify');

        $tokenStorageMock = $this->createMock(TokenStorageInterface::class);
        $tokenStorageMock->expects(self::never())->method('setToken');

        $bagMock = $this->createMock(ParameterBagInterface::class);
        $bagMock->expects(self::once())->method('get')->willReturn('test');

        $request = new Request();
        $request->attributes->set('security', 'yes');
        $request->cookies->set('auth-token', 'test');

        $event = new RequestEvent(
            $this->createMock(HttpKernelInterface::class),
            $request,
            0
        );

        $listener = new RequestVerifyListener($guardMock, $tokenStorageMock, $bagMock);
        $listener->__invoke($event);
    }
}

<?php

declare(strict_types=1);

namespace App\Application\Security\Listener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

final class AddCookiesToLoginResponseSubscriber implements EventSubscriberInterface
{
    private const TOKEN_LIFE_TIME = 3600;

    private TokenStorageInterface $tokenStorage;

    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::RESPONSE => 'onKernelResponse'
        ];
    }

    public function onKernelResponse(ResponseEvent $event): void
    {
        if (null === $routeName = $event->getRequest()->attributes->get('_route')) {
            return;
        }

        if ($routeName !== 'web.login.post') {
            return;
        }

        $token = $this->tokenStorage->getToken();
        if (null === $token) {
            return;
        }

        $cookie = new Cookie('auth-token', $token->getCredentials(), time() + self::TOKEN_LIFE_TIME);
        $response = $event->getResponse();
        $response->headers->setCookie($cookie);
        $event->setResponse($response);
    }
}

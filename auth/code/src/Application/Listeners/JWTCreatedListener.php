<?php

declare(strict_types=1);

namespace App\Application\Listeners;

use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTCreatedEvent;
use Symfony\Component\HttpFoundation\RequestStack;

final class JWTCreatedListener
{
    private RequestStack $requestStack;

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    public function onJWTCreated(JWTCreatedEvent $event): void
    {
        $request = $this->requestStack->getCurrentRequest();
        $payload = $event->getData();

        $payload['ip'] = null === $request ? null : $request->getClientIp();
        $payload['id'] = $event->getUser()->getId();

        $event->setData($payload);
    }
}

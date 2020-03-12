<?php

declare(strict_types=1);

namespace App\Application\Listeners;

use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTDecodedEvent;
use RuntimeException;
use Symfony\Component\HttpFoundation\RequestStack;

final class JWTDecodedListener
{
    private RequestStack $requestStack;

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    public function onJWTDecoded(JWTDecodedEvent $event): void
    {
        dump(22); die();
        $request = $this->requestStack->getCurrentRequest();
        if (null === $request) {
            throw new RuntimeException('Not found current request');
        }

        $payload = $event->getPayload();
        if (!isset($payload['ip']) || $payload['ip'] !== $request->getClientIp()) {
            $event->markAsInvalid();
        }
    }
}

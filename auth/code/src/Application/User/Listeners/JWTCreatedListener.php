<?php

declare(strict_types=1);

namespace App\Application\User\Listeners;

use App\Infrastructure\Metrics\MetricsInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTCreatedEvent;
use Symfony\Component\HttpFoundation\RequestStack;

final class JWTCreatedListener
{
    private RequestStack $requestStack;
    private MetricsInterface $metrics;

    public function __construct(RequestStack $requestStack, MetricsInterface $metrics)
    {
        $this->requestStack = $requestStack;
        $this->metrics = $metrics;
    }

    public function onJWTCreated(JWTCreatedEvent $event): void
    {
        $request = $this->requestStack->getCurrentRequest();
        $payload = $event->getData();

        $user = $event->getUser();

        $payload['email'] = $user->getEmail();
        $payload['phone'] = $user->getPhone();
        $payload['ip'] = null === $request ? null : $request->getClientIp();
        $payload['id'] = $event->getUser()->getId();

        $event->setData($payload);

        $this->metrics->inc('jwt_create');
    }
}

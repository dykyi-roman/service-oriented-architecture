<?php

declare(strict_types=1);

namespace App\Application\Security\Listener;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\RequestEvent;

final class ProhibitedActionForAuthorizedUserListener
{
    public function __invoke(RequestEvent $event)
    {
        $request = $event->getRequest();
        if (null === $routeName = $request->attributes->get('_route')) {
            return;
        }

        $authToken = $request->cookies->get('auth-token');
        if (null === $authToken) {
            return;
        }

        if ('no' === $request->attributes->get('security')) {
            $event->setResponse(RedirectResponse::create('/'));
            return;
        }
    }
}

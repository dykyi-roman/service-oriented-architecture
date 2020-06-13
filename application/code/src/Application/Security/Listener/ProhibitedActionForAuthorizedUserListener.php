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
        $authToken = $request->cookies->get('auth-token');
        if (null === $authToken) {
            return;
        }

        if (null === $routeName = $event->getRequest()->attributes->get('_route')) {
            return;
        }

        if ($routeName === 'web.index') {
            return;
        }

        if ('no' === $request->attributes->get('security')) {
            $event->setResponse(RedirectResponse::create('/'));
            return;
        }
    }
}

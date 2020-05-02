<?php

declare(strict_types=1);

namespace App\Application\User\Listeners;

use Symfony\Component\HttpKernel\Event\ResponseEvent;

final class PreflightIgnoreOnNewRelicListener
{
    public function onKernelResponse(ResponseEvent $event): void
    {
        if (!extension_loaded('newrelic')) {
            return;
        }

        if ('OPTIONS' === $event->getRequest()->getMethod()) {
            newrelic_ignore_transaction();
        }
    }
}

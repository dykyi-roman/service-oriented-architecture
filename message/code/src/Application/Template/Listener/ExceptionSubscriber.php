<?php

declare(strict_types=1);

namespace App\Application\Template\Listener;

use App\Application\Common\Error;
use App\Application\Common\Response;
use App\Application\Template\Exception\JsonSchemaException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class ExceptionSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::EXCEPTION => 'onKernelException'
        ];
    }

    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();
        if (!$exception instanceof JsonSchemaException) {
            return;
        }

        $error = Error::create($exception->getMessage(), $exception->getCode());
        $response = new JsonResponse(Response::error($error), 500);
        $response->headers->set('Content-Type', 'application/json');

        $event->setResponse($response);
    }
}

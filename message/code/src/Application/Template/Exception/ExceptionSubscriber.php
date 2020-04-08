<?php

declare(strict_types=1);

namespace App\Application\Template\Exception;

use App\Application\Template\ResponseTemplateFactory;
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

        $response = new JsonResponse(ResponseTemplateFactory::error($exception->getMessage()), 500);
        $response->headers->set('Content-Type', 'application/json');

        $event->setResponse($response);
    }
}

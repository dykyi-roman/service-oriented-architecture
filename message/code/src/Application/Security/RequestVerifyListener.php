<?php

declare(strict_types=1);

namespace App\Application\Security;

use App\Application\Common\Error;
use App\Application\Common\Response;
use App\Domain\Auth\Exception\AuthException;
use Exception;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\RequestEvent;

final class RequestVerifyListener
{
    private Guard $guard;
    private ParameterBagInterface $bag;

    public function __construct(Guard $guard, ParameterBagInterface $bag)
    {
        $this->bag = $bag;
        $this->guard = $guard;
    }

    public function __invoke(RequestEvent $event)
    {
        if ('dev' === $this->bag->get('APP_ENV')) {
            return;
        }

        $request = $event->getRequest();
        if (!$routeName = $request->attributes->get('_route')) {
            return;
        }

        if (false === mb_strpos($routeName, 'api.')) {
            return;
        }

        try {
            $token = $request->headers->get('auth-token');
            if (null === $token) {
                throw AuthException::tokenIsNotFoundInHeaders('auth-token');
            }

            $this->guard->verify($token, $this->bag->get('JWT_PUBLIC_KEY'));

            return;
        } catch (AuthException $exception) {
            $event->setResponse($this->createResponseWithError($exception));
        }
    }

    private function createResponseWithError(Exception $exception): JsonResponse
    {
        $error = Error::create($exception->getMessage(), $exception->getCode());
        $response = new JsonResponse(Response::error($error), 401);
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }
}

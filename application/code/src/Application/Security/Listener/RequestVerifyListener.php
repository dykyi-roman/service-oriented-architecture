<?php

declare(strict_types=1);

namespace App\Application\Security\Listener;

use App\Application\Security\Service\Guard;
use App\Domain\Auth\Exception\AuthException;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

final class RequestVerifyListener
{
    private Guard $guard;
    private ParameterBagInterface $bag;
    private TokenStorageInterface $tokenStorage;

    public function __construct(Guard $guard, TokenStorageInterface $tokenStorage, ParameterBagInterface $bag)
    {
        $this->bag = $bag;
        $this->guard = $guard;
        $this->tokenStorage = $tokenStorage;
    }

    public function __invoke(RequestEvent $event)
    {
        $request = $event->getRequest();
        if ('no' === $request->attributes->get('security')) {
            return;
        }

        try {
            $authToken = $request->cookies->get('auth-token');
            if (null === $authToken) {
                throw AuthException::tokenIsNotFoundInCookies('auth-token');
            }

            $this->guard->verify($authToken, $this->bag->get('JWT_PUBLIC_KEY'));
        } catch (AuthException $exception) {
            $this->tokenStorage->setToken(null);
            $event->setResponse(RedirectResponse::create('/'));
        }
    }
}

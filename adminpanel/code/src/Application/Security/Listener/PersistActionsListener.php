<?php

declare(strict_types=1);

namespace App\Application\Security\Listener;

use Psr\Log\LoggerInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\User\UserInterface;

final class PersistActionsListener
{
    private LoggerInterface $logger;
    private TokenStorageInterface $tokenStorage;

    public function __construct(TokenStorageInterface $tokenStorage, LoggerInterface $logger)
    {
        $this->logger = $logger;
        $this->tokenStorage = $tokenStorage;
    }

    public function __invoke(RequestEvent $event)
    {
        $user = null;
        $token = $this->tokenStorage->getToken();
        if ($token) {
            $user = $token->getUser();
        }

        if ($user instanceof UserInterface) {
            return;
        }

        $request = $event->getRequest();
        if (null === $routeName = $request->attributes->get('_route')) {
            return;
        }

        $this->logger->info(
            'Application::Admin::Actions',
            [
                'user' => $user->getUsername(),
                'route' => $routeName,
            ]
        );
    }
}

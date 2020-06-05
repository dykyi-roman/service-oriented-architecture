<?php

declare(strict_types=1);

namespace App\UI\Http\Web;

use App\Application\Auth\Commands\Command\LoginCommand;
use App\Application\Auth\Commands\Command\LogoutCommand;
use App\Application\Auth\Commands\Command\SignUpCommand;
use App\Application\Auth\Exception\AppAuthException;
use App\Application\Auth\Request\LoginRequest;
use App\Application\Auth\Request\SignUpRequest;
use Psr\Log\LoggerInterface;
use SimpleBus\SymfonyBridge\Bus\CommandBus;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Routing\Annotation\Route;

class SecurityController extends AbstractController
{
    /**
     * @Route(
     *     path="/login",
     *     methods={"GET"},
     *     name="web.login",
     *     defaults={"security" = "no", "onlyForNotAuthorized" = "yes"})
     */
    public function loginAction(): Response
    {
        return $this->render('security/login.html.twig');
    }

    /**
     * @Route(path="/login", methods={"POST"}, name="web.login.post", defaults={"security" = "no"})
     */
    public function loginPostAction(
        Request $request,
        CommandBus $commandBus,
        FlashBagInterface $flashBag,
        LoggerInterface $logger
    ): Response {
        try {
            $request = new LoginRequest(
                $request->get('login', ''),
                $request->get('password', '')
            );
            $commandBus->handle(new LoginCommand($request));
            return $this->redirectToRoute('web.index');
        } catch (AppAuthException $exception) {
            $logger->error('App::SecurityController::loginPostAction', ['error' => $exception->getMessage()]);
            $flashBag->add('error', 'web.login.error.code.' . $exception->getCode());
        }

        return $this->redirectToRoute('web.login');
    }

    /**
     * @Route(path="/sign-up", methods={"POST"}, name="web.sign-up.post", defaults={"security" = "no"})
     */
    public function signUpPostAction(
        Request $request,
        CommandBus $commandBus,
        FlashBagInterface $flashBag,
        LoggerInterface $logger
    ): Response {
        try {
            $request = new SignUpRequest(
                $request->get('email', ''),
                $request->get('password', ''),
                $request->get('phone', ''),
                $request->get('firstName', ''),
                $request->get('lastName', '')
            );

            $commandBus->handle(new SignUpCommand($request));
            $flashBag->add('success', 'web.sign-up.success');

            return $this->redirectToRoute('web.index');
        } catch (AppAuthException $exception) {
            $logger->error('App::SecurityController::signUpPostAction', ['error' => $exception->getMessage()]);
            $flashBag->add('error', 'web.sign-up.error.code.' . $exception->getCode());
        }
    }

    /**
     * @Route(path="/logout", methods={"GET"}, name="web.logout")
     */
    public function logoutAction(CommandBus $commandBus): RedirectResponse
    {
        $commandBus->handle(new LogoutCommand());

        $response = $this->redirectToRoute('web.index');
        $response->headers->setCookie(new Cookie('auth-token', null));

        return $response;
    }
}

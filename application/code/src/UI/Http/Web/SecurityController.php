<?php

declare(strict_types=1);

namespace App\UI\Http\Web;

use App\Application\Auth\Command\LogoutUserCommand;
use App\Application\Auth\Exception\AppAuthException;
use App\Application\Auth\Request\LoginRequest;
use App\Application\Auth\Service\LoginUser;
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
     * @Route(path="/login", methods={"GET"}, name="web.login", defaults={"security" = "no"})
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
        LoginUser $loginUser,
        FlashBagInterface $flashBag,
        LoggerInterface $logger
    ): Response {
        try {
            $loginUser->login(
                new LoginRequest(
                    $request->get('login', ''),
                    $request->get('password', '')
                )
            );
            return $this->redirectToRoute('web.index');
        } catch (AppAuthException $exception) {
            $logger->error('SecurityController::loginAction', ['error' => $exception->getMessage()]);
            $flashBag->add('error', 'web.login.error.code.' . $exception->getCode());
        }

        return $this->render('security/login.html.twig');
    }

    /**
     * @Route(path="/registration", methods={"GET"}, name="web.registration", defaults={"security" = "no"})
     */
    public function registrationAction(): RedirectResponse
    {
        return $this->redirectToRoute('web.index');
    }

    /**
     * @Route(path="/registration", methods={"POST"}, name="web.registration.post", defaults={"security" = "no"})
     */
    public function registrationPostAction(FlashBagInterface $flashBag): RedirectResponse
    {
        $flashBag->add('success', 'web.login.success');

        return $this->redirectToRoute('web.index');
    }

    /**
     * @Route(path="/logout", methods={"GET"}, name="web.logout", defaults={"security" = "no"})
     */
    public function logoutAction(CommandBus $commandBus): RedirectResponse
    {
        $commandBus->handle(new LogoutUserCommand());

        $response = $this->redirectToRoute('web.index');
        $response->headers->setCookie(new Cookie('auth-token', null));

        return $response;
    }
}

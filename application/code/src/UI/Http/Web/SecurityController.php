<?php

declare(strict_types=1);

namespace App\UI\Http\Web;

use App\Application\Auth\Commands\Command\LogoutUserCommand;
use App\Application\Auth\Exception\AppAuthException;
use App\Application\Auth\Request\LoginRequest;
use App\Application\Auth\Request\SignUpRequest;
use App\Application\Auth\Service\LoginUser;
use App\Application\Auth\Service\SignUpUser;
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
     * @Route(path="/login", methods={"GET"}, name="web.login", defaults={"security" = "no", "onlyForNotAuthorized" = "yes"})
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
            $logger->error('App::SecurityController::loginPostAction', ['error' => $exception->getMessage()]);
            $flashBag->add('error', 'web.login.error.code.' . $exception->getCode());
        }

        return $this->redirectToRoute('web.login');
    }

    /**
     * @Route(path="/sign-up", methods={"GET"}, name="web.sign-up", defaults={"security" = "no"})
     */
    public function signUpAction(): Response
    {
        return $this->render('security/sigh-up.html.twig');
    }

    /**
     * @Route(path="/sign-up", methods={"POST"}, name="web.sign-up.post", defaults={"security" = "no"})
     */
    public function signUpPostAction(
        Request $request,
        SignUpUser $signUp,
        FlashBagInterface $flashBag,
        LoggerInterface $logger
    ): Response {
        try {
            $signUp->signUp(
                new SignUpRequest(
                    $request->get('email', ''),
                    $request->get('password', ''),
                    $request->get('phone', ''),
                    $request->get('firstName', ''),
                    $request->get('lastName', '')
                )
            );
            $flashBag->add('success', 'web.sign-up.success');

            return $this->redirectToRoute('web.index');
        } catch (AppAuthException $exception) {
            $logger->error('App::SecurityController::signUpPostAction', ['error' => $exception->getMessage()]);
            $flashBag->add('error', 'web.sign-up.error.code.' . $exception->getCode());
        }

        return $this->redirectToRoute('web.sign-up');
    }

    /**
     * @Route(path="/logout", methods={"GET"}, name="web.logout")
     */
    public function logoutAction(CommandBus $commandBus): RedirectResponse
    {
        $commandBus->handle(new LogoutUserCommand());

        $response = $this->redirectToRoute('web.index');
        $response->headers->setCookie(new Cookie('auth-token', null));

        return $response;
    }
}

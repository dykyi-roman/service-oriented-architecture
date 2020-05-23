<?php

declare(strict_types=1);

namespace App\UI\Http\Web;

use App\Application\Auth\Commands\Command\ForgotPasswordCommand;
use App\Application\Auth\Commands\Command\RestorePasswordCommand;
use App\Application\Auth\Exception\AppAuthException;
use App\Application\Auth\Request\RestorePasswordRequest;
use App\Domain\Auth\Exception\AuthException;
use App\Domain\Message\Exception\MessageException;
use Psr\Log\LoggerInterface;
use SimpleBus\SymfonyBridge\Bus\CommandBus;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Routing\Annotation\Route;

class PasswordController extends AbstractController
{
    /**
     * @Route(path="/password/forgot", methods={"GET"}, name="web.password.forgot", defaults={"security" = "no"})
     */
    public function forgotAction(): Response
    {
        return $this->render('password/forgot.html.twig');
    }

    /**
     * @Route(path="/password/forgot", methods={"POST"}, name="web.password.forgot.post", defaults={"security" = "no"})
     */
    public function forgotPostAction(
        Request $request,
        CommandBus $commandBus,
        FlashBagInterface $flashBag,
        LoggerInterface $logger
    ): Response {
        try {
            $commandBus->handle(new ForgotPasswordCommand($request->get('contact', '')));

            return $this->render('password/restore.html.twig', ['contact' => $request->get('contact')]);
        } catch (MessageException $exception) {
            $logger->error('App::PasswordController::forgotPostAction', ['error' => $exception->getMessage()]);
            $flashBag->add('error', 'web.password.forgot.error.code.' . $exception->getCode());
        }

        return $this->render('password/forgot.html.twig');
    }

    /**
     * @Route(path="/password/restore", methods={"POST"}, name="web.password.restore.post", defaults={"security" = "no"})
     */
    public function restorePostAction(
        Request $request,
        CommandBus $commandBus,
        FlashBagInterface $flashBag,
        LoggerInterface $logger
    ): Response {
        try {
            $request = new RestorePasswordRequest(
                $request->get('contact', ''),
                $request->get('code', ''),
                $request->get('password', ''),
            );

            $commandBus->handle(new RestorePasswordCommand($request));
            $flashBag->add('success', 'web.password.restore.success');
        } catch (AppAuthException $exception) {
            $logger->error('App::PasswordController::restorePostAction', ['error' => $exception->getMessage()]);
            $flashBag->add('error', 'web.password.restore.error.code.' . $exception->getCode());
        }

        return $this->redirectToRoute('web.index');
    }
}

<?php

declare(strict_types=1);

namespace App\UI\Http\Web;

use App\Application\Auth\Commands\Command\ForgotPasswordCommand;
use App\Application\Auth\Commands\Command\RestorePasswordCommand;
use App\Application\Auth\Exception\AppAuthException;
use App\Application\Auth\Request\RestorePasswordRequest;
use App\Application\Common\Response;
use App\Domain\Message\Exception\MessageException;
use Psr\Log\LoggerInterface;
use SimpleBus\SymfonyBridge\Bus\CommandBus;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Routing\Annotation\Route;

class PasswordController extends AbstractController
{
    private CommandBus $commandBus;
    private LoggerInterface $logger;
    private FlashBagInterface $flashBag;

    public function __construct(CommandBus $commandBus, FlashBagInterface $flashBag, LoggerInterface $logger)
    {
        $this->commandBus = $commandBus;
        $this->flashBag = $flashBag;
        $this->logger = $logger;
    }

    /**
     * @Route(path="/password/forgot", methods={"GET"}, name="web.password.forgot", defaults={"security" = "no"})
     */
    public function forgotAction(): SymfonyResponse
    {
        return $this->render('password/forgot.html.twig');
    }

    /**
     * @Route(path="/password/forgot", methods={"POST"}, name="web.password.forgot.post", defaults={"security" = "no"})
     */
    public function forgotPostAction(Request $request): SymfonyResponse
    {
        try {
            $this->commandBus->handle(new ForgotPasswordCommand($request->get('contact', '')));

            return $this->render('password/restore.html.twig', ['contact' => $request->get('contact')]);
        } catch (MessageException $exception) {
            $this->logger->error('App::PasswordController::forgotPostAction', ['error' => $exception->getMessage()]);
            $this->flashBag->add('error', 'web.password.forgot.error.code.' . $exception->getCode());
        }

        return $this->render('password/forgot.html.twig');
    }

    /**
     * @Route(
     *     path="/password/restore",
     *     methods={"POST"},
     *     name="web.password.restore.post",
     *     defaults={"security" = "no"}
     *     )
     */
    public function restorePostAction(Request $request): SymfonyResponse
    {
        try {
            $request = new RestorePasswordRequest(
                $request->get('contact', ''),
                $request->get('code', ''),
                $request->get('password', ''),
            );
            $this->commandBus->handle(new RestorePasswordCommand($request));
            $this->flashBag->add('success', 'web.password.restore.success');

            return $this->redirectToRoute('web.index');
        } catch (\Throwable $exception) {
            $this->logger->error('App::PasswordController::restorePostAction', ['error' => $exception->getMessage()]);
            $this->flashBag->add('error', 'web.password.restore.error.code.' . $exception->getCode());
        }

        return $this->render('password/restore.html.twig', ['contact' => $request->get('contact')]);
    }

    /**
     * @Route(
     *     path="/password/restore/code-resend",
     *     methods={"POST"},
     *     name="web.password.restore.code.resend.post",
     *     defaults={"security" = "no", "ajax" = "yes"}
     *     )
     */
    public function resendCodeAction(Request $request, CommandBus $commandBus): JsonResponse
    {
        try {
            $commandBus->handle(new ForgotPasswordCommand($request->get('contact', '')));
        } catch (MessageException $exception) {
            $this->logger->error('App::PasswordController::resendCodeAction', ['error' => $exception->getMessage()]);
            $this->flashBag->add('error', 'web.password.forgot.resend.error.code.' . $exception->getCode());
        }

        return JsonResponse::create(Response::success([]));
    }
}

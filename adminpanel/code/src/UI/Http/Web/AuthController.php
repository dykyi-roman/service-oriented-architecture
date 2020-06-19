<?php

declare(strict_types=1);

namespace App\UI\Http\Web;

use App\Domain\Storage\DTO\FileDTO;
use App\Application\Common\Exception\LoggerException;
use App\Domain\Auth\AuthAdapter;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Routing\Annotation\Route;
use Throwable;

class AuthController extends AbstractController
{
    private LoggerInterface $logger;
    private FlashBagInterface $flashBag;

    public function __construct(LoggerInterface $logger, FlashBagInterface $flashBag)
    {
        $this->logger = $logger;
        $this->flashBag = $flashBag;
    }

    /**
     * @Route(path="/auth", name="auth.users.get", defaults={"security" = "yes"})
     */
    public function getUsers(AuthAdapter $authAdapter): Response
    {
        try {
            $response = $authAdapter->allUsers();
            $users = array_map((fn(array $user) => new FileDTO($user)), $response->getData());

            return $this->render('auth/list.html.twig', ['users' => $users]);
        } catch (Throwable $exception) {
            $this->logger->error(...LoggerException::log(__METHOD__, $exception->getMessage()));
            $this->flashBag->add('error', 'web.auth.update.error.code.' . $exception->getCode());

            return $this->redirectToRoute('web.index');
        }
    }

    /**
     * @Route(path="/auth/{id}", methods={"GET"}, name="auth.user.get", defaults={"security" = "yes"})
     */
    public function getOneUser(string $id, AuthAdapter $authAdapter): Response
    {
        try {
            $response = $authAdapter->getUser($id);

            return $this->render('auth/item.html.twig', ['user' => new FileDTO($response->getData())]);
        } catch (Throwable $exception) {
            $this->logger->error(...LoggerException::log(__METHOD__, $exception->getMessage()));
            $this->flashBag->add('error', 'web.auth.update.error.code.' . $exception->getCode());

            return $this->redirectToRoute('web.index');
        }
    }

    /**
     * @Route(path="/auth/{id}", methods={"POST"}, name="auth.user.update", defaults={"security" = "yes"})
     */
    public function updateUser(string $id, Request $request, AuthAdapter $authAdapter): RedirectResponse
    {
        try {
            $authAdapter->updateUser($id, $request->request->all());

            return $this->redirectToRoute('auth.user.get', ['id' => $id]);
        } catch (Throwable $exception) {
            $this->logger->error(...LoggerException::log(__METHOD__, $exception->getMessage()));
            $this->flashBag->add('error', 'web.auth.update.error.code.' . $exception->getCode());

            return $this->redirectToRoute('web.index');
        }
    }
}

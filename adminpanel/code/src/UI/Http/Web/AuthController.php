<?php

declare(strict_types=1);

namespace App\UI\Http\Web;

use App\Application\Auth\DTO\UserDTO;
use App\Domain\Auth\AuthAdapter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AuthController extends AbstractController
{
    /**
     * @Route(path="/auth", name="auth.users.get", defaults={"security" = "yes"})
     */
    public function getUsers(AuthAdapter $authAdapter): Response
    {
        $response = $authAdapter->allUsers();
        $users = array_map((fn(array $user) => new UserDTO($user)), $response->getData());

        return $this->render('auth/list.html.twig', ['users' => $users]);
    }

    /**
     * @Route(path="/auth/{id}", methods={"GET"}, name="auth.user.get", defaults={"security" = "yes"})
     */
    public function getOneUser(string $id, AuthAdapter $authAdapter): Response
    {
        $response = $authAdapter->getUser($id);

        return $this->render('auth/item.html.twig', ['user' => new UserDTO($response->getData())]);
    }

    /**
     * @Route(path="/auth/{id}", methods={"POST"}, name="auth.user.update", defaults={"security" = "yes"})
     */
    public function updateUser(string $id, Request $request, AuthAdapter $authAdapter): RedirectResponse
    {
        $authAdapter->updateUser($id, $request->request->all());

        return $this->redirectToRoute('auth.user.get', ['id' => $id]);
    }
}

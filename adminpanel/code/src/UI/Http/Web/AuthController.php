<?php

declare(strict_types=1);

namespace App\UI\Http\Web;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AuthController extends AbstractController
{
    /**
     * @Route(path="/auth", name="auth.all.index", defaults={"security" = "yes"})
     */
    public function list(): Response
    {
        return $this->render('auth/list.html.twig');
    }
}

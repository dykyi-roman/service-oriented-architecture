<?php

namespace App\UI\Http\Web;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    /**
     * @Route(path="/", name="web.index", defaults={"security" = "no"})
     */
    public function index(): Response
    {
        return $this->render('index/index.html.twig');
    }
}

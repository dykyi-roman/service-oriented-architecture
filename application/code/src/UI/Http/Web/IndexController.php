<?php

namespace App\UI\Http\Web;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    /**
     * @Route(path="/", name="main_page")
     */
    public function index()
    {
        dump('stop'); die();
    }
}
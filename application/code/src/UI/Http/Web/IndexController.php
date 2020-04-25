<?php

namespace App\UI\Http\Web;

use App\Domain\Auth\Service\JWTAuth;
use App\Domain\Auth\Service\JWTGuard;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    /**
     * @Route(path="/", name="main_page")
     */
    public function test(ParameterBagInterface $bag, JWTAuth $auth, JWTGuard $guard)
    {
        $guard->downloadPublicKey($bag->get('JWT_PUBLIC_KEY'));
        $token = $auth->authorizeByEmail('test1587643773@gmail.com', 'test');
        $user = $guard->verify($token['token'], $bag->get('JWT_PUBLIC_KEY'));
        dump($user);
        die();
    }
}

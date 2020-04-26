<?php

namespace App\UI\Http\Web;

use App\Domain\Auth\AuthAdapter;
use App\Domain\Auth\Service\Guard;
use App\Domain\Message\MessageAdapter;
use App\Domain\Storage\StorageAdapter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    /**
     * @Route(path="/", name="test_page")
     */
    public function test(
        ParameterBagInterface $bag,
        AuthAdapter $auth,
        MessageAdapter $messageAdapter,
        StorageAdapter $storageAdapter,
        Guard $guard
    ) {
        // Auth service
        $token = $auth->authorize('test1587643773@gmail.com', 'test');
        $user = $guard->verify($token['token'], $bag->get('JWT_PUBLIC_KEY'));
        // Message service
        $messageAdapter->sendWelcomeMessage($user->id);
        //Storage Service
        $uploadFile = $storageAdapter->uploadFile(tempnam(sys_get_temp_dir(), 'Tux'), 'txt');
        $file = $storageAdapter->downloadFile($uploadFile['data'][0]['payload']['path']);

        dump($user, $file);
        die();
    }
}

<?php

namespace App\UI\Http\Web;

use App\Domain\Message\MessageAdapter;
use App\Domain\Storage\StorageAdapter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class IndexController extends AbstractController
{
    /**
     * @Route(path="/", name="web.index", defaults={"security" = "no"})
     */
    public function test(
        MessageAdapter $messageAdapter,
        StorageAdapter $storageAdapter,
        TokenStorageInterface $tokenStorage
    ): Response {
        // Message service
//        $messageAdapter->sendWelcomeMessage($user->id);
//        Storage Service
//        $uploadFile = $storageAdapter->uploadFile(tempnam(sys_get_temp_dir(), 'Tux'), 'txt');
//        $file = $storageAdapter->downloadFile($uploadFile['data'][0]['payload']['path']);

        return $this->render('index/index.html.twig');
    }
}

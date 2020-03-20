<?php

namespace App\UI\Controller;

use App\Domain\Service\Sender;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class IndexController extends AbstractController
{
    public function index(Sender $sender)
    {
        $data = [
            'id' => 'd4141414124',
            'to' => ['+380938982443', 'alarmdemo@ukr.net'],
            'language' => 'en',
            'template' => 'welcome'
        ];

        $sender->execute($data);

        dump('sent');
        die();
    }
}
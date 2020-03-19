<?php

namespace App\UI\Controller;

use App\Domain\Service\Sender;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class IndexController extends AbstractController
{
    public function index(Sender $sender)
    {
        $data = [
            'id' => 's213424241424',
            'to' => '+380938982443',
            'type' => 'phone',
            'template' => 'welcome'
        ];

        $sender->execute($data);
    }
}
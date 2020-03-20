<?php

namespace App\DataFixtures;

use App\Domain\Document\Template;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $template = new Template();
        $template->setName('welcome');
        $template->setType('phone');
        $template->setLanguage('en');
        $template->setSubject('Welcome!');
        $template->setContext('Welcome sms message!');

        $manager->persist($template);
        $manager->flush();
    }
}

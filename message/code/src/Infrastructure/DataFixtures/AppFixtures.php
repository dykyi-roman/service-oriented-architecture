<?php

namespace App\Infrastructure\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;

class AppFixtures extends Fixture
{
    public function load($manager): void
    {
        // $product = new Product();
        // $manager->persist($product);

        $manager->flush();
    }
}

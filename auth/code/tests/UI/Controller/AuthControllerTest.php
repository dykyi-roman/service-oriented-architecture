<?php

namespace App\Tests\UI\Controller;

use App\Tests\DBHelper;
use App\UI\Api\AuthController;
use Faker\Factory;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;

/**
 * @coversDefaultClass \App\UI\Api\AuthController
 */
class AuthControllerTest extends WebTestCase
{
    /**
     * @covers ::register
     */
    public function testRegisterSuccess(): void
    {
        self::bootKernel();
        $container = self::$kernel->getContainer();

        $controller = new AuthController();
        $response = $controller->register($this->createRequest(), $container->get('tactician.commandbus.default'));

        $this->assertEquals($response->getStatusCode(), 201);

        $content = json_decode($response->getContent(), true, 512, JSON_THROW_ON_ERROR);
        $this->assertArrayHasKey('status', $content);
        $this->assertArrayHasKey('data', $content);
        $this->assertEquals($content['status'], 'success');

        DBHelper::clearTable('users');
    }

    private function createRequest(): Request
    {
        $faker = Factory::create();
        $data = [
            'email' => $faker->email,
            'password' => $faker->password,
            'phone' => $faker->phoneNumber,
            'firstName' => $faker->firstName,
            'lastName' => $faker->lastName,
        ];

        return Request::create('/api/user/registration', 'POST', $data, [], [], []);
    }
}

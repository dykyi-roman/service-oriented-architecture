<?php

declare(strict_types=1);

namespace App\Tests\UI\Controller;

use App\Domain\Service\UserFinder;
use App\Infrastructure\Repository\Doctrine\UserRepository;
use App\Infrastructure\Repository\InMemory\InMemoryUserRepository;
use App\UI\Controller\UserController;
use Faker\Factory;
use Ramsey\Uuid\Uuid;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;

/**
 * @coversDefaultClass \App\UI\Controller\UserController
 */
class UserControllerTest extends WebTestCase
{
    /**
     * @covers ::user
     */
    public function testGetUserWithEmptyIdFailed(): void
    {
        $request = Request::create('/api/user/', 'GET', ['id' => ''], [], [], []);
        $userFinder = $this->createMock(UserFinder::class);

        $controller = new UserController();
        $response = $controller->user($request, $userFinder);

        $this->assertEquals($response->getStatusCode(), 404);
    }

    /**
     * @covers ::user
     */
    public function testGetUserWithEmptyIdFailed2(): void
    {
        $id = Uuid::uuid4();
        $request = Request::create('/api/user/', 'GET', ['id' => $id->toString()], [], [], []);

        $faker = Factory::create();
        $inMemoryUserRepository = new InMemoryUserRepository();
        $inMemoryUserRepository->createUser(
            $id,
            $faker->email,
            $faker->password,
            $faker->phoneNumber,
            $faker->firstName
        );

        $controller = new UserController();
        $response = $controller->user($request, new UserFinder($inMemoryUserRepository));

        $this->assertEquals($response->getStatusCode(), 200);

        $content = json_decode($response->getContent(), true, 512, JSON_THROW_ON_ERROR);
        $this->assertArrayHasKey('status', $content);
        $this->assertArrayHasKey('data', $content);
        $this->assertEquals($content['status'], 'success');
    }
}

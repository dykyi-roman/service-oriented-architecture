<?php

declare(strict_types=1);

namespace App\Tests\UI\Http\Rest;

use App\Domain\User\Entity\User;
use App\Domain\User\Service\UserFinder;
use App\Infrastructure\Repository\InMemory\InMemoryUserRepository;
use App\UI\Http\Rest\AdminController;
use Faker\Factory;
use Faker\Generator;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;

/**
 * @coversDefaultClass \App\UI\Http\Rest\AdminController
 */
class AdminControllerTest extends WebTestCase
{
    private Generator $faker;

    public function __construct(?string $name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->faker = Factory::create();
    }

    /**
     * @covers ::getOne
     */
    public function testGetUserById(): void
    {
        $controller = new AdminController();
        $uuid = Uuid::uuid4();

        $repository = new InMemoryUserRepository();
        $repository->store($this->createUser($uuid));

        $request = Request::create('', 'GET', ['id' => $uuid->toString()]);
        $userFinder = new UserFinder($repository);

        $responseJson = $controller->getOne($request, $userFinder);
        $response = json_decode($responseJson->getContent());

        $this->assertSame($response->status, 'success');
    }

    /**
     * @covers ::getAll
     */
    public function testAllUserById(): void
    {
        $controller = new AdminController();
        $repository = new InMemoryUserRepository();
        $repository->store($this->createUser(Uuid::uuid4()));
        $repository->store($this->createUser(Uuid::uuid4()));
        $repository->store($this->createUser(Uuid::uuid4()));

        $userFinder = new UserFinder($repository);

        $responseJson = $controller->getAll($userFinder);
        $response = json_decode($responseJson->getContent());

        $this->assertSame($response->status, 'success');
        $this->assertCount(3, $userFinder->findAll());
    }

    private function createUser(UuidInterface $uuid): User
    {
        $user = new User($uuid);
        $user->setEmail($this->faker->email);
        $user->setPhone($this->faker->e164PhoneNumber);
        $user->setFullName($this->faker->name);

        return $user;
    }
}

<?php

declare(strict_types=1);

namespace App\Tests\UI\Http\Rest;

use App\Domain\User\Entity\User;
use App\Tests\DBHelper;
use App\UI\Http\Rest\UserController;
use Faker\Factory;
use Faker\Generator;
use PHPUnit\Framework\MockObject\MockObject;
use Ramsey\Uuid\Uuid;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

/**
 * @coversDefaultClass UserController
 */
class UserControllerTest extends WebTestCase
{
    private Generator $faker;

    public function __construct(?string $name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->faker = Factory::create();
    }

    /**
     * @covers ::user
     */
    public function testGetUserWithEmptyUserInTheStorage(): void
    {
        $controller = new UserController();
        $response = $controller->user($this->createTokenStorage(null));

        $this->assertEquals($response->getStatusCode(), 404);
    }

    /**
     * @covers ::user
     */
    public function testGeCurrentUser(): void
    {
        $user = new User(Uuid::uuid4());
        $user->setPhone($this->faker->e164PhoneNumber);
        $user->setEmail($this->faker->email);
        $user->setFullName($this->faker->name);
        $user->setPassword($this->faker->password);

        $controller = new UserController();
        $response = $controller->user($this->createTokenStorage($user));
        $this->assertEquals($response->getStatusCode(), 200);

        $content = json_decode($response->getContent(), true, 512, JSON_THROW_ON_ERROR);
        $this->assertArrayHasKey('status', $content);
        $this->assertArrayHasKey('data', $content);
        $this->assertEquals($content['status'], 'success');
    }

    /**
     * @covers ::register
     */
    public function testRegisterSuccess(): void
    {
        self::bootKernel();
        $container = self::$kernel->getContainer();

        $controller = new UserController();
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
            'phone' => $faker->e164PhoneNumber,
            'firstName' => $faker->firstName,
            'lastName' => $faker->lastName,
        ];

        return Request::create('/api/user/registration', 'POST', $data, [], [], []);
    }

    private function createTokenStorage($user = null): MockObject
    {
        $token = $this->createMock(TokenInterface::class);
        $token->method('getUser')->willReturn($user);

        $tokenStorage = $this->createMock(TokenStorage::class);
        $tokenStorage->method('getToken')->willReturn($token);

        return $tokenStorage;
    }
}

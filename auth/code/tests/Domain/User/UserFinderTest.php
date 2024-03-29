<?php

declare(strict_types=1);

namespace App\Tests\Domain\User;

use App\Domain\User\Entity\User;
use App\Domain\User\Service\UserFinder;
use App\Infrastructure\Repository\InMemory\InMemoryUserRepository;
use Ramsey\Uuid\Uuid;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * @coversDefaultClass UserFinder
 */
class UserFinderTest extends WebTestCase
{
    /**
     * @var InMemoryUserRepository|object|null
     */
    private $inMemoryUserRepository;

    public function setUp(): void
    {
        parent::setUp(); // TODO: Change the autogenerated stub
        self::bootKernel();

        $container = self::$kernel->getContainer();
        $this->inMemoryUserRepository = $container->get(InMemoryUserRepository::class);
    }

    /**
     * @covers ::findById
     */
    public function testFindByIdSuccess(): void
    {
        $id = Uuid::uuid4();
        $this->inMemoryUserRepository->createUser(
            $id,
            'test@gmail.com',
            'test-password',
            '+380938982443',
            'Dikiy Roman'
        );

        $userFinder = new UserFinder($this->inMemoryUserRepository);
        $user = $userFinder->findActiveUserById($id->toString());
        $this->assertInstanceOf(User::class, $user);
    }

    /**
     * @covers ::findById
     */
    public function testFindByIdFailed(): void
    {
        $userFinder = new UserFinder($this->inMemoryUserRepository);
        $user = $userFinder->findActiveUserById(Uuid::uuid4()->toString());
        $this->assertNull($user);
    }
}

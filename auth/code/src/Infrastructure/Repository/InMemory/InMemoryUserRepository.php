<?php

declare(strict_types=1);

namespace App\Infrastructure\Repository\InMemory;

use App\Domain\Repository\UserRepositoryInterface;
use App\Domain\Entity\User;
use Doctrine\ORM\ORMException;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class InMemoryUserRepository implements UserRepositoryInterface
{
    /**
     * @var User[]
     */
    private array $users = [];
    private UserPasswordEncoderInterface $encoder;

    public function findUserByEmail(string $email): ?User
    {
        foreach ($this->users as $id => $user) {
            if ($user->getEmail() === $email) {
                return $user;
            }
        }

        return null;
    }

    public function findUserById(string $userId): ?User
    {
        return isset($this->users[$userId]) ? $this->users[$userId] : null;
    }

    /**
     * @inheritDoc
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function createUser(
        UuidInterface $uuid,
        string $email,
        string $password,
        string $phone,
        string $fullName
    ): void {
        if (isset($this->users[$uuid->toString()])) {
            throw new ORMException('User exist exception');
        }

        $user = new User($uuid);
        $user->setEmail($email);
        $user->setPhone($phone);
        $user->setFullName($fullName);
        $user->setPassword($password);

        $this->users[$uuid->toString()] = $user;
    }
}

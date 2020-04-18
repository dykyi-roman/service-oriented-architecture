<?php

declare(strict_types=1);

namespace App\Infrastructure\Repository\InMemory;

use App\Domain\Entity\User;
use App\Domain\Repository\UserRepositoryInterface;
use Doctrine\ORM\ORMException;
use Ramsey\Uuid\UuidInterface;

class InMemoryUserRepository implements UserRepositoryInterface
{
    /**
     * @var User[]
     */
    private array $users = [];

    public function findUserByEmail(string $email): ?User
    {
        foreach ($this->users as $id => $user) {
            if ($user->getEmail() === $email) {
                return $this->users[$id];
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

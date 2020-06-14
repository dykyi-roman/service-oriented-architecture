<?php

declare(strict_types=1);

namespace App\Infrastructure\Repository\InMemory;

use App\Domain\User\Entity\User;
use App\Domain\User\Repository\ReadUserRepositoryInterface;
use App\Domain\User\Repository\WriteUserRepositoryInterface;
use Doctrine\ORM\ORMException;
use Ramsey\Uuid\UuidInterface;

class InMemoryUserRepository implements WriteUserRepositoryInterface, ReadUserRepositoryInterface
{
    /**
     * @var User[]
     */
    private array $users = [];

    public function findActiveUserByEmail(string $email): ?User
    {
        foreach ($this->users as $id => $user) {
            if ($user->getEmail() === $email) {
                return $this->users[$id];
            }
        }

        return null;
    }

    public function findActiveUserById(string $userId): ?User
    {
        return isset($this->users[$userId]) ? $this->users[$userId] : null;
    }

    public function findUserById(string $id): ?User
    {
        return isset($this->users[$id]) ? $this->users[$id] : null;
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

    public function findUserByEmailOrPhone(string $contact): ?User
    {
        foreach ($this->users as $user) {
            if ($user->getPhone() === $contact || $user->getEmail() === $contact) {
                return $user;
            }
        }

        return null;
    }

    public function store(User $user): void
    {
        $this->users[$user->getId()->toString()] = $user;
    }

    public function all(): array
    {
        return $this->users;
    }

    public function updateUser(UuidInterface $uuid, string $fullName, bool $active): void
    {
        $user = $this->users[$uuid->toString()];
        $user->setFullName($fullName);
        $user->setActive($active);

        $this->users[$uuid->toString()] = $user;
    }
}

<?php

declare(strict_types=1);

namespace App\Domain\Repository;

use App\Domain\Entity\User;
use Ramsey\Uuid\UuidInterface;

interface UserRepositoryInterface
{
    public function createUser(UuidInterface $uuid, string $email, string $password, string $phone, string $firstName): void;

    public function findUserByEmail(string $email): ?User;

    public function findUserById(string $id): ?User;
}

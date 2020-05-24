<?php

declare(strict_types=1);

namespace App\Domain\User\Repository;

use App\Domain\User\Entity\User;
use Ramsey\Uuid\UuidInterface;

interface WriteUserRepositoryInterface
{
    public function createUser(
        UuidInterface $uuid,
        string $email,
        string $password,
        string $phone,
        string $fullName
    ): void;

    public function store(User $user): void;
}

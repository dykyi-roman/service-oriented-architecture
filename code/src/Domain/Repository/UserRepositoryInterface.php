<?php

declare(strict_types=1);

namespace App\Domain\Repository;

interface UserRepositoryInterface
{
    public function createUser(string $email, string $password, string $username): void;
}

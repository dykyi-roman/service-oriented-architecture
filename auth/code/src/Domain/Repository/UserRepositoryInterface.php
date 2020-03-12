<?php

declare(strict_types=1);

namespace App\Domain\Repository;

use App\Domain\Entity\User;

interface UserRepositoryInterface
{
    public function createUser(string $email, string $password, string $username): void;

    public function findUserByEmail(string $email): ?User;

    public function loadUserByUsername(string $usernameOrEmail = null);
}

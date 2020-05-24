<?php

declare(strict_types=1);

namespace App\Domain\User\Repository;

use App\Domain\User\Entity\User;

interface ReadUserRepositoryInterface
{
    public function findUserByEmail(string $email): ?User;

    public function findUserById(string $id): ?User;

    public function findUserByEmailOrPhone(string $contact): ?User;
}

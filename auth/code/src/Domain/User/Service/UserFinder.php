<?php

declare(strict_types=1);

namespace App\Domain\User\Service;

use App\Domain\User\Entity\User;
use App\Domain\User\Repository\ReadUserRepositoryInterface;

class UserFinder
{
    private ReadUserRepositoryInterface $userRepository;

    public function __construct(ReadUserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function findById(string $userId): ?User
    {
        return $this->userRepository->findUserById($userId);
    }

    public function findByContact(string $contact): ?User
    {
        return $this->userRepository->findUserByEmailOrPhone($contact);
    }

    public function findAll(): array
    {
        return $this->userRepository->all();
    }
}

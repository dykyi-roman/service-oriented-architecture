<?php

declare(strict_types=1);

namespace App\Domain\User\Service;

use App\Domain\User\Entity\User;
use App\Domain\User\Repository\WriteUserRepositoryInterface;

final class UserStore
{
    private WriteUserRepositoryInterface $userRepository;

    public function __construct(WriteUserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function store(User $user): void
    {
        $this->userRepository->store($user);
    }
}

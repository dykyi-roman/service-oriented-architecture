<?php

declare(strict_types=1);

namespace App\Application\User\DTO;

use App\Domain\User\Entity\User;

final class ApiUserDTO
{
    public static function transform(User $user): array
    {
        return [
            'id' => $user->getId(),
            'email' => $user->getEmail(),
            'phone' => $user->getPhone(),
            'fullName' => $user->getFullName(),
            'isActive' => $user->isActive(),
        ];
    }
}

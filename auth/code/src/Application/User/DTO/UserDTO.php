<?php

declare(strict_types=1);

namespace App\Application\User\DTO;

use App\Domain\User\Entity\User;

final class UserDTO
{
    public static function transform(User $user): array
    {
        return [
            'email' => $user->getEmail(),
            'phone' => $user->getPhone(),
            'fullName' => $user->getFullName(),
            'isActive' => $user->isActive(),
        ];
    }
}

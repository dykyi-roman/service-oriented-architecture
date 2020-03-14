<?php

declare(strict_types=1);

namespace App\Domain\Transformer\Api;

use App\Domain\Entity\User;

final class UserApiTransformer
{
    private function __construct()
    {
        //Mock __construct
    }

    public static function transform(User $user): array
    {
        return [
            'fullName' => $user->getFullName(),
            'email' => $user->getEmail(),
            'phone' => $user->getPhone(),
            'isActive' => $user->isActive(),
        ];
    }
}
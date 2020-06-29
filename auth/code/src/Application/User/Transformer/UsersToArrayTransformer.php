<?php

declare(strict_types=1);

namespace App\Application\User\Transformer;

use App\Application\User\DTO\ApiUserDTO;
use App\Domain\User\Entity\User;

final class UsersToArrayTransformer
{
    private function __construct()
    {
        //Mock __construct
    }

    public static function transformForApi(array $users): array
    {
        return array_map(fn(User $user) => ApiUserDTO::transform($user), $users);
    }
}

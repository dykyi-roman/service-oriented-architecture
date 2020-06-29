<?php

declare(strict_types=1);

namespace App\Application\Auth\Transformer;

use App\Application\Auth\DTO\UserDTO;

class ArrayToUsersTransformer
{
    public static function transform(array $data): array
    {
        return array_map((fn(array $user) => new UserDTO($user)), $data);
    }
}

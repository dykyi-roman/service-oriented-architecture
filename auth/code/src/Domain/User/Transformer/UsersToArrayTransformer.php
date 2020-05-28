<?php

declare(strict_types=1);

namespace App\Domain\User\Transformer;

use App\Domain\User\Entity\User;

final class UsersToArrayTransformer
{
    private function __construct()
    {
        //Mock __construct
    }

    /**
     * @param User[] $users
     * @return array
     */
    public static function transform(array $users): array
    {
        return array_map(fn(User $template) => $template->toArray(), $users);
    }
}

<?php

declare(strict_types=1);

namespace App\Domain\User\Exception;

use Exception;

class UserException extends Exception
{
    public static function createUser(): UserException
    {
        return new static('Create user problem', 5100);
    }
}

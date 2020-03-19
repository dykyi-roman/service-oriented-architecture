<?php

declare(strict_types=1);

namespace App\Domain\Exception;

class PhoneException extends \InvalidArgumentException
{
    public static function notCorrectPhoneNumber(): PhoneException
    {
        return new static('Phone number is not correct');
    }
}

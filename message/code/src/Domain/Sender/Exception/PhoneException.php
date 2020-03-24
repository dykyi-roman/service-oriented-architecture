<?php

declare(strict_types=1);

namespace App\Domain\Sender\Exception;

class PhoneException extends \InvalidArgumentException
{
    public static function notCorrectPhoneNumber(): self
    {
        return new static('Phone number is not correct');
    }
}

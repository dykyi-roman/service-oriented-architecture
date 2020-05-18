<?php

namespace App\Domain\Auth\ValueObject;

use App\Domain\Auth\Exception\AuthException;

final class Phone
{
    private string $phone;

    public function __construct(string $phone)
    {
        $this->assertWhenInvalidPhone($phone);

        $this->phone = $phone;
    }

    /**
     * @throws AuthException
     */
    private function assertWhenInvalidPhone(string $phone): void
    {
        if (!$this->isValidateMobile($phone)) {
            throw AuthException::invalidPhoneFormat();
        }
    }

    public function toString(): string
    {
        return $this->phone;
    }

    public function __toString(): string
    {
        return $this->phone;
    }

    private function isValidateMobile(string $value): bool
    {
        $filteredPhoneNumber = filter_var($value, FILTER_SANITIZE_NUMBER_INT);
        $phoneToCheck = str_replace('-', '', $filteredPhoneNumber);
        $tmp = \strlen($phoneToCheck);

        return !($tmp < 10 || $tmp > 14);
    }
}

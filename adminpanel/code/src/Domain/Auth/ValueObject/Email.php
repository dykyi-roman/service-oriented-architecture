<?php

namespace App\Domain\Auth\ValueObject;

use App\Domain\Auth\Exception\AuthException;

final class Email
{
    private string $email;

    public function __construct(string $email)
    {
        $this->assertWhenInvalidEmail($email);

        $this->email = $email;
    }

    /**
     * @throws AuthException
     */
    private function assertWhenInvalidEmail(string $value): void
    {
        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
            throw AuthException::invalidEmailFormat();
        }
    }

    public function toString(): string
    {
        return $this->email;
    }

    public function __toString(): string
    {
        return $this->email;
    }
}

<?php

declare(strict_types=1);

namespace App\Domain\Auth\ValueObject;

use App\Domain\Auth\Exception\AuthException;

final class Password
{
    private const PASSWORD_MIN_SIZE = 6;

    private string $password;

    public function __construct(string $password)
    {
        $this->assertWhenPasswordIsTooShort($password);

        $this->password = $password;
    }

    public function __toString()
    {
        return $this->toString();
    }

    public function toString(): string
    {
        return $this->password;
    }

    /**
     * @throws AuthException
     */
    private function assertWhenPasswordIsTooShort(string $password): void
    {
        if (mb_strlen($password) < self::PASSWORD_MIN_SIZE) {
            throw AuthException::passwordLengthIsTooShort(self::PASSWORD_MIN_SIZE);
        }
    }
}

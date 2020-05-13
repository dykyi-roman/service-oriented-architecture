<?php

declare(strict_types=1);

namespace App\Application\Auth\Request;

use App\Application\Auth\Exception\AppAuthException;

final class LoginRequest
{
    private const PASSWORD_MIN_SIZE = 6;

    private string $login;
    private string $password;

    public function __construct(string $login, string $password)
    {
        $this->login = $login;
        $this->password = $password;

        $this->assertWhenEmptyFields();
        $this->assertWhenLoginIsNotEmail();
        $this->assertWhenPasswordIsTooShort();
    }

    /**
     * @throws AppAuthException
     */
    public function assertWhenEmptyFields(): void
    {
        if ('' === $this->password || '' === $this->login) {
            throw AppAuthException::requireFieldsIsEmpty();
        }
    }

    /**
     * @throws AppAuthException
     */
    public function assertWhenLoginIsNotEmail(): void
    {
        if (!filter_var($this->login, FILTER_VALIDATE_EMAIL)) {
            throw AppAuthException::requireFieldsIsEmpty();
        }
    }

    /**
     * @throws AppAuthException
     */
    public function assertWhenPasswordIsTooShort(): void
    {
        if (mb_strlen($this->password) <= self::PASSWORD_MIN_SIZE) {
            throw AppAuthException::passwordLengthIsTooShort(self::PASSWORD_MIN_SIZE);
        }
    }

    public function login(): string
    {
        return $this->login;
    }

    public function password(): string
    {
        return $this->password;
    }
}

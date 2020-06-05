<?php

declare(strict_types=1);

namespace App\Application\Auth\Request;

use App\Application\Auth\Exception\AppAuthException;
use App\Domain\Auth\Exception\AuthException;
use App\Domain\Auth\ValueObject\Email;
use App\Domain\Auth\ValueObject\Password;

final class LoginRequest
{
    private Email $login;
    private Password $password;

    public function __construct(string $login, string $password)
    {
        $this->assertWhenEmptyFields([$login, $password]);
        try {
            $this->login = new Email($login);
            $this->password = new Password($password);
        } catch (AuthException $exception) {
            throw AppAuthException::domainException($exception->getMessage(), $exception->getCode());
        }
    }

    /**
     * @throws AppAuthException
     */
    private function assertWhenEmptyFields(array $fields): void
    {
        foreach ($fields as $field) {
            if ('' === $field) {
                throw AppAuthException::requireFieldsIsEmpty();
            }
        }
    }

    public function login(): Email
    {
        return $this->login;
    }

    public function password(): Password
    {
        return $this->password;
    }
}
